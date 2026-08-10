@extends('base-landing', ['title' => 'Ningrat ISP | Beli Voucher Hotspot'])

@push('head')
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Sora:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        /* ── Theme Definitions ── */
        :root {
            --hs-primary: #ff8c00;
            --hs-primary-hover: #ff6a00;
            --hs-bg: #f8fafc;
            --hs-card-bg: #ffffff;
            --hs-card-header-bg: #ffffff;
            --hs-card-border: rgba(0, 0, 0, 0.08);
            --hs-text-main: #0f172a;
            --hs-text-sub: #64748b;
            --hs-item-bg: #f1f5f9;
            --hs-item-border: rgba(0, 0, 0, 0.06);
            --hs-shadow: 0 15px 35px rgba(0, 0, 0, 0.06);
            --hs-accordion-filter: none;
            --hs-close-filter: none;
        }

        html.dark, body.dark {
            --hs-primary: #ff8c00;
            --hs-primary-hover: #ff6a00;
            --hs-bg: #0f172a;
            --hs-card-bg: #1e293b;
            --hs-card-header-bg: rgba(15, 23, 42, 0.6);
            --hs-card-border: rgba(255, 255, 255, 0.12);
            --hs-text-main: #f8fafc;
            --hs-text-sub: #94a3b8;
            --hs-item-bg: rgba(15, 23, 42, 0.4);
            --hs-item-border: rgba(255, 255, 255, 0.06);
            --hs-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            --hs-accordion-filter: invert(1);
            --hs-close-filter: invert(1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--hs-bg);
            color: var(--hs-text-main);
            min-height: 100vh;
            margin: 0;
            transition: background-color 0.3s, color 0.3s;
        }

        .hs-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 24px 16px 40px;
            box-sizing: border-box;
        }

        .hs-container {
            width: 100%;
            max-width: 520px;
        }

        .hs-main-card {
            background: var(--hs-card-bg);
            border: 1px solid var(--hs-card-border);
            border-radius: 24px;
            overflow: hidden;
            box-shadow: var(--hs-shadow);
            position: relative;
            transition: background-color 0.3s, border-color 0.3s;
        }

        .hs-main-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, transparent, var(--hs-primary), transparent);
        }

        .hs-header {
            padding: 28px 24px 20px;
            text-align: center;
            background: var(--hs-card-header-bg);
            border-bottom: 1px solid var(--hs-card-border);
            position: relative;
        }

        .hs-theme-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background: var(--hs-item-bg);
            border: 1px solid var(--hs-card-border);
            color: var(--hs-text-main);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            padding: 0;
        }

        .hs-theme-btn:hover {
            color: var(--hs-primary);
            transform: scale(1.05);
        }

        .hs-brand-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 14px;
            border-radius: 50px;
            background: rgba(255, 140, 0, 0.12);
            border: 1px solid rgba(255, 140, 0, 0.3);
            color: var(--hs-primary);
            font-size: 0.72rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 12px;
        }

        .hs-title {
            font-family: 'Sora', sans-serif;
            font-size: 1.45rem;
            font-weight: 800;
            margin: 0 0 6px;
            color: var(--hs-text-main);
            letter-spacing: -0.02em;
        }

        .hs-subtitle {
            font-size: 0.85rem;
            color: var(--hs-text-sub);
            margin: 0;
        }

        .hs-body {
            padding: 24px;
        }

        .hs-section-title {
            font-family: 'Sora', sans-serif;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--hs-primary);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Payment Accordion */
        .hs-accordion .accordion-item {
            background: var(--hs-item-bg);
            border: 1px solid var(--hs-card-border);
            border-radius: 16px !important;
            margin-bottom: 12px;
            overflow: hidden;
            transition: all 0.2s;
        }

        .hs-accordion .accordion-item:hover {
            border-color: rgba(255, 140, 0, 0.4);
        }

        .hs-accordion .accordion-button {
            background: transparent;
            color: var(--hs-text-main);
            font-weight: 700;
            font-size: 0.88rem;
            padding: 16px 18px;
            box-shadow: none !important;
        }

        .hs-accordion .accordion-button:not(.collapsed) {
            background: rgba(255, 140, 0, 0.08);
            color: var(--hs-primary);
        }

        .hs-accordion .accordion-button::after {
            filter: var(--hs-accordion-filter);
        }

        .hs-accordion .accordion-body {
            padding: 8px 12px 14px;
        }

        .hs-channel-card {
            display: flex;
            flex-direction: column;
            padding: 14px 16px;
            border-radius: 12px;
            background: var(--hs-card-bg);
            border: 1px solid var(--hs-card-border);
            margin-top: 8px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: var(--hs-text-main);
        }

        .hs-channel-card:hover {
            background: rgba(255, 140, 0, 0.08);
            border-color: rgba(255, 140, 0, 0.4);
            transform: translateY(-1px);
        }

        .hs-channel-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .hs-channel-name {
            font-weight: 700;
            font-size: 0.88rem;
            color: var(--hs-text-main);
        }

        .hs-channel-logo {
            width: 32px;
            height: 32px;
            object-fit: contain;
            background: #ffffff;
            border-radius: 6px;
            padding: 3px;
            border: 1px solid rgba(0, 0, 0, 0.08);
        }

        .hs-channel-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px dashed var(--hs-card-border);
            font-size: 0.78rem;
            color: var(--hs-text-sub);
        }

        .hs-channel-price {
            font-family: 'Sora', sans-serif;
            font-weight: 800;
            color: var(--hs-primary);
            font-size: 0.92rem;
        }

        /* Summary / QRIS View */
        .hs-status-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 16px;
            border-radius: 50px;
            font-size: 0.82rem;
            font-weight: 700;
            margin-bottom: 20px;
            width: 100%;
            justify-content: center;
            text-align: center;
        }

        .hs-status-pill.pending {
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #d97706;
        }

        html.dark .hs-status-pill.pending {
            color: #fbbf24;
        }

        .hs-status-pill.success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #059669;
        }

        html.dark .hs-status-pill.success {
            color: #34d399;
        }

        .hs-qris-container {
            background: #ffffff;
            border-radius: 20px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
            margin: 0 auto 20px;
            max-width: 280px;
            border: 1px solid var(--hs-card-border);
        }

        .hs-qris-img {
            max-width: 240px;
            width: 100%;
            height: auto;
            border-radius: 12px;
        }

        .hs-instruction-box {
            background: var(--hs-item-bg);
            border: 1px solid var(--hs-card-border);
            border-radius: 16px;
            padding: 16px;
            margin-bottom: 20px;
        }

        .hs-instruction-title {
            font-weight: 700;
            color: var(--hs-primary);
            font-size: 0.85rem;
            margin-bottom: 8px;
        }

        .hs-instruction-list {
            padding-left: 20px;
            margin-bottom: 0;
            font-size: 0.8rem;
            color: var(--hs-text-sub);
            line-height: 1.6;
        }

        .hs-timer-box {
            background: var(--hs-item-bg);
            border: 1px dashed rgba(255, 140, 0, 0.4);
            border-radius: 14px;
            padding: 10px 16px;
            transition: all 0.3s;
        }

        .hs-voucher-card {
            background: rgba(16, 185, 129, 0.1);
            border: 2px dashed rgba(16, 185, 129, 0.4);
            border-radius: 20px;
            padding: 24px 20px;
            text-align: center;
            margin-bottom: 24px;
        }

        .hs-voucher-code {
            font-family: 'Courier New', monospace;
            font-size: 1.75rem;
            font-weight: 800;
            color: #059669;
            letter-spacing: 0.12em;
            margin: 12px 0 16px;
            background: var(--hs-card-bg);
            padding: 12px 16px;
            border-radius: 12px;
            border: 1px solid rgba(16, 185, 129, 0.3);
            word-break: break-all;
        }

        html.dark .hs-voucher-code {
            color: #34d399;
        }

        .hs-btn-primary {
            background: linear-gradient(135deg, var(--hs-primary), var(--hs-primary-hover));
            color: #ffffff !important;
            border: none;
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.88rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            box-shadow: 0 4px 16px rgba(255, 140, 0, 0.3);
            transition: all 0.2s;
            width: 100%;
            text-decoration: none;
        }

        .hs-btn-primary:hover {
            box-shadow: 0 6px 22px rgba(255, 140, 0, 0.45);
            transform: translateY(-1px);
        }

        .hs-btn-outline {
            background: transparent;
            border: 1px solid var(--hs-card-border);
            color: var(--hs-text-main);
            padding: 12px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.88rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
        }

        .hs-btn-outline:hover {
            background: var(--hs-item-bg);
            border-color: rgba(255, 140, 0, 0.4);
            color: var(--hs-primary);
        }

        .hs-btn-danger {
            background: transparent;
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
            padding: 11px 20px;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            cursor: pointer;
            transition: all 0.2s;
            width: 100%;
        }

        .hs-btn-danger:hover {
            background: rgba(239, 68, 68, 0.08);
            border-color: #ef4444;
            color: #dc2626;
        }

        /* Order Info Grid */
        .hs-info-grid {
            background: var(--hs-item-bg);
            border: 1px solid var(--hs-card-border);
            border-radius: 16px;
            padding: 16px;
            margin-top: 20px;
        }

        .hs-info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid var(--hs-card-border);
            font-size: 0.82rem;
        }

        .hs-info-row:last-child {
            border-bottom: none;
        }

        .hs-info-label {
            color: var(--hs-text-sub);
        }

        .hs-info-val {
            font-weight: 700;
            color: var(--hs-text-main);
        }

        /* Modal styling */
        .hs-modal .modal-content {
            background: var(--hs-card-bg);
            border: 1px solid var(--hs-card-border);
            border-radius: 20px;
            color: var(--hs-text-main);
            box-shadow: 0 25px 60px rgba(0, 0, 0, 0.25);
        }

        .hs-modal .modal-header {
            background: var(--hs-card-header-bg);
            border-bottom: 1px solid var(--hs-card-border);
            padding: 20px 24px;
        }

        .hs-modal .modal-title {
            font-family: 'Sora', sans-serif;
            font-size: 1.15rem;
            font-weight: 800;
            color: var(--hs-text-main);
        }

        .hs-modal .btn-close {
            filter: var(--hs-close-filter);
        }

        .hs-modal-details {
            background: var(--hs-item-bg);
            border: 1px solid rgba(255, 140, 0, 0.3);
            border-radius: 14px;
            padding: 14px 16px;
            margin-bottom: 20px;
        }

        .hs-modal .hs-label-sub,
        .hs-modal .text-muted,
        .hs-modal .form-text {
            color: var(--hs-text-sub) !important;
        }

        .hs-modal .whatsapp-prefix {
            color: var(--hs-text-sub);
        }

        .whatsapp-input-group {
            position: relative;
        }

        .whatsapp-input-group .form-control {
            background: var(--hs-item-bg);
            border: 1px solid var(--hs-card-border);
            color: var(--hs-text-main);
            padding-left: 56px;
            border-radius: 12px;
            padding-top: 10px;
            padding-bottom: 10px;
        }

        .whatsapp-input-group .form-control::placeholder {
            color: var(--hs-text-sub);
            opacity: 0.6;
        }

        .whatsapp-input-group .form-control:focus {
            border-color: var(--hs-primary);
            box-shadow: 0 0 0 3px rgba(255, 140, 0, 0.2);
            background: var(--hs-card-bg);
            color: var(--hs-text-main);
        }

        .whatsapp-prefix {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            color: var(--hs-text-sub);
            font-weight: 700;
            font-size: 0.9rem;
        }

        .request-qris-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(6px);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            display: none;
            color: #ffffff;
        }

        @media (max-width: 640px) {
            .hs-wrapper {
                padding: 16px 12px 32px;
            }
            .hs-header {
                padding: 22px 18px 16px;
            }
            .hs-title {
                font-size: 1.25rem;
            }
            .hs-body {
                padding: 18px 16px;
            }
        }
    </style>
