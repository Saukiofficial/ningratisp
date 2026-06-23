<x-filament-panels::page>
    <style>
        /* ── WhatsApp Brand Token ── */
        :root {
            --wa-green: #00A884;
            --wa-green-dim: color-mix(in srgb, #00A884 12%, transparent);
            --wa-green-border: color-mix(in srgb, #00A884 25%, transparent);
        }

        /* ── Map to Filament 5 design tokens ── */
        /*
         * Filament 5 exposes CSS custom properties on :root that automatically
         * switch between light/dark via the `dark` class on <html>.
         *
         * Surface hierarchy  →  var(--fi-color-gray-{50,100,200,...,900,950})
         * Text               →  via Tailwind text-{gray-N} pulled from Filament's palette
         * Border             →  var(--fi-color-gray-200) / dark: var(--fi-color-gray-700)
         * Danger             →  var(--fi-color-danger-{400,500,600})
         * Warning            →  var(--fi-color-warning-{400,500,600})
         * Primary            →  var(--fi-color-primary-{400,500,600})
         *
         * We alias them below so the rest of the component stays unchanged.
         */

        :root,
        :root.light,
        html:not(.dark) {
            --surface-0: var(--fi-color-gray-50, #F9FAFB);
            --surface-1: var(--fi-color-gray-100, #F3F4F6);
            --surface-2: var(--fi-color-gray-50, #F9FAFB);
            --surface-3: var(--fi-color-gray-200, #E5E7EB);
            --border: var(--fi-color-gray-200, #E5E7EB);
            --border-subtle: var(--fi-color-gray-100, #F3F4F6);
            --text-primary: var(--fi-color-gray-900, #111827);
            --text-secondary: var(--fi-color-gray-600, #4B5563);
            --text-muted: var(--fi-color-gray-400, #9CA3AF);
            --danger: var(--fi-color-danger-600, #DC2626);
            --danger-dim: color-mix(in srgb, var(--fi-color-danger-500, #EF4444) 10%, transparent);
            --warning: var(--fi-color-warning-600, #D97706);
            --warning-dim: color-mix(in srgb, var(--fi-color-warning-500, #F59E0B) 10%, transparent);
        }

        html.dark {
            --surface-0: var(--fi-color-gray-950, #0F172A);
            --surface-1: var(--fi-color-gray-900, #111827);
            --surface-2: var(--fi-color-gray-800, #1F2937);
            --surface-3: var(--fi-color-gray-700, #374151);
            --border: var(--fi-color-gray-700, #374151);
            --border-subtle: var(--fi-color-gray-800, #1F2937);
            --text-primary: var(--fi-color-gray-50, #F9FAFB);
            --text-secondary: var(--fi-color-gray-400, #9CA3AF);
            --text-muted: var(--fi-color-gray-600, #4B5563);
            --danger: var(--fi-color-danger-400, #F87171);
            --danger-dim: color-mix(in srgb, var(--fi-color-danger-500, #EF4444) 12%, transparent);
            --warning: var(--fi-color-warning-400, #FBBF24);
            --warning-dim: color-mix(in srgb, var(--fi-color-warning-500, #F59E0B) 12%, transparent);
        }

        /* ── Base ── */
        .wa-page {
            font-family: 'Inter', system-ui, sans-serif;
        }

        /* ── Cards ── */
        .wa-card {
            background: var(--surface-1);
            border: 1px solid var(--border);
            border-radius: 16px;
            overflow: hidden;
        }

        .wa-card-inner {
            padding: 24px;
        }

        /* ── Status Pill ── */
        .wa-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
        }

        .wa-status-pill.active {
            background: var(--wa-green-dim);
            color: var(--wa-green);
            border: 1px solid var(--wa-green-border);
        }

        .wa-status-pill.warning {
            background: var(--warning-dim);
            color: var(--warning);
            border: 1px solid color-mix(in srgb, var(--warning) 25%, transparent);
        }

        .wa-status-pill.danger {
            background: var(--danger-dim);
            color: var(--danger);
            border: 1px solid color-mix(in srgb, var(--danger) 25%, transparent);
        }

        .wa-status-pill.muted {
            background: var(--surface-3);
            color: var(--text-secondary);
            border: 1px solid var(--border);
        }

        .pulse-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: currentColor;
            animation: pulse-ring 2s ease-out infinite;
        }

        @keyframes pulse-ring {
            0% {
                box-shadow: 0 0 0 0 currentColor;
                opacity: 1;
            }

            70% {
                box-shadow: 0 0 0 6px transparent;
                opacity: .6;
            }

            100% {
                box-shadow: 0 0 0 0 transparent;
                opacity: 1;
            }
        }

        /* ── Buttons ── */
        .wa-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 18px;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s ease;
            border: 1px solid transparent;
            white-space: nowrap;
        }

        .wa-btn svg {
            width: 15px;
            height: 15px;
            flex-shrink: 0;
        }

        .wa-btn-green {
            background: var(--wa-green);
            color: #fff;
        }

        .wa-btn-green:hover {
            background: #00C59A;
            color: #fff;
        }

        .wa-btn-ghost {
            background: var(--surface-3);
            color: var(--text-primary);
            border-color: var(--border);
        }

        .wa-btn-ghost:hover {
            background: var(--surface-2);
            border-color: var(--border);
            filter: brightness(0.95);
        }

        .wa-btn-danger {
            background: var(--danger-dim);
            color: var(--danger);
            border-color: color-mix(in srgb, var(--danger) 20%, transparent);
        }

        .wa-btn-danger:hover {
            background: color-mix(in srgb, var(--danger) 18%, transparent);
        }

        .wa-btn-warning {
            background: var(--warning-dim);
            color: var(--warning);
            border-color: color-mix(in srgb, var(--warning) 20%, transparent);
        }

        .wa-btn-warning:hover {
            background: color-mix(in srgb, var(--warning) 18%, transparent);
        }

        .wa-btn-full {
            width: 100%;
            justify-content: flex-start;
        }

        /* ── Divider ── */
        .wa-divider {
            border: none;
            border-top: 1px solid var(--border);
            margin: 20px 0;
        }

        /* ── Meta Row ── */
        .wa-meta-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
        }

        .wa-meta-row+.wa-meta-row {
            border-top: 1px solid var(--border-subtle);
        }

        .wa-meta-label {
            font-size: 11px;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: .06em;
        }

        .wa-meta-value {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-primary);
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
        }

        /* ── Avatar ── */
        .wa-avatar {
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #00A884, #00796B);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            position: relative;
            flex-shrink: 0;
        }

        .wa-avatar-dot {
            position: absolute;
            bottom: 2px;
            right: 2px;
            width: 14px;
            height: 14px;
            border-radius: 50%;
            background: var(--wa-green);
            border: 2px solid var(--surface-1);
        }

        /* ── Live View ── */
        .wa-live-topbar {
            background: var(--surface-2);
            border-bottom: 1px solid var(--border);
            padding: 12px 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .wa-traffic-lights {
            display: flex;
            gap: 6px;
        }

        .wa-tl {
            width: 11px;
            height: 11px;
            border-radius: 50%;
        }

        .wa-tl-r {
            background: #FF5F57;
        }

        .wa-tl-y {
            background: #FEBC2E;
        }

        .wa-tl-g {
            background: #28C840;
        }

        .wa-screenshot-area {
            background: var(--surface-0);
            min-height: 360px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }

        .wa-screenshot-area img {
            border-radius: 8px;
            max-height: 420px;
            width: auto;
            box-shadow: 0 8px 40px color-mix(in srgb, #000 35%, transparent);
        }

        /* ── Chat Composer ── */
        .wa-composer {
            background: var(--surface-2);
            border-top: 1px solid var(--border);
            padding: 16px 20px;
        }

        .wa-composer-row {
            display: flex;
            gap: 10px;
            align-items: flex-end;
        }

        .wa-composer-input-wrap {
            flex: 1;
            position: relative;
        }

        .wa-composer-to {
            background: var(--surface-3);
            border: 1px solid var(--border);
            color: var(--text-primary);
            border-radius: 8px 8px 0 0;
            border-bottom: none;
            padding: 8px 14px;
            width: 100%;
            font-size: 12px;
            outline: none;
        }

        .wa-composer-to:focus {
            border-color: var(--wa-green);
        }

        .wa-composer-msg {
            background: var(--surface-3);
            border: 1px solid var(--border);
            color: var(--text-primary);
            border-radius: 0 0 10px 10px;
            padding: 10px 14px;
            width: 100%;
            font-size: 13px;
            resize: none;
            outline: none;
            min-height: 64px;
            font-family: inherit;
        }

        .wa-composer-msg:focus {
            border-color: var(--wa-green);
        }

        .wa-composer-to::placeholder,
        .wa-composer-msg::placeholder {
            color: var(--text-muted);
        }

        .wa-send-btn {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--wa-green);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border: none;
            transition: all .15s;
            flex-shrink: 0;
            align-self: flex-end;
        }

        .wa-send-btn:hover {
            background: #00C59A;
            transform: scale(1.05);
        }

        .wa-send-btn svg {
            width: 20px;
            height: 20px;
        }

        /* ── QR Container ── */
        .wa-qr-wrap {
            background: #fff;
            border-radius: 16px;
            padding: 20px;
            display: inline-flex;
            box-shadow: 0 0 0 1px #E5E7EB, 0 8px 32px color-mix(in srgb, #000 18%, transparent);
        }

        .wa-qr-wrap img {
            width: 220px;
            height: 220px;
            display: block;
        }

        /* ── Step ── */
        .wa-step {
            display: flex;
            gap: 12px;
            align-items: flex-start;
        }

        .wa-step-num {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            flex-shrink: 0;
            background: var(--wa-green-dim);
            border: 1px solid var(--wa-green-border);
            color: var(--wa-green);
            font-size: 11px;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wa-step-text {
            font-size: 13px;
            color: var(--text-secondary);
            padding-top: 3px;
            line-height: 1.5;
        }

        .wa-step-text strong {
            color: var(--text-primary);
            font-weight: 600;
        }

        /* ── Error / Offline ── */
        .wa-error-card {
            background: var(--danger-dim);
            border: 1px solid color-mix(in srgb, var(--danger) 20%, transparent);
            border-radius: 16px;
            padding: 28px;
        }

        .wa-error-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            background: color-mix(in srgb, var(--danger) 12%, transparent);
            color: var(--danger);
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        /* ── Idle / Stopped ── */
        .wa-idle-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            background: var(--surface-3);
            color: var(--text-muted);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* ── Spinner ── */
        .wa-spinner {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 3px solid var(--surface-3);
            border-top-color: var(--wa-green);
            animation: spin .8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .wa-ping {
            position: relative;
            width: 64px;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wa-ping-ring {
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: var(--wa-green-dim);
            animation: ping 1.5s ease-out infinite;
        }

        @keyframes ping {
            0% {
                transform: scale(.8);
                opacity: 1;
            }

            100% {
                transform: scale(1.6);
                opacity: 0;
            }
        }

        /* ── Section Label ── */
        .wa-section-label {
            font-size: 10px;
            font-weight: 700;
            letter-spacing: .1em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 10px;
        }

        /* ── Toast ── */
        .wa-toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: var(--surface-2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 14px 18px;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 13px;
            color: var(--text-primary);
            box-shadow: 0 8px 32px color-mix(in srgb, #000 30%, transparent);
            z-index: 9999;
            opacity: 0;
            transform: translateY(12px);
            transition: all .3s ease;
            pointer-events: none;
        }

        .wa-toast.show {
            opacity: 1;
            transform: none;
        }

        .wa-toast-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: var(--wa-green);
        }

        /* ── Code Snippets inside error messages ── */
        .wa-code {
            background: var(--surface-3);
            color: var(--text-primary);
            padding: 1px 5px;
            border-radius: 4px;
            font-size: 11px;
            font-family: 'JetBrains Mono', 'Fira Code', monospace;
        }

        /* ── Grid helpers ── */
        .wa-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .wa-grid-3 {
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 20px;
        }

        @media (max-width: 1024px) {
            .wa-grid-2 {
                grid-template-columns: 1fr;
            }

            .wa-grid-3 {
                grid-template-columns: 1fr;
            }
        }

        .wa-flex {
            display: flex;
        }

        .wa-gap-3 {
            gap: 12px;
        }

        .wa-gap-2 {
            gap: 8px;
        }

        .wa-items-start {
            align-items: flex-start;
        }

        .wa-items-center {
            align-items: center;
        }

        .wa-col {
            flex-direction: column;
        }

        .wa-space-y-4>*+* {
            margin-top: 16px;
        }

        .wa-space-y-3>*+* {
            margin-top: 12px;
        }

        .wa-center {
            text-align: center;
        }

        .wa-f1 {
            flex: 1;
        }

        /* ── Modal overlay backdrop ── */
        .wa-modal-panel {
            background: var(--surface-1);
            border: 1px solid var(--border);
        }

        .wa-modal-header {
            border-bottom: 1px solid var(--border);
            background: var(--surface-1);
        }

        .wa-modal-footer {
            background: var(--surface-2);
            border-top: 1px solid var(--border);
        }

        /* ── Chat bubbles (modal) ── */
        .wa-bubble-in {
            background: var(--surface-3);
            border: 1px solid var(--border);
        }

        .wa-bubble-out {
            background: linear-gradient(135deg, #005C4B, #00A884);
            border: 1px solid transparent;
        }

        /* ── Composer inline textarea (modal) ── */
        .wa-modal-reply {
            background: var(--surface-3);
            border: 1px solid var(--border);
            color: var(--text-primary);
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 13px;
            resize: none;
            outline: none;
            font-family: inherit;
        }

        .wa-modal-reply::placeholder {
            color: var(--text-muted);
        }

        .wa-modal-reply:focus {
            border-color: var(--wa-green);
        }

        /* ── Close button (modal) ── */
        .wa-close-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--surface-3);
            border: 1px solid var(--border);
            border-radius: 8px;
            cursor: pointer;
            color: var(--text-secondary);
        }

        .wa-close-btn:hover {
            background: var(--surface-2);
        }

        /* ── Error checklist panel ── */
        .wa-checklist-panel {
            background: var(--surface-2);
            border: 1px solid color-mix(in srgb, var(--danger) 12%, transparent);
            border-radius: 10px;
            padding: 14px 16px;
            margin-bottom: 18px;
        }

        /* ── Status badge in STARTING state ── */
        .wa-status-badge {
            background: var(--surface-2);
            border-radius: 8px;
            padding: 10px 16px;
            margin-bottom: 24px;
            display: inline-flex;
            gap: 8px;
            align-items: center;
        }
    </style>

    <div class="wa-page">

        {{-- ══════════════════════════════════════════
             STATE: OFFLINE / ERROR
        ══════════════════════════════════════════ --}}
        @if ($status === 'OFFLINE' || $status === 'ERROR')
            <div class="wa-error-card">
                <div class="wa-flex wa-gap-3 wa-items-start">
                    <div class="wa-error-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" style="width:22px;height:22px">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div class="wa-f1">
                        <div class="wa-flex wa-items-center wa-gap-3" style="margin-bottom:8px">
                            <span style="font-size:15px;font-weight:700;color:var(--danger)">WAHA Connection
                                Failed</span>
                            <span class="wa-status-pill danger"><span
                                    class="pulse-dot"></span>{{ $status }}</span>
                        </div>
                        <p style="font-size:13px;color:var(--text-secondary);line-height:1.6;margin-bottom:16px">
                            {{ $errorMessage ?? 'Could not reach the WAHA (WhatsApp HTTP API) service. Verify your container is running and the credentials in your .env are correct.' }}
                        </p>
                        <div class="wa-checklist-panel">
                            <p class="wa-section-label" style="color:var(--danger);margin-bottom:8px">Checklist</p>
                            <div class="wa-space-y-3">
                                <div class="wa-flex wa-gap-3 wa-items-start">
                                    <svg style="width:14px;height:14px;color:var(--text-muted);flex-shrink:0;margin-top:2px"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                    <span style="font-size:12px;color:var(--text-secondary)">WAHA container / service is
                                        running and reachable from this host.</span>
                                </div>
                                <div class="wa-flex wa-gap-3 wa-items-start">
                                    <svg style="width:14px;height:14px;color:var(--text-muted);flex-shrink:0;margin-top:2px"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                    <span style="font-size:12px;color:var(--text-secondary)"><code
                                            class="wa-code">WAHA_URL</code> in <code class="wa-code">.env</code> matches
                                        the running service endpoint.</span>
                                </div>
                                <div class="wa-flex wa-gap-3 wa-items-start">
                                    <svg style="width:14px;height:14px;color:var(--text-muted);flex-shrink:0;margin-top:2px"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                                    </svg>
                                    <span style="font-size:12px;color:var(--text-secondary)"><code
                                            class="wa-code">WAHA_API_KEY</code> matches the key configured in the WAHA
                                        container.</span>
                                </div>
                            </div>
                        </div>
                        <button class="wa-btn wa-btn-danger" wire:click="refreshStatus">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Retry Connection
                        </button>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════
             STATE: STOPPED
        ══════════════════════════════════════════ --}}
        @elseif ($status === 'STOPPED')
            <div class="wa-card" style="max-width:560px;margin:0 auto">
                <div class="wa-card-inner" style="padding:40px;text-align:center">
                    <div class="wa-idle-icon" style="margin:0 auto 20px">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" style="width:26px;height:26px">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                        </svg>
                    </div>
                    <h3 style="font-size:18px;font-weight:700;color:var(--text-primary);margin-bottom:8px">WhatsApp
                        Session Stopped</h3>
                    <p
                        style="font-size:13px;color:var(--text-secondary);line-height:1.6;max-width:380px;margin:0 auto 28px">
                        The WhatsApp engine is not running. Start a session to generate a QR code and link your phone.
                    </p>
                    <span class="wa-status-pill muted" style="margin-bottom:28px;display:inline-flex">
                        <span class="pulse-dot" style="background:var(--text-muted)"></span>
                        STOPPED
                    </span>
                    <div style="display:flex;gap:10px;justify-content:center;margin-top:20px">
                        <button class="wa-btn wa-btn-green" wire:click="startSession">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M8 5.14v14l11-7-11-7Z" />
                            </svg>
                            Start WhatsApp
                        </button>
                        <button class="wa-btn wa-btn-ghost" wire:click="refreshStatus">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Refresh
                        </button>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════
             STATE: STARTING
        ══════════════════════════════════════════ --}}
        @elseif ($status === 'STARTING')
            <div class="wa-card" style="max-width:480px;margin:0 auto">
                <div class="wa-card-inner" style="padding:48px;text-align:center">
                    <div style="display:flex;justify-content:center;margin-bottom:24px">
                        <div class="wa-ping">
                            <div class="wa-ping-ring"></div>
                            <div class="wa-spinner"></div>
                        </div>
                    </div>
                    <h3 style="font-size:18px;font-weight:700;color:var(--text-primary);margin-bottom:8px">Starting
                        WhatsApp Engine</h3>
                    <p
                        style="font-size:13px;color:var(--text-secondary);line-height:1.6;max-width:320px;margin:0 auto 28px">
                        The WAHA engine is launching a browser instance. This typically takes 10–30 seconds.
                    </p>
                    <div class="wa-status-badge">
                        <span
                            style="font-size:11px;color:var(--wa-green);font-family:monospace;font-weight:600">STATUS</span>
                        <span style="font-size:11px;color:var(--text-muted)">›</span>
                        <span style="font-size:11px;color:var(--text-secondary);font-family:monospace">STARTING…</span>
                    </div>
                    <div style="display:flex;gap:10px;justify-content:center">
                        <button class="wa-btn wa-btn-green" wire:click="refreshStatus">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                            </svg>
                            Check Status
                        </button>
                        <button class="wa-btn wa-btn-danger" wire:click="stopSession">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="2" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5.25 7.5A2.25 2.25 0 0 1 7.5 5.25h9a2.25 2.25 0 0 1 2.25 2.25v9a2.25 2.25 0 0 1-2.25 2.25h-9a2.25 2.25 0 0 1-2.25-2.25v-9Z" />
                            </svg>
                            Abort
                        </button>
                    </div>
                </div>
            </div>

            {{-- ══════════════════════════════════════════
             STATE: SCAN_QR_CODE
        ══════════════════════════════════════════ --}}
        @elseif ($status === 'SCAN_QR_CODE')
            <div class="wa-grid-2">
                {{-- Instructions --}}
                <div class="wa-card">
                    <div class="wa-card-inner" style="display:flex;flex-direction:column;height:100%">
                        <div style="margin-bottom:20px">
                            <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px">
                                <span class="wa-status-pill warning"><span class="pulse-dot"></span>Awaiting
                                    Scan</span>
                            </div>
                            <h3 style="font-size:20px;font-weight:700;color:var(--text-primary);margin:0 0 8px">Link
                                Your Phone</h3>
                            <p style="font-size:13px;color:var(--text-secondary);line-height:1.6">
                                Scan the QR code with your WhatsApp mobile app to link this session.
                            </p>
                        </div>

                        <hr class="wa-divider">

                        <div class="wa-space-y-4" style="flex:1">
                            <div class="wa-step">
                                <div class="wa-step-num">1</div>
                                <p class="wa-step-text">Open <strong>WhatsApp</strong> on your phone.</p>
                            </div>
                            <div class="wa-step">
                                <div class="wa-step-num">2</div>
                                <p class="wa-step-text">Tap <strong>Menu ⋮</strong> (Android) or <strong>Settings
                                        ⚙</strong> (iPhone).</p>
                            </div>
                            <div class="wa-step">
                                <div class="wa-step-num">3</div>
                                <p class="wa-step-text">Go to <strong>Linked Devices</strong> → <strong>Link a
                                        Device</strong>.</p>
                            </div>
                            <div class="wa-step">
                                <div class="wa-step-num">4</div>
                                <p class="wa-step-text">Point your camera at the QR code on the right.</p>
                            </div>
                        </div>

                        <hr class="wa-divider" style="margin-bottom:16px">
                        <div style="display:flex;gap:8px">
                            <button class="wa-btn wa-btn-ghost" wire:click="refreshStatus">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Refresh QR
                            </button>
                            <button class="wa-btn wa-btn-danger" wire:click="stopSession"
                                wire:confirm="Stop the session and cancel pairing?">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M5.25 7.5A2.25 2.25 0 0 1 7.5 5.25h9a2.25 2.25 0 0 1 2.25 2.25v9a2.25 2.25 0 0 1-2.25 2.25h-9a2.25 2.25 0 0 1-2.25-2.25v-9Z" />
                                </svg>
                                Stop
                            </button>
                        </div>
                    </div>
                </div>

                {{-- QR Display --}}
                <div class="wa-card" style="display:flex;align-items:center;justify-content:center;min-height:320px">
                    @if ($qrCode)
                        <div style="text-align:center">
                            <div class="wa-qr-wrap" style="margin-bottom:14px">
                                <img src="data:image/png;base64,{{ $qrCode }}" alt="WhatsApp QR Code">
                            </div>
                            <p style="font-size:11px;color:var(--text-muted)">QR code expires in ~60 seconds — refresh
                                if it doesn't scan.</p>
                        </div>
                    @else
                        <div style="text-align:center">
                            <div class="wa-spinner" style="margin:0 auto 16px"></div>
                            <p style="font-size:12px;color:var(--text-muted)">Fetching QR code…</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- ══════════════════════════════════════════
             STATE: WORKING (Connected)
        ══════════════════════════════════════════ --}}
        @elseif ($status === 'WORKING')
            <div class="wa-grid-3">

                {{-- ── LEFT SIDEBAR ── --}}
                <div style="display:flex;flex-direction:column;gap:16px">

                    {{-- Account Card --}}
                    <div class="wa-card">
                        <div class="wa-card-inner" style="text-align:center">
                            <div style="position:relative;display:inline-block;margin-bottom:16px">
                                <div class="wa-avatar">
                                    @if (!empty($me['pushName']))
                                        {{ strtoupper(substr($me['pushName'], 0, 2)) }}
                                    @else
                                        WA
                                    @endif
                                </div>
                                <div class="wa-avatar-dot"></div>
                            </div>
                            <div style="margin-bottom:4px;font-size:16px;font-weight:700;color:var(--text-primary)">
                                {{ $me['pushName'] ?? 'WhatsApp' }}
                            </div>
                            <div
                                style="font-size:12px;font-family:monospace;color:var(--text-secondary);margin-bottom:16px">
                                {{ is_array($me) && isset($me['id']) ? '+' . $me['id'] : '—' }}
                            </div>
                            <span class="wa-status-pill active"><span class="pulse-dot"></span>Connected</span>
                        </div>
                        <hr class="wa-divider" style="margin:0 24px">
                        <div class="wa-card-inner" style="padding-top:16px">
                            <div class="wa-meta-row">
                                <span class="wa-meta-label">Session</span>
                                <span class="wa-meta-value">default</span>
                            </div>
                            <div class="wa-meta-row">
                                <span class="wa-meta-label">Platform</span>
                                <span class="wa-meta-value">{{ $me['platform'] ?? 'web' }}</span>
                            </div>
                            <div class="wa-meta-row">
                                <span class="wa-meta-label">Engine</span>
                                <span class="wa-meta-value">WAHA</span>
                            </div>
                        </div>
                    </div>

                    {{-- Controls --}}
                    <div class="wa-card">
                        <div class="wa-card-inner">
                            <p class="wa-section-label">Session Controls</p>
                            <div style="display:flex;flex-direction:column;gap:8px">
                                <button class="wa-btn wa-btn-ghost wa-btn-full" wire:click="refreshStatus">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    Refresh
                                </button>
                                <button class="wa-btn wa-btn-warning wa-btn-full" wire:click="restartSession"
                                    wire:confirm="Restart the WhatsApp session? Messages will briefly disconnect.">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    Restart Session
                                </button>
                                <button class="wa-btn wa-btn-danger wa-btn-full" wire:click="stopSession"
                                    wire:confirm="Stop the WhatsApp session? It won't restart automatically.">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M5.25 7.5A2.25 2.25 0 0 1 7.5 5.25h9a2.25 2.25 0 0 1 2.25 2.25v9a2.25 2.25 0 0 1-2.25 2.25h-9a2.25 2.25 0 0 1-2.25-2.25v-9Z" />
                                    </svg>
                                    Stop Session
                                </button>
                                <button class="wa-btn wa-btn-danger wa-btn-full" wire:click="disconnect"
                                    wire:confirm="Log out of WhatsApp? You'll need to scan the QR code again.">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.25 9V5.25A2.25 2.25 0 0 1 10.5 3h6a2.25 2.25 0 0 1 2.25 2.25v13.5A2.25 2.25 0 0 1 16.5 21h-6a2.25 2.25 0 0 1-2.25-2.25V15m-3 0-3-3m0 0 3-3m-3 3H15" />
                                    </svg>
                                    Logout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── RIGHT PANEL ── --}}
                <div style="display:flex;flex-direction:column;gap:16px">

                    {{-- Live View --}}
                    <div class="wa-card" style="overflow:hidden">
                        <div class="wa-live-topbar">
                            <div style="display:flex;align-items:center;gap:12px">
                                <div class="wa-traffic-lights">
                                    <div class="wa-tl wa-tl-r"></div>
                                    <div class="wa-tl wa-tl-y"></div>
                                    <div class="wa-tl wa-tl-g"></div>
                                </div>
                                <span
                                    style="font-size:12px;font-weight:600;color:var(--text-muted);font-family:monospace">whatsapp-web
                                    · live view</span>
                            </div>
                            <button class="wa-btn wa-btn-ghost" style="padding:6px 12px;font-size:12px"
                                wire:click="refreshStatus">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" style="width:13px;height:13px">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                </svg>
                                Refresh
                            </button>
                        </div>
                        <div class="wa-screenshot-area">
                            @if ($screenshot)
                                <img src="data:image/png;base64,{{ $screenshot }}"
                                    alt="WhatsApp Web Live Screenshot">
                            @else
                                <div style="text-align:center">
                                    <svg style="width:40px;height:40px;color:var(--text-muted);margin:0 auto 12px;display:block"
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                                    </svg>
                                    <p style="font-size:13px;font-weight:600;color:var(--text-secondary)">No screenshot
                                        available</p>
                                    <p style="font-size:11px;color:var(--text-muted);margin-top:4px">Refresh to capture
                                        the live view.</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- ── Chat Composer ── --}}
                    <div class="wa-card" style="overflow:visible">

                        {{-- Card header --}}
                        <div
                            style="padding:14px 20px;border-bottom:1px solid var(--border);display:flex;align-items:center;justify-content:space-between">
                            <div style="display:flex;align-items:center;gap:8px">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    style="width:16px;height:16px;color:var(--wa-green)">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                </svg>
                                <span style="font-size:13px;font-weight:600;color:var(--text-primary)">Send
                                    Message</span>
                            </div>
                            <div style="display:flex;align-items:center;gap:8px">
                                <button class="wa-btn wa-btn-ghost" style="padding:5px 10px;font-size:11px;gap:5px"
                                    wire:click="refreshContacts" title="Force-refresh contact list from API">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor" style="width:12px;height:12px">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                                    </svg>
                                    Sync contacts
                                </button>
                                <span
                                    style="font-size:10px;color:var(--text-muted);font-family:monospace;background:var(--surface-3);padding:3px 8px;border-radius:6px">via
                                    WAHA API</span>
                            </div>
                        </div>

                        <div class="wa-composer">

                            {{-- ── Contact Picker ── --}}
                            <div style="margin-bottom:10px;position:relative" x-data="{ open: false }"
                                @click.outside="open = false">

                                @if ($selectedContact)
                                    <div
                                        style="display:flex;align-items:center;gap:10px;background:var(--surface-3);border:1px solid var(--wa-green-border);border-radius:10px;padding:9px 12px">
                                        <div
                                            style="width:32px;height:32px;border-radius:50%;background:linear-gradient(135deg,#00A884,#00796B);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0">
                                            {{ $this->makeInitials($selectedName) }}
                                        </div>
                                        <div style="flex:1;min-width:0">
                                            <div
                                                style="font-size:13px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                                {{ $selectedName }}</div>
                                            <div style="font-size:11px;color:var(--text-muted);font-family:monospace">
                                                +{{ $sendTo }}</div>
                                        </div>
                                        <div style="display:flex;gap:6px;flex-shrink:0">
                                            <button class="wa-btn wa-btn-ghost"
                                                style="padding:5px 10px;font-size:11px;gap:5px"
                                                wire:click="openChatHistory" title="View chat history">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    style="width:12px;height:12px">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M2.25 12.76c0 1.6 1.113 2.994 2.707 3.227 1.052.157 2.112.279 3.182.365M15.75 18.5l-3-3m0 0-3 3m3-3v7.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                                </svg>
                                                History
                                            </button>
                                            <button class="wa-btn wa-btn-ghost"
                                                style="padding:5px 10px;font-size:11px" wire:click="clearContact"
                                                title="Change contact">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                    style="width:12px;height:12px">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18 18 6M6 6l12 12" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                @else
                                    <div style="position:relative">
                                        <div
                                            style="position:absolute;left:12px;top:50%;transform:translateY(-50%);pointer-events:none">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                style="width:14px;height:14px;color:var(--text-muted)">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z" />
                                            </svg>
                                        </div>
                                        <input type="text" wire:model.live.debounce.200ms="contactSearch"
                                            class="wa-composer-to" style="border-radius:10px;padding-left:34px"
                                            placeholder="{{ count($contacts) > 0 ? 'Search ' . count($contacts) . ' contacts…' : 'Loading contacts…' }}"
                                            @focus="open = true" autocomplete="off">
                                    </div>

                                    <div x-show="open" x-cloak
                                        style="position:absolute;top:calc(100% + 6px);left:0;right:0;z-index:50;background:var(--surface-2);border:1px solid var(--border);border-radius:12px;box-shadow:0 8px 32px color-mix(in srgb, #000 35%, transparent);max-height:260px;overflow-y:auto">

                                        @php $filtered = $this->getFilteredContacts(); @endphp

                                        @if (count($filtered) === 0)
                                            <div
                                                style="padding:20px;text-align:center;font-size:12px;color:var(--text-muted)">
                                                {{ count($contacts) === 0 ? 'No contacts loaded yet — try "Sync contacts".' : 'No contacts match your search.' }}
                                            </div>
                                        @else
                                            @foreach ($filtered as $contact)
                                                <button type="button"
                                                    wire:click="selectContact('{{ $contact['id'] }}')"
                                                    @click="open = false"
                                                    style="display:flex;align-items:center;gap:10px;width:100%;padding:10px 14px;background:none;border:none;cursor:pointer;text-align:left;transition:background .1s"
                                                    onmouseover="this.style.background='var(--surface-3)'"
                                                    onmouseout="this.style.background='none'">
                                                    <div
                                                        style="width:34px;height:34px;border-radius:50%;background:linear-gradient(135deg,#00A884,#00796B);display:flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;color:#fff;flex-shrink:0">
                                                        {{ $contact['initials'] }}
                                                    </div>
                                                    <div style="flex:1;min-width:0">
                                                        <div
                                                            style="font-size:13px;font-weight:600;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                                            {{ $contact['name'] }}
                                                        </div>
                                                        <div
                                                            style="font-size:11px;color:var(--text-muted);font-family:monospace">
                                                            +{{ $contact['number'] }}
                                                        </div>
                                                    </div>
                                                </button>
                                            @endforeach
                                            @if (count($filtered) === 50 && $contactSearch === '')
                                                <div
                                                    style="padding:8px 14px;font-size:11px;color:var(--text-muted);border-top:1px solid var(--border)">
                                                    Showing first 50 — type to search all {{ count($contacts) }}
                                                </div>
                                            @endif
                                        @endif
                                    </div>
                                @endif

                            </div>

                            @if (!$selectedContact)
                                <div style="display:flex;align-items:center;gap:8px;margin-bottom:10px">
                                    <div style="flex:1;height:1px;background:var(--border)"></div>
                                    <span style="font-size:10px;color:var(--text-muted);white-space:nowrap">or enter
                                        number manually</span>
                                    <div style="flex:1;height:1px;background:var(--border)"></div>
                                </div>
                                <input type="text" wire:model.live="sendTo" class="wa-composer-to"
                                    style="border-radius:10px;margin-bottom:10px" placeholder="628123456789">
                            @endif

                            <div class="wa-composer-row">
                                <textarea wire:model="sendMessage" class="wa-composer-msg" style="border-radius:10px"
                                    placeholder="Type your message…" rows="3"></textarea>
                                <button class="wa-send-btn" wire:click="sendChat" title="Send">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                    </svg>
                                </button>
                            </div>

                        </div>
                    </div>

                </div>
            </div>

            {{-- ══════════════════════════════════════════
             CHAT HISTORY MODAL
        ══════════════════════════════════════════ --}}
            @if ($showChatModal)
                <div
                    style="position:fixed;inset:0;z-index:9000;display:flex;align-items:flex-end;justify-content:flex-end;padding:20px;pointer-events:none">
                    <div style="position:fixed;inset:0;background:color-mix(in srgb,#000 45%,transparent);pointer-events:all;backdrop-filter:blur(2px)"
                        wire:click="closeChatModal"></div>

                    <div class="wa-modal-panel"
                        style="position:relative;pointer-events:all;width:420px;max-width:100%;height:72vh;display:flex;flex-direction:column;border-radius:20px;box-shadow:0 24px 64px color-mix(in srgb,#000 50%,transparent);overflow:hidden">

                        <div class="wa-modal-header"
                            style="padding:16px 18px;display:flex;align-items:center;gap:12px;flex-shrink:0">
                            <div
                                style="width:38px;height:38px;border-radius:50%;background:linear-gradient(135deg,#00A884,#00796B);display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0">
                                {{ $this->makeInitials($modalContactName) }}
                            </div>
                            <div style="flex:1;min-width:0">
                                <div
                                    style="font-size:14px;font-weight:700;color:var(--text-primary);white-space:nowrap;overflow:hidden;text-overflow:ellipsis">
                                    {{ $modalContactName }}
                                </div>
                                <div style="font-size:11px;color:var(--text-muted)">Last 40 messages</div>
                            </div>
                            <button wire:click="closeChatModal" class="wa-close-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor" style="width:14px;height:14px">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <div style="flex:1;overflow-y:auto;padding:16px;display:flex;flex-direction:column;gap:8px;background:var(--surface-0)"
                            id="wa-chat-scroll">

                            @if ($chatLoading)
                                <div
                                    style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;gap:12px">
                                    <div class="wa-spinner"></div>
                                    <p style="font-size:12px;color:var(--text-muted)">Loading messages…</p>
                                </div>
                            @elseif (count($chatMessages) === 0)
                                <div
                                    style="display:flex;flex-direction:column;align-items:center;justify-content:center;height:100%;gap:8px;text-align:center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor"
                                        style="width:36px;height:36px;color:var(--text-muted)">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8.625 12a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 0 1-2.555-.337A5.972 5.972 0 0 1 5.41 20.97a5.969 5.969 0 0 1-.474-.065 4.48 4.48 0 0 0 .978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25Z" />
                                    </svg>
                                    <p style="font-size:13px;color:var(--text-secondary);font-weight:600">No messages
                                        yet</p>
                                    <p style="font-size:11px;color:var(--text-muted)">Start a conversation by sending a
                                        message below.</p>
                                </div>
                            @else
                                @foreach ($chatMessages as $msg)
                                    <div
                                        style="display:flex;flex-direction:column;align-items:{{ $msg['fromMe'] ? 'flex-end' : 'flex-start' }}">
                                        <div class="{{ $msg['fromMe'] ? 'wa-bubble-out' : 'wa-bubble-in' }}"
                                            style="
                            max-width:82%;
                            padding:8px 12px;
                            border-radius:{{ $msg['fromMe'] ? '14px 14px 4px 14px' : '14px 14px 14px 4px' }};
                        ">
                                            <p
                                                style="font-size:13px;line-height:1.5;color:{{ $msg['fromMe'] ? '#E8EAF0' : 'var(--text-primary)' }};margin:0;word-break:break-word">
                                                {{ $msg['body'] }}
                                            </p>
                                            @if ($msg['timestamp'])
                                                <p
                                                    style="font-size:10px;color:{{ $msg['fromMe'] ? 'rgba(232,234,240,.5)' : 'var(--text-muted)' }};margin:4px 0 0;text-align:right">
                                                    {{ $msg['timestamp'] }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                        </div>

                        <div class="wa-modal-footer" style="padding:12px 14px;display:flex;gap:8px;flex-shrink:0">
                            <textarea wire:model="sendMessage" class="wa-modal-reply" placeholder="Quick reply…" rows="2" style="flex:1"></textarea>
                            <button class="wa-send-btn" wire:click="sendChat" style="align-self:flex-end"
                                title="Send">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5" />
                                </svg>
                            </button>
                        </div>

                    </div>
                </div>

                <script>
                    document.addEventListener('livewire:updated', () => {
                        const el = document.getElementById('wa-chat-scroll');
                        if (el) el.scrollTop = el.scrollHeight;
                    });
                </script>
            @endif

            {{-- ══════════════════════════════════════════
             FALLBACK
        ══════════════════════════════════════════ --}}
        @else
            <div class="wa-card" style="max-width:400px;margin:0 auto">
                <div class="wa-card-inner" style="text-align:center;padding:40px">
                    <div class="wa-idle-icon" style="margin:0 auto 16px">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" style="width:24px;height:24px">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <h3 style="font-size:15px;font-weight:700;color:var(--text-primary);margin-bottom:8px">Unknown
                        Status</h3>
                    <p style="font-size:12px;color:var(--text-secondary);margin-bottom:20px">
                        Received status: <code class="wa-code">{{ $status ?? 'NULL' }}</code>
                    </p>
                    <button class="wa-btn wa-btn-ghost" wire:click="refreshStatus">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99" />
                        </svg>
                        Refresh
                    </button>
                </div>
            </div>
        @endif

    </div>

    {{-- Toast notification --}}
    <div id="wa-toast" class="wa-toast">
        <div class="wa-toast-dot"></div>
        <span id="wa-toast-text"></span>
    </div>
</x-filament-panels::page>
