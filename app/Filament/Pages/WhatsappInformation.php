<?php

namespace App\Filament\Pages;

use App\Helpers\WahaApi;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use App\Filament\Widgets\WhatsappStatusWidget;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Support\Enums\Width;
use Illuminate\Support\Facades\Cache;

class WhatsappInformation extends Page
{
    use HasPageShield;

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?string $navigationLabel = 'WhatsApp';
    protected static ?int $navigationSort = 10;
    protected string $view = 'filament.pages.whatsapp-information';

    // ── Session State ──────────────────────────────────────────────
    public ?string $status       = null;
    public ?string $qrCode       = null;
    public ?array  $me           = null;
    public ?array  $sessions     = null;
    public ?string $screenshot   = null;
    public ?string $errorMessage = null;

    // ── Contact Selector ───────────────────────────────────────────
    /** @var array<int, array{id: string, name: string, number: string, initials: string}> */
    public array  $contacts        = [];
    public bool   $contactsLoading = false;
    public string $contactSearch   = '';
    public string $selectedContact = '';   // stores the chatId (number@c.us)
    public string $selectedName    = '';   // display name of selected contact

    // ── Chat Composer ──────────────────────────────────────────────
    public string $sendTo      = '';   // raw number (no @c.us), kept in sync with selectedContact
    public string $sendMessage = '';

    // ── Chat History Modal ─────────────────────────────────────────
    public bool   $showChatModal  = false;
    public array  $chatMessages   = [];
    public bool   $chatLoading    = false;
    public string $modalContactName = '';

    // Cache TTL for contacts list (seconds)
    private const CONTACTS_CACHE_TTL = 3600; // 1 hour

    // ──────────────────────────────────────────────────────────────

    public function mount(): void
    {
        $this->refreshStatus();
    }

    public function getMaxContentWidth(): Width | string | null
    {
        return Width::Full;
    }