@endpush

@push('body')
    <div class="hs-wrapper">
        <div class="hs-container">
            <div class="hs-main-card">
                <!-- Header -->
                <div class="hs-header">
                    <button type="button" id="hsThemeToggle" class="hs-theme-btn" aria-label="Toggle theme" title="Ubah Tema">
                        <i class="bi bi-moon-stars-fill" id="hsThemeIcon"></i>
                    </button>
                    <div class="hs-brand-badge">
                        <i class="bi bi-wifi me-1"></i> Ningrat Hotspot
                    </div>
                    <h1 class="hs-title">Beli Voucher Internet</h1>
                    <p class="hs-subtitle">
                        {{ $priceDetail['label'] ?? 'Voucher Hotspot' }} — 
                        <strong style="color: var(--hs-primary)">Rp{{ number_format($price ?? 0, 0, ',', '.') }}</strong>
                        <span class="ms-1 opacity-75">({{ TaxCalculate::getLabelTax($price ?? 0) }})</span>
                    </p>
                </div>

                <!-- Form: Step 1 Payment Method Selection -->
                <form id="form-req-voucher" action="{{ route('voucherRequest') }}" method="post" target="_newtab">
                    <div class="hs-body body-payment-method">
                        <div class="hs-section-title">
                            <i class="bi bi-credit-card"></i> Pilih Metode Pembayaran
                        </div>
                        <div class="accordion hs-accordion" id="paymentMethods">
                            @foreach ($channels as $category => $items)
                                @php
                                    $logoImages = $items
                                        ->pluck('logoPath')
                                        ->map(function ($path) {
                                            return '<img src="' . $path . '" alt="Logo" style="width: 20px; height: 20px; margin-left: 4px; object-fit: contain;">';
                                        })
                                        ->implode('');
                                @endphp
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="heading_{{ $category }}">
                                        <button
                                            class="accordion-button collapsed d-flex justify-content-between align-items-center"
                                            type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse_{{ $category }}" aria-expanded="false"
                                            aria-controls="collapse_{{ $category }}">

                                            <span class="flex-grow-1">{{ $categories[$category] ?? ucfirst($category) }}</span>
                                            <div class="d-flex align-items-center me-2">
                                                {!! $logoImages !!}
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse_{{ $category }}" class="accordion-collapse collapse"
                                        data-bs-parent="#paymentMethods">
                                        <div class="accordion-body">
                                            @foreach ($items as $channel)
                                                <div onclick="goConfirm(this)" data-id="{{ $channel->id }}" class="hs-channel-card">
                                                    <div class="hs-channel-top">
                                                        <span id="channel_name" class="hs-channel-name">{{ $channel->name }}</span>
                                                        <img id="channel_logo" src="{{ $channel->logoPath }}" alt="{{ $channel->name }}" class="hs-channel-logo">
                                                    </div>
                                                    <div class="hs-channel-meta">
                                                        <span>Biaya admin: {{ TaxCalculate::getLabelTax($channel->fee?->amount ?? 0, $channel->fee?->unit) }}</span>
                                                        <span id="channel_total" class="hs-channel-price">
                                                            Rp{{ number_format(TaxCalculate::calculate($price, $channel->fee?->amount, $channel->fee?->unit), 0, ',', '.') }}
                                                        </span>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <input type="hidden" name="channel_id">
                    <input type="hidden" name="seal_code" value="{{ $sealcode }}">
                    <input type="hidden" name="pointer" value="{{ $pointer }}">
                </form>

                <!-- Step 2: Summary / QRIS View -->
                <div class="hs-body body-summary" style="display: none">
                    <!-- Status Header -->
                    <div id="flag-status" class="hs-status-pill pending">
                        <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>
                        <span id="flag-msg">Pembayaran sedang berlangsung...</span>
                    </div>

                    <!-- Payment Countdown Timer -->
                    <div id="hs-timer-box" class="hs-timer-box mb-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <span class="small hs-label-sub"><i class="bi bi-clock me-1 text-warning"></i> Batas Waktu Pembayaran:</span>
                            <span id="hs-timer-value" class="fw-bold fs-6" style="color: var(--hs-primary); font-family: 'Sora', sans-serif;">15:00</span>
                        </div>
                    </div>

                    <!-- QRIS Image Box -->
                    <div id="box-instr-qris">
                        <div class="hs-qris-container">
                            <img class="hs-qris-img" id="box-qris" src="#" alt="QRIS Payment Code">
                            <span class="mt-2 small" style="color: #475569"><i class="bi bi-qr-code-scan me-1"></i> Scan QRIS untuk Bayar</span>
                        </div>

                        <div class="hs-instruction-box">
                            <div class="hs-instruction-title"><i class="bi bi-info-circle me-1"></i> Cara Pembayaran:</div>
                            <ol class="hs-instruction-list">
                                <li>Buka GoPay, OVO, DANA, ShopeePay, BCA, atau Mobile Banking pilihan Anda.</li>
                                <li>Pindai (Scan) Kode QRIS di atas.</li>
                                <li>Konfirmasi jumlah pembayaran dan selesaikan transaksi.</li>
                                <li>Voucher akan terbuka otomatis di halaman ini setelah lunas.</li>
                            </ol>
                        </div>
                    </div>

                    <!-- Unlocked Voucher Card -->
                    <div class="hs-voucher-card box-copy-button" style="display: none">
                        <div class="text-success fw-bold"><i class="bi bi-check-circle-fill me-1"></i> Pembayaran Berhasil!</div>
                        <div class="small text-muted mt-1">Kode Voucher Anda:</div>
                        <div id="coupon-code" class="hs-voucher-code">-</div>
                        
                        <button class="hs-btn-primary copy-button mb-2" type="button">
                            <i class="bi bi-clipboard me-1"></i> Salin Kode Voucher
                        </button>
                        <p class="small text-muted mb-0">Gunakan kode ini di halaman portal login Wi-Fi Anda.</p>
                    </div>

                    <!-- Order Details Grid -->
                    <div class="hs-info-grid">
                        <div class="hs-info-row">
                            <span class="hs-info-label">Invoice Code</span>
                            <span id="order_id" class="hs-info-val">{{ $orderid ?? '-' }}</span>
                        </div>
                        <div class="hs-info-row">
                            <span class="hs-info-label">Nama Paket</span>
                            <span id="item-name" class="hs-info-val">{{ $item_name ?? ($priceDetail['label'] ?? 'Voucher Hotspot') }}</span>
                        </div>
                        <div class="hs-info-row">
                            <span class="hs-info-label">Total Pembayaran</span>
                            <span id="total-amount" class="hs-info-val" style="color: var(--hs-primary)">Rp {{ number_format($price ?? 0, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div id="check-status-btn" class="mt-3 d-grid gap-2">
                        <button id="btn-check-manual" class="hs-btn-outline" type="button" onclick="checkStatus(true)">
                            <i class="bi bi-arrow-repeat me-1"></i> Cek Status Pembayaran
                        </button>
                        <button id="btn-cancel-payment" class="hs-btn-danger" type="button" onclick="cancelPayment()">
                            <i class="bi bi-x-circle me-1"></i> Batalkan Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Confirmation Modal -->
    <div class="modal fade hs-modal" id="cancelConfirmModal" tabindex="-1" aria-labelledby="cancelConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title" id="cancelConfirmModalLabel">
                        <i class="bi bi-exclamation-triangle-fill text-danger me-2"></i>Batalkan Pembayaran?
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <p class="hs-label-sub mb-0 small">Tindakan ini akan membatalkan nomor tagihan dan transaksi pembayaran Anda. Apakah Anda yakin ingin melanjutkan?</p>
                </div>
                <div class="modal-footer border-top-0 pt-2">
                    <button type="button" class="hs-btn-outline me-2" style="width: auto;" data-bs-dismiss="modal">Tidak, Kembali</button>
                    <button type="button" class="hs-btn-danger" style="width: auto;" id="confirmCancelBtn">
                        Ya, Batalkan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Confirmation Modal -->
    <div class="modal fade hs-modal" id="paymentConfirmModal" tabindex="-1" aria-labelledby="paymentConfirmModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentConfirmModalLabel">
                        <i class="bi bi-shield-check text-warning me-2"></i>Konfirmasi Pembayaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="hs-modal-details">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="hs-label-sub small">Metode Pembayaran:</span>
                            <span id="modal-payment-method" class="fw-bold" style="color: var(--hs-text-main)"></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="hs-label-sub small">Total Tagihan:</span>
                            <span id="modal-payment-total" class="fw-bold fs-6" style="color: var(--hs-primary)"></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="whatsappNumber" class="form-label small fw-bold" style="color: var(--hs-text-main)">
                            <i class="bi bi-whatsapp text-success me-1"></i>
                            Nomor WhatsApp <span class="hs-label-sub fw-normal">(Opsional)</span>
                        </label>
                        <div class="whatsapp-input-group">
                            <span class="whatsapp-prefix">+62</span>
                            <input type="tel" class="form-control" id="whatsappNumber" placeholder="8123456789" maxlength="12">
                        </div>
                        <div class="form-text small hs-label-sub mt-1">
                            <i class="bi bi-info-circle me-1"></i> Notifikasi kode voucher akan dikirimkan otomatis ke WhatsApp Anda.
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="hs-btn-outline me-2" style="width: auto;" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="hs-btn-primary" style="width: auto;" id="confirmPaymentBtn">
                        <i class="bi bi-qr-code-scan me-1"></i> Buat Tagihan QRIS
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="toast-copy" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Kode Voucher berhasil disalin ke clipboard!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>

    <!-- Loading Overlay -->
    <div class="request-qris-loader">
        <div class="spinner-border" style="color: var(--hs-primary); width: 3.5rem; height: 3.5rem;" role="status"></div>
        <p class="mt-3 fw-bold text-light">Memproses tagihan QRIS Anda...</p>
    </div>
@endpush

@push('script')
    <script>
        let pollTimer = null;
        let countdownTimer = null;
        let notificationSent = false;

        function playSuccessSound() {
            try {
                const AudioCtx = window.AudioContext || window.webkitAudioContext;
                if (!AudioCtx) return;
                const audioCtx = new AudioCtx();
                const now = audioCtx.currentTime;
                
                const osc1 = audioCtx.createOscillator();
                const gain1 = audioCtx.createGain();
                osc1.type = 'sine';
                osc1.frequency.setValueAtTime(523.25, now);
                gain1.gain.setValueAtTime(0.3, now);
                gain1.gain.exponentialRampToValueAtTime(0.001, now + 0.3);
                osc1.connect(gain1);
                gain1.connect(audioCtx.destination);
                osc1.start(now);
                osc1.stop(now + 0.3);

                const osc2 = audioCtx.createOscillator();
                const gain2 = audioCtx.createGain();
                osc2.type = 'sine';
                osc2.frequency.setValueAtTime(659.25, now + 0.15);
                gain2.gain.setValueAtTime(0.3, now + 0.15);
                gain2.gain.exponentialRampToValueAtTime(0.001, now + 0.6);
                osc2.connect(gain2);
                gain2.connect(audioCtx.destination);
                osc2.start(now + 0.15);
                osc2.stop(now + 0.6);
            } catch (e) {
                console.log('Audio alert error:', e);
            }
        }

        function requestNotificationPermission() {
            if ("Notification" in window && Notification.permission === "default") {
                Notification.requestPermission();
            }
        }

        function sendPaidNotification(voucherCode) {
            if (notificationSent) return;
            notificationSent = true;

            playSuccessSound();

            if ("Notification" in window) {
                if (Notification.permission === "granted") {
                    const notif = new Notification("Pembayaran Berhasil! 🎉", {
                        body: `Kode Voucher Anda: ${voucherCode}\nKlik untuk menyalin kode voucher.`,
                        tag: "voucher-paid-" + voucherCode,
                        requireInteraction: true
                    });
                    notif.onclick = function() {
                        window.focus();
                        if (navigator.clipboard && navigator.clipboard.writeText) {
                            navigator.clipboard.writeText(voucherCode).then(() => {
                                showToast('Kode Voucher berhasil disalin!');
                            });
                        }
                    };
                } else if (Notification.permission === "default") {
                    Notification.requestPermission().then(permission => {
                        if (permission === "granted") {
                            sendPaidNotification(voucherCode);
                        }
                    });
                }
            }
        }

        function startPaymentTimer(durationSeconds = 900) {
            if (countdownTimer) clearInterval(countdownTimer);
            let secondsLeft = durationSeconds;

            function updateTimerDisplay() {
                if (secondsLeft <= 0) {
                    clearInterval(countdownTimer);
                    $('#hs-timer-value').text('Expired').css('color', '#ef4444');
                    $('#flag-msg').text('Batas waktu pembayaran telah habis');
                    return;
                }
                const m = Math.floor(secondsLeft / 60);
                const s = secondsLeft % 60;
                const formatted = `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
                $('#hs-timer-value').text(formatted);
                secondsLeft--;
            }

            updateTimerDisplay();
            countdownTimer = setInterval(updateTimerDisplay, 1000);
        }

        function stopPaymentTimer() {
            if (countdownTimer) {
                clearInterval(countdownTimer);
                countdownTimer = null;
            }
        }

        // ── Theme Switcher ──
        const themeToggleBtn = document.getElementById('hsThemeToggle');
        const themeIcon = document.getElementById('hsThemeIcon');

        function applyTheme(isDark) {
            if (isDark) {
                document.documentElement.classList.add('dark');
                document.body.classList.add('dark');
                if (themeIcon) themeIcon.className = 'bi bi-sun-fill';
                localStorage.setItem('hs-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                document.body.classList.remove('dark');
                if (themeIcon) themeIcon.className = 'bi bi-moon-stars-fill';
                localStorage.setItem('hs-theme', 'light');
            }
        }

        const savedTheme = localStorage.getItem('hs-theme') || (window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light');
        applyTheme(savedTheme === 'dark');

        if (themeToggleBtn) {
            themeToggleBtn.addEventListener('click', function() {
                const isDark = !document.body.classList.contains('dark');
                applyTheme(isDark);
            });
        }

        let cancelConfirmModal;

        document.addEventListener('DOMContentLoaded', function() {
            paymentConfirmModal = new bootstrap.Modal(document.getElementById('paymentConfirmModal'));
            cancelConfirmModal = new bootstrap.Modal(document.getElementById('cancelConfirmModal'));
        });

        function goConfirm(el) {
            const c_name = $(el).find('#channel_name').text();
            const c_total = $(el).find('#channel_total').text();

            $('#modal-payment-method').text(c_name);
            $('#modal-payment-total').text(c_total);

            paymentConfirmModal.show();
        }

        $('#confirmPaymentBtn').on('click', function() {
            $(this).prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Memproses...');

            const whatsappNumber = $('#whatsappNumber').val().trim();
            paymentConfirmModal.hide();

            requestQRIS(whatsappNumber);
        });

        $('#paymentConfirmModal').on('hidden.bs.modal', function() {
            $('#confirmPaymentBtn').prop('disabled', false).html('<i class="bi bi-qr-code-scan me-1"></i> Buat Tagihan QRIS');
            $('#whatsappNumber').val('');
        });

        $('#whatsappNumber').on('input', function() {
            let value = $(this).val().replace(/\D/g, '').replace(/^0+/, '');
            if (value.length > 12) {
                value = value.substring(0, 12);
            }
            $(this).val(value);
        });

        function requestQRIS(whatsappNumber = '') {
            const loader = $('.request-qris-loader');
            loader.css('display', 'flex');

            const form = $('#form-req-voucher');

            const requestData = {
                pointer: form.find('[name="pointer"]').val(),
                channel_id: 'qris',
                seal_code: form.find('[name="seal_code"]').val()
            };

            if (whatsappNumber) {
                requestData.whatsapp_number = '+62' + whatsappNumber;
            }

            $.ajax({
                url: "{{ route('voucherRequest-qris') }}",
                method: "POST",
                data: requestData,
                success: function(resp) {
                    loader.css('display', 'none');
                    $('.body-payment-method').remove();
                    $('.body-summary').show();
                    disableButton();

                    $('#box-qris').attr('src', resp.url);

                    requestNotificationPermission();
                    checkStatus();
                    startPaymentTimer(900);

                    if (!pollTimer) {
                        pollTimer = setInterval(checkStatus, 4000);
                    }
                },
                error: function(xhr, status, error) {
                    loader.css('display', 'none');
                    const errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan saat membuat tagihan QRIS.';
                    showToast('Error: ' + errorMsg);
                }
            });
        }

        function disableButton() {
            $('#flag-status').show();
            $('#flag-msg').text('Menunggu pembayaran...');
            $('#check-status-btn').show();
        }

        function checkStatus(isManual = false) {
            const sealcode = $('[name="seal_code"]').val();
            const manualBtn = $('#btn-check-manual');

            if (isManual) {
                manualBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Mengecek status...');
            }

            $.ajax({
                url: '{{ route('voucherDetails') }}/' + sealcode,
                method: 'GET',
                success: function(response) {
                    if (isManual) {
                        manualBtn.prop('disabled', false).html('<i class="bi bi-arrow-repeat me-1"></i> Cek Status Pembayaran');
                    }

                    if (!response.error && response.data.status == '1') {
                        if (pollTimer) {
                            clearInterval(pollTimer);
                            pollTimer = null;
                        }
                        stopPaymentTimer();

                        $('#flag-status')
                            .removeClass('pending')
                            .addClass('success')
                            .html('<i class="bi bi-check-circle-fill me-1"></i> Pembayaran Telah Berhasil!');
                        
                        $('#coupon-code').html(`<strong>${response.data.code}</strong>`);
                        $('.box-copy-button').show();
                        $('#box-instr-qris').remove();
                        $('#hs-timer-box').remove();
                        $('#check-status-btn').remove();
                        $('#btn-check-manual').remove();
                        $('#btn-cancel-payment').remove();

                        sendPaidNotification(response.data.code);
                    } else {
                        if (isManual) {
                            showToast('Pembayaran belum terdeteksi. Silakan coba lagi setelah transfer/scan.');
                        }
                        if (response.data?.order_id) {
                            $('#order_id').html(response.data.order_id);
                        }
                        if (response.data?.description) {
                            $('#item-name').html(response.data.description);
                        }
                        if (response.data?.total_amount) {
                            $('#total-amount').html(`Rp ${response.data.total_amount}`);
                        }
                    }
                },
                error: function(xhr, status, error) {
                    if (isManual) {
                        manualBtn.prop('disabled', false).html('<i class="bi bi-arrow-repeat me-1"></i> Cek Status Pembayaran');
                    }
                    console.error('Error checking status:', error);
                }
            });
        }

        function cancelPayment() {
            if (cancelConfirmModal) {
                cancelConfirmModal.show();
            }
        }

        $('#confirmCancelBtn').on('click', function() {
            const sealcode = $('[name="seal_code"]').val();
            const btn = $(this);
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1"></span>Memproses...');

            $.ajax({
                url: '{{ route('voucherCancel') }}/' + sealcode,
                method: 'POST',
                success: function(response) {
                    if (pollTimer) {
                        clearInterval(pollTimer);
                        pollTimer = null;
                    }
                    stopPaymentTimer();
                    cancelConfirmModal.hide();
                    showToast(response.message || 'Transaksi berhasil dibatalkan.');
                    setTimeout(() => window.location.reload(), 1000);
                },
                error: function(xhr) {
                    btn.prop('disabled', false).html('Ya, Batalkan');
                    cancelConfirmModal.hide();
                    const msg = xhr.responseJSON?.message || 'Gagal membatalkan transaksi.';
                    showToast('Error: ' + msg);
                }
            });
        });

        $('.copy-button').click(function() {
            const voucherCode = $('#coupon-code').text().trim();
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(voucherCode).then(() => {
                    showToast('Kode Voucher berhasil disalin!');
                }).catch(() => {
                    showToast('Gagal menyalin Kode Voucher');
                });
            } else {
                const textArea = document.createElement('textarea');
                textArea.value = voucherCode;
                textArea.style.position = 'fixed';
                textArea.style.opacity = 0;
                document.body.appendChild(textArea);
                textArea.focus();
                textArea.select();
                try {
                    document.execCommand('copy');
                    showToast('Kode Voucher berhasil disalin!');
                } catch (err) {
                    showToast('Gagal menyalin Kode Voucher');
                }
                document.body.removeChild(textArea);
            }
        });

        function showToast(message) {
            const toastElement = document.getElementById('toast-copy');
            const toastBody = toastElement.querySelector('.toast-body');
            toastBody.textContent = message;
            const toast = new bootstrap.Toast(toastElement);
            toast.show();
        }

        window.addEventListener('beforeunload', (event) => {
            if ($('.body-summary').is(':visible') && !$('.box-copy-button').is(':visible')) {
                event.preventDefault();
                event.returnValue = '';
            }
        });
    </script>
@endpush
