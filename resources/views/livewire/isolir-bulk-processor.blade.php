<div
    style="font-family: ui-sans-serif, system-ui, sans-serif; background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden;">

    {{-- Header --}}
    <div
        style="display: flex; align-items: center; gap: 10px; padding: 14px 20px; border-bottom: 1px solid #f1f5f9; background: #f8fafc;">
        <div
            style="width: 28px; height: 28px; border-radius: 6px; background: #ede9fe; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="var(--primary-400)" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round">
                <path d="M5 12.55a11 11 0 0 1 14.08 0" />
                <path d="M1.42 9a16 16 0 0 1 21.16 0" />
                <path d="M8.53 16.11a6 6 0 0 1 6.95 0" />
                <circle cx="12" cy="20" r="1" fill="var(--primary-400)" stroke="none" />
            </svg>
        </div>
        <span style="font-size: 13px; font-weight: 500; color: #1e293b; letter-spacing: -0.01em;">
            {{ $type === 'sync' ? 'Sync Isolir Status' : 'Open Isolir (Paid)' }}
        </span>
        <span
            style="margin-left: auto; font-family: ui-monospace, monospace; font-size: 10px; color: #94a3b8; background: #f1f5f9; border: 1px solid #e2e8f0; padding: 2px 8px; border-radius: 4px;">
            PID #{{ getmypid() }}
        </span>
    </div>

    <div style="padding: 20px; display: flex; flex-direction: column; gap: 16px;">

        {{-- Status --}}
        <div style="display: flex; align-items: center; gap: 8px;">
            <span
                style="display: inline-block; width: 7px; height: 7px; border-radius: 50%; flex-shrink: 0;
                background: {{ $isProcessing ? '#f59e0b' : ($status === 'Completed!' ? '#10b981' : '#94a3b8') }};
                {{ $isProcessing ? 'animation: isolir-pulse 1.4s ease-in-out infinite;' : '' }}">
            </span>
            <span style="font-size: 12px; color: #475569;">{{ $status }}</span>
        </div>

        @if ($totalTasks > 0)

            {{-- Segmented ticker --}}
            <div>
                <div style="display: flex; justify-content: space-between; align-items: baseline; margin-bottom: 8px;">
                    <span
                        style="font-family: ui-monospace, monospace; font-size: 10px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.07em; font-weight: 500;">Progress</span>
                    <span
                        style="font-family: ui-monospace, monospace; font-size: 12px; color: var(--primary-400); font-weight: 500;">{{ $currentIndex }}
                        / {{ $totalTasks }}</span>
                </div>
                <div style="display: flex; gap: 2px; height: 6px;">
                    @for ($i = 0; $i < $totalTasks; $i++)
                        <div
                            style="flex: 1; border-radius: 3px; transition: background 0.3s;
                            background: {{ $i < $currentIndex ? 'var(--primary-400)' : ($i === $currentIndex && $isProcessing ? '#a5b4fc' : '#e2e8f0') }};">
                        </div>
                    @endfor
                </div>
            </div>

            {{-- Stat cards --}}
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px;">
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px;">
                    <div
                        style="font-family: ui-monospace, monospace; font-size: 22px; font-weight: 500; color: var(--primary-400); line-height: 1; margin-bottom: 4px;">
                        {{ $currentIndex }}</div>
                    <div
                        style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; font-weight: 500;">
                        Processed</div>
                </div>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px;">
                    <div
                        style="font-family: ui-monospace, monospace; font-size: 22px; font-weight: 500; color: #16a34a; line-height: 1; margin-bottom: 4px;">
                        {{ $successCount }}</div>
                    <div
                        style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; font-weight: 500;">
                        Success</div>
                </div>
                <div style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 12px 14px;">
                    <div
                        style="font-family: ui-monospace, monospace; font-size: 22px; font-weight: 500; color: #d97706; line-height: 1; margin-bottom: 4px;">
                        {{ $errorCount + $skippedCount }}</div>
                    <div
                        style="font-size: 10px; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; font-weight: 500;">
                        Skipped / Err</div>
                </div>
            </div>

            {{-- Log panel --}}
            <div x-data="{ scrollToBottom() { $el.scrollTop = $el.scrollHeight } }" x-init="scrollToBottom()"
                @process-next.window="setTimeout(() => scrollToBottom(), 50)"
                style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; height: 200px; overflow-y: auto;
                       scrollbar-width: thin; scrollbar-color: #cbd5e1 transparent;">
                <div style="padding: 10px 12px;">
                    @foreach ($log as $entry)
                        @php
                            $s = is_array($entry) ? $entry['status'] : 'ok';
                            $m = is_array($entry) ? $entry['message'] : $entry;
                            [$bg, $color, $label] = match ($s) {
                                'error' => ['#fee2e2', '#991b1b', 'ERR'],
                                'skip' => ['#fef9c3', '#854d0e', 'SKIP'],
                                default => ['#dcfce7', '#166534', 'OK'],
                            };
                        @endphp
                        <div
                            style="display: flex; align-items: baseline; gap: 8px; padding: 3px 0; border-bottom: 1px solid #f1f5f9; font-family: ui-monospace, monospace; font-size: 11px; line-height: 1.5;">
                            <span style="color: #94a3b8; flex-shrink: 0;">{{ now()->format('H:i:s') }}</span>
                            <span
                                style="font-size: 9px; padding: 1px 6px; border-radius: 3px; flex-shrink: 0; font-weight: 500; letter-spacing: 0.04em; background: {{ $bg }}; color: {{ $color }};">{{ $label }}</span>
                            <span style="color: #64748b;">{{ $m }}</span>
                        </div>
                    @endforeach

                    @if ($isProcessing)
                        <div
                            style="display: flex; align-items: center; gap: 8px; padding: 3px 0; font-family: ui-monospace, monospace; font-size: 11px;">
                            <span style="color: #94a3b8;">{{ now()->format('H:i:s') }}</span>
                            <span
                                style="font-size: 9px; padding: 1px 6px; border-radius: 3px; background: #ede9fe; color: #4338ca; font-weight: 500;">LIVE</span>
                            <span
                                style="display: inline-block; width: 6px; height: 11px; background: var(--primary-400); border-radius: 1px; animation: isolir-blink 1s step-end infinite;"></span>
                        </div>
                    @endif
                </div>
            </div>

        @endif
    </div>

    {{-- Footer --}}
    <div
        style="display: flex; align-items: center; justify-content: space-between; padding: 14px 20px; border-top: 1px solid #f1f5f9; background: #f8fafc;">
        <span style="font-family: ui-monospace, monospace; font-size: 11px; color: #94a3b8;">
            @if ($isProcessing)
                Processing · do not close
            @elseif($currentIndex > 0 && !$isProcessing)
                Completed {{ now()->format('H:i:s') }}
            @else
                Ready to start
            @endif
        </span>

        <div>
            @if (!$isProcessing && $currentIndex === 0)
                <x-filament::button wire:click="start"
                    style="font-size: 13px; font-weight: 500; padding: 7px 18px; border-radius: 7px; border: none;
                           background: var(--primary-400); color: #fff; cursor: pointer; display: inline-flex; align-items: center; gap: 6px;
                           font-family: inherit;">
                    <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M8 5v14l11-7z" />
                    </svg>
                    Start Process
                </x-filament::button>
            @elseif(!$isProcessing && $currentIndex > 0)
                <x-filament::button x-on:click="$dispatch('close-modal', { id: 'filament-modal' })"
                    style="font-size: 13px; font-weight: 500; padding: 7px 18px; border-radius: 7px;
                           border: 1px solid #e2e8f0; background: #fff; color: #475569; cursor: pointer; font-family: inherit;">
                    Close
                </x-filament::button>
            @else
                <x-filament::button disabled
                    style="font-size: 13px; font-weight: 500; padding: 7px 18px; border-radius: 7px;
                           border: 1px solid #e2e8f0; background: #f8fafc; color: #94a3b8; cursor: not-allowed;
                           display: inline-flex; align-items: center; gap: 6px; font-family: inherit;">
                    <span
                        style="width: 12px; height: 12px; border: 1.5px solid #e2e8f0; border-top-color: var(--primary-400);
                                 border-radius: 50%; animation: isolir-spin 0.7s linear infinite; display: inline-block;"></span>
                    Processing…
                </x-filament::button>
            @endif
        </div>
    </div>

    <style>
        @keyframes isolir-pulse {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: 0.35
            }
        }

        @keyframes isolir-blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: 0
            }
        }

        @keyframes isolir-spin {
            to {
                transform: rotate(360deg)
            }
        }
    </style>
</div>