    protected function getHeaderWidgets(): array
    {
        return [
            // WhatsappStatusWidget::class
        ];
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('refresh')
                ->label('Refresh')
                ->icon('heroicon-o-arrow-path')
                ->action('refreshStatus'),

            Action::make('start')
                ->label('Start Session')
                ->color('success')
                ->icon('heroicon-o-play')
                ->visible(fn(): bool => $this->status === 'STOPPED')
                ->action('startSession'),

            Action::make('restart')
                ->label('Restart Session')
                ->color('warning')
                ->icon('heroicon-o-arrow-path')
                ->requiresConfirmation()
                ->visible(fn(): bool => in_array($this->status, ['WORKING', 'SCAN_QR_CODE']))
                ->action('restartSession'),

            Action::make('stop')
                ->label('Stop Session')
                ->color('danger')
                ->icon('heroicon-o-stop')
                ->requiresConfirmation()
                ->visible(fn(): bool => in_array($this->status, ['WORKING', 'SCAN_QR_CODE', 'STARTING']))
                ->action('stopSession'),

            Action::make('disconnect')
                ->label('Logout')
                ->color('danger')
                ->icon('heroicon-o-power')
                ->requiresConfirmation()
                ->visible(fn(): bool => in_array($this->status, ['WORKING', 'SCAN_QR_CODE']))
                ->action('disconnect'),
        ];
    }

    // ══════════════════════════════════════════════════════════════
    //  SESSION ACTIONS
    // ══════════════════════════════════════════════════════════════

    public function refreshStatus(): void
    {
        $wahaApi = new WahaApi();
        $status  = $wahaApi->getSessionStatus();

        $this->qrCode       = null;
        $this->me           = null;
        $this->sessions     = null;
        $this->screenshot   = null;
        $this->errorMessage = null;

        if (is_array($status) && !empty($status['connection_failed'])) {
            $this->status       = 'OFFLINE';
            $this->errorMessage = $status['message'] ?? 'Could not connect to WAHA server.';
            return;
        }

        if (is_array($status) && !empty($status['error']) && !isset($status['status'])) {
            $this->status       = 'ERROR';
            $this->errorMessage = $status['message'] ?? ($status['detail'] ?? 'An API error occurred.');
            return;
        }

        $this->status = $status['status'] ?? 'UNKNOWN';

        if ($this->status === 'SCAN_QR_CODE') {
            $this->qrCode = $wahaApi->getQrCode() ?? null;
        } elseif ($this->status === 'WORKING') {
            $this->me         = $wahaApi->getMe();
            $this->sessions   = $wahaApi->getSessions();
            $this->screenshot = $wahaApi->getScreenshot();
            $this->loadContacts();
        }
    }

    public function startSession(): void
    {
        $this->callWaha('startSession', 'Starting session…', 'Failed to start session');
    }

    public function stopSession(): void
    {
        $this->callWaha('stopSession', 'Session stopped.', 'Failed to stop session');
    }

    public function restartSession(): void
    {
        $this->callWaha('restartSession', 'Restarting session…', 'Failed to restart session');
    }

    public function disconnect(): void
    {
        $this->callWaha('logout', 'Logged out successfully.', 'Failed to logout');
    }

    // ══════════════════════════════════════════════════════════════
    //  CONTACTS
    // ══════════════════════════════════════════════════════════════

    /**
     * Load & cache contacts. Served from cache until TTL expires or
     * the user explicitly calls refreshContacts().
     */
    public function loadContacts(): void
    {
        $cacheKey = 'waha_contacts_default';

        $cached = Cache::get($cacheKey);

        if ($cached !== null) {
            $this->contacts = $cached;
            return;
        }

        $this->fetchAndCacheContacts();
    }

    /**
     * Force a fresh fetch from the API, bypass cache.
     * Bound to the "Refresh contacts" button in the UI.
     */
    public function refreshContacts(): void
    {
        Cache::forget('waha_contacts_default');
        $this->fetchAndCacheContacts();

        Notification::make()
            ->title('Contacts refreshed')
            ->success()
            ->send();
    }

    private function fetchAndCacheContacts(): void
    {
        $wahaApi  = new WahaApi();
        $response = $wahaApi->getContacts();

        if (!is_array($response) || !empty($response['error']) || !empty($response['connection_failed'])) {
            // Silently fail — composer still usable with manual number entry
            $this->contacts = [];
            return;
        }

        // Normalise: WAHA returns a flat array of contact objects
        $contacts = [];
        foreach ($response as $contact) {
            if (!is_array($contact)) continue;

            $id   = $contact['id'] ?? '';
            $name = $contact['name'] ?? $contact['pushname'] ?? $contact['notify'] ?? '';

            // Skip groups (@g.us) and broadcasts, keep only individual chats (@c.us)
            if (!str_ends_with($id, '@c.us')) continue;
            // Skip contacts with no usable name
            if (trim($name) === '') continue;

            $number   = str_replace('@c.us', '', $id);
            $initials = $this->makeInitials($name);

            $contacts[] = [
                'id'       => $id,
                'name'     => $name,
                'number'   => $number,
                'initials' => $initials,
            ];
        }

        // Sort A→Z by name
        usort($contacts, fn($a, $b) => strcmp($a['name'], $b['name']));

        Cache::put('waha_contacts_default', $contacts, self::CONTACTS_CACHE_TTL);
        $this->contacts = $contacts;
    }

    /**
     * Called by the contact picker in the blade via wire:click="selectContact('...')"
     */
    public function selectContact(string $chatId): void
    {
        $this->selectedContact = $chatId;
        $this->sendTo          = str_replace('@c.us', '', $chatId);

        // Find display name from loaded contacts
        $match = collect($this->contacts)->firstWhere('id', $chatId);
        $this->selectedName = $match['name'] ?? $this->sendTo;

        // Close the dropdown (handled client-side too, but reset search)
        $this->contactSearch = '';
    }

    /**
     * Clear the selected contact so the user can type manually or re-pick.
     */
    public function clearContact(): void
    {
        $this->selectedContact = '';
        $this->selectedName    = '';
        $this->sendTo          = '';
    }

    /**
     * Computed: contacts filtered by search string.
     * Livewire computed properties aren't cached by default in v3, so this
     * is a plain method called from the blade.
     */
    public function getFilteredContacts(): array
    {
        if ($this->contactSearch === '') {
            return array_slice($this->contacts, 0, 50); // cap initial list for performance
        }

        $q = mb_strtolower($this->contactSearch);

        return array_values(array_filter(
            $this->contacts,
            fn($c) => str_contains(mb_strtolower($c['name']), $q)
                || str_contains($c['number'], $q)
        ));
    }

    // ══════════════════════════════════════════════════════════════
    //  CHAT HISTORY MODAL
    // ══════════════════════════════════════════════════════════════

    /**
     * Open the chat history modal for the currently selected contact.
     */
    public function openChatHistory(): void
    {
        if (!$this->selectedContact) {
            Notification::make()
                ->title('Select a contact first')
                ->warning()
                ->send();
            return;
        }

        $this->modalContactName = $this->selectedName ?: $this->sendTo;
        $this->chatMessages     = [];
        $this->chatLoading      = true;
        $this->showChatModal    = true;

        $this->fetchChatMessages();
    }

    public function closeChatModal(): void
    {
        $this->showChatModal = false;
        $this->chatMessages  = [];
        $this->chatLoading   = false;
    }

    private function fetchChatMessages(): void
    {
        $wahaApi  = new WahaApi();
        $response = $wahaApi->getChatMessages($this->selectedContact);

        $this->chatLoading = false;

        if (!is_array($response) || !empty($response['error']) || !empty($response['connection_failed'])) {
            $this->chatMessages = [];
            Notification::make()
                ->title('Could not load messages')
                ->body($response['message'] ?? 'No messages returned.')
                ->warning()
                ->send();
            return;
        }

        // Normalise messages — WAHA shape varies slightly by version
        $messages = [];
        foreach ($response as $msg) {
            if (!is_array($msg)) continue;

            $body      = $msg['body'] ?? $msg['text'] ?? $msg['caption'] ?? '';
            $fromMe    = (bool) ($msg['fromMe'] ?? false);
            $timestamp = $msg['timestamp'] ?? null;
            $type      = $msg['type'] ?? 'chat';

            // For media messages with no body, show a friendly placeholder
            if (trim($body) === '') {
                $body = match ($type) {
                    'image'    => '📷 Image',
                    'video'    => '🎥 Video',
                    'audio', 'ptt' => '🎙 Voice message',
                    'document' => '📄 Document',
                    'sticker'  => '🪄 Sticker',
                    'location' => '📍 Location',
                    default    => '(unsupported message)',
                };
            }

            $messages[] = [
                'body'      => $body,
                'fromMe'    => $fromMe,
                'timestamp' => $timestamp ? date('d M, H:i', $timestamp) : null,
                'type'      => $type,
            ];
        }

        // WAHA returns newest-first; reverse so oldest is at top (natural chat order)
        $this->chatMessages = array_reverse($messages);
    }

    // ══════════════════════════════════════════════════════════════
    //  SEND CHAT
    // ══════════════════════════════════════════════════════════════

    public function sendChat(): void
    {
        $to      = trim($this->sendTo);
        $message = trim($this->sendMessage);

        if ($to === '') {
            Notification::make()
                ->title('Recipient required')
                ->body('Select a contact or enter a number with country code.')
                ->warning()
                ->send();
            return;
        }

        if ($message === '') {
            Notification::make()
                ->title('Message is empty')
                ->body('Type something before sending.')
                ->warning()
                ->send();
            return;
        }

        $to = ltrim($to, '+');

        $wahaApi  = new WahaApi();
        $response = $wahaApi->sendMessage($to, $message);

        if (!empty($response['connection_failed'])) {
            Notification::make()
                ->title('Send failed — connection error')
                ->body($response['message'] ?? 'Could not reach WAHA server.')
                ->danger()
                ->send();
            return;
        }

        if (!empty($response['error'])) {
            Notification::make()
                ->title('Send failed')
                ->body($response['message'] ?? ($response['detail'] ?? 'WAHA returned an error.'))
                ->danger()
                ->send();
            return;
        }

        Notification::make()
            ->title('Message sent')
            ->body('Delivered to ' . ($this->selectedName ?: "+{$to}") . '.')
            ->success()
            ->send();

        $this->sendMessage = '';
    }

    // ══════════════════════════════════════════════════════════════
    //  HELPERS
    // ══════════════════════════════════════════════════════════════

    private function callWaha(string $method, string $successTitle, string $failTitle): void
    {
        $wahaApi  = new WahaApi();
        $response = $wahaApi->{$method}();

        if (!empty($response['connection_failed'])) {
            Notification::make()
                ->title($failTitle)
                ->body($response['message'] ?? 'Could not reach WAHA server.')
                ->danger()
                ->send();
            return;
        }

        if (!empty($response['error'])) {
            Notification::make()
                ->title($failTitle)
                ->body($response['message'] ?? ($response['detail'] ?? 'Operation failed.'))
                ->danger()
                ->send();
            return;
        }

        Notification::make()
            ->title($successTitle)
            ->success()
            ->send();

        $this->refreshStatus();
    }

    public function makeInitials(string $name): string
    {
        $words = preg_split('/\s+/', trim($name));
        if (count($words) >= 2) {
            return strtoupper(mb_substr($words[0], 0, 1) . mb_substr($words[1], 0, 1));
        }
        return strtoupper(mb_substr($name, 0, 2));
    }
}
