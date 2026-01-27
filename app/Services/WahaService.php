<?php

namespace App\Services;

use App\Helpers\WahaApi;
use App\Models\TemplateMessage;
use Filament\Notifications\Notification;

class WahaService
{
    public function __construct(
        public WahaApi $wahaApi
    ) {}

    public function sendMessage(TemplateMessage $templateMessage, string $chatId)
    {
        $content = $this->translateHtmlToPlainText($templateMessage->content);
        $response = $this->wahaApi->sendMessage($chatId, $content);

        return $this->parseResponse($response);
    }

    private function translateHtmlToPlainText(string $html): string
    {
        return strip_tags($html);
    }

    private function translateHtmlToWhatsAppCode(string $html): string
    {
        // First, convert HTML formatting to WhatsApp formatting
        $text = $html;

        // Convert bold tags to WhatsApp bold (asterisk)
        $text = preg_replace('/<strong[^>]*>(.*?)<\/strong>/is', '*$1*', $text);
        $text = preg_replace('/<b[^>]*>(.*?)<\/b>/is', '*$1*', $text);

        // Convert italic tags to WhatsApp italic (underscore)
        $text = preg_replace('/<em[^>]*>(.*?)<\/em>/is', '_$1_', $text);
        $text = preg_replace('/<i[^>]*>(.*?)<\/i>/is', '_$1_', $text);

        // Convert strikethrough tags to WhatsApp strikethrough (tilde)
        $text = preg_replace('/<s[^>]*>(.*?)<\/s>/is', '~$1~', $text);
        $text = preg_replace('/<strike[^>]*>(.*?)<\/strike>/is', '~$1~', $text);
        $text = preg_replace('/<del[^>]*>(.*?)<\/del>/is', '~$1~', $text);

        // Convert code tags to WhatsApp monospace (triple backticks for blocks, single for inline)
        $text = preg_replace('/<pre[^>]*><code[^>]*>(.*?)<\/code><\/pre>/is', "```\n$1\n```", $text);
        $text = preg_replace('/<code[^>]*>(.*?)<\/code>/is', '`$1`', $text);
        $text = preg_replace('/<pre[^>]*>(.*?)<\/pre>/is', "```\n$1\n```", $text);

        // Convert paragraph tags to line breaks
        $text = preg_replace('/<p[^>]*>/i', '', $text);
        $text = preg_replace('/<\/p>/i', "\n\n", $text);

        // Convert div tags to line breaks
        $text = preg_replace('/<div[^>]*>/i', '', $text);
        $text = preg_replace('/<\/div>/i', "\n", $text);

        // Convert line break tags
        $text = preg_replace('/<br[^>]*>/i', "\n", $text);

        // Convert heading tags to bold text with line breaks
        $text = preg_replace('/<h[1-6][^>]*>(.*?)<\/h[1-6]>/is', "*$1*\n\n", $text);

        // Convert list items
        $text = preg_replace('/<li[^>]*>(.*?)<\/li>/is', "• $1\n", $text);

        // Remove remaining HTML tags
        $text = strip_tags($text);

        // Clean up excessive line breaks and whitespace
        $text = preg_replace('/\n{3,}/', "\n\n", $text); // Max 2 consecutive line breaks
        $text = preg_replace('/[ \t]+/', ' ', $text); // Multiple spaces/tabs to single space
        $text = trim($text);

        return $text;
    }

    public function sendTemplatedMessage(TemplateMessage $templateMessage, string $chatId, array $data)
    {
        $content = $this->replacePlaceholders($templateMessage->content, $data);
        $content = $this->translateHtmlToWhatsAppCode($content);
        $response = $this->wahaApi->sendMessage($chatId, $content);

        return $this->parseResponse($response);
    }

    private function replacePlaceholders(string $content, array $data): string
    {
        foreach ($data as $key => $value) {
            $content = str_replace('{{' . $key . '}}', $value, $content);
        }

        return $content;
    }

    private function parseResponse(array $response): void
    {
        if (isset($response['error'])) {
            Notification::make()
                ->title('Error')
                ->body($response['message'])
                ->danger()
                ->send();

            return;
        }

        Notification::make()
            ->title('Success')
            ->body('Message sent successfully')
            ->success()
            ->send();
    }
}
