@extends('base-landing', ['title' => 'Ningrat ISP | Metode Pembayaran'])

@push('head')
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.min.css') }}" rel="stylesheet">

    <style>
        .payment-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            /* Ensure full viewport height */
            overflow-y: auto;
            /* Allow vertical scrolling */
            padding: 20px;
            /* Add padding to prevent content from touching the edges */
            box-sizing: border-box;
        }

        .payment-container {
            width: 50%;
        }

        @media (max-width: 768px) {
            .payment-container {
                width: 90%;
            }
        }

        .summary-instructions {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 10px;
        }

        .summary-box {
            flex: 1;
            background-color: #f9fafc;
            border-radius: 10px;
            padding: 20px;
            border-left: 6px solid #0d6efd;
        }

        .summary-box h5 {
            font-size: 1.3rem;
            color: #222;
        }

        .summary-box p {
            font-size: 1rem;
            margin: 6px 0;
        }

        .summary-box strong {
            font-weight: bold;
        }

        .summary-box .table td:first-child {
            width: 30%;
        }

        .summary-box .table td:last-child {
            width: 70%;
        }

        .copy-button {
            white-space: nowrap;
            /* Ensure text/icon stay on one line */
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .request-qris-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.9);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 1050;
            display: none;
            /* Initially hidden */
        }

        .whatsapp-input-group {
            position: relative;
        }

        .whatsapp-input-group .form-control {
            padding-left: 80px;
        }

        .whatsapp-prefix {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            color: #6c757d;
            font-weight: 500;
        }

        .modal-header {
            background: linear-gradient(135deg, #0d6efd, #0056b3);
            color: white;
        }

        .modal-header .btn-close {
            filter: invert(1) grayscale(100%) brightness(200%);
        }

        .payment-details {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
            border-left: 4px solid #0d6efd;
        }
    </style>
@endpush

@push('body')
    <div class="payment-wrapper">
        <div class="payment-container">
            <div class="card shadow">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Ningrat ISP</h4>
                    <p class="mb-0">Voucher {{ TaxCalculate::getLabelTax($price ?? 0) }}
                        ({{ $priceDetail['label'] ?? '' }})</p>
                    {{-- <small>Order ID MID-62215736789</small> --}}
                </div>
                <form id="form-req-voucher" action="{{ route('voucherRequest') }}" method="post" target="_newtab">
                    <div class="card-body body-payment-method">
                        <h5 class="mb-3">Pilih Metode Pembayaran</h5>
                        <div class="accordion" id="paymentMethods">
                            <!-- ATM/Bank Transfer -->
                            @foreach ($channels as $category => $items)
                                @php
                                    $logoImages = $items
                                        ->pluck('logoPath')
                                        ->map(function ($path) {
                                            return '<img src="' .
                                                $path .
                                                '" alt="Logo" style="width: 20px; height: 20px; margin-right: 5px;">';
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

                                            <!-- Left side for category name -->
                                            <span class="flex-grow-1">{{ $categories[$category] }}</span>

                                            <!-- Right side for icon/small image -->
                                            <div class="d-flex align-items-center">
                                                {!! $logoImages !!}
                                            </div>
                                        </button>
                                    </h2>
                                    <div id="collapse_{{ $category }}" class="accordion-collapse collapse"
                                        data-bs-parent="#paymentMethods">

                                        @foreach ($items as $channel)
                                            <a onclick="goConfirm(this)" data-id="{{ $channel->id }}"
                                                class="text-decoration-none text-dark">
                                                <div class="accordion-body">
                                                    <!-- Two sides: channel name on the left and logo on the right -->
                                                    <div class="d-flex justify-content-between align-items-center">
                                                        <!-- Channel Name -->
                                                        <span id="channel_name">{{ $channel->name }}</span>

                                                        <!-- Channel Logo -->
                                                        <img id="channel_logo" src="{{ $channel->logoPath }}"
                                                            alt="{{ $channel->name }} Logo"
                                                            style="width: 30px; height: 30px;">
                                                    </div>
                                                    <!-- Fee information below -->
                                                    <div class="mt-2">
                                                        <div class="row">
                                                            <div class="col">
                                                                <small class="text-muted">Harga:
                                                                    Rp{{ number_format($price, 0, ',', '.') }}</small>
                                                            </div>
                                                            <div class="col">
                                                                <small id="channel_fee" class="text-muted">Biaya admin:
                                                                    {{ TaxCalculate::getLabelTax($channel->fee?->amount ?? 0, $channel->fee?->unit) }}</small>
                                                            </div>
                                                            <div class="col">
                                                                <small id="channel_total" class="text-muted">Total:
                                                                    Rp{{ number_format(TaxCalculate::calculate($price, $channel->fee?->amount, $channel->fee?->unit), 0, ',', '.') }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>

                                </div>
                            @endforeach

                        </div>
                    </div>
                    <input type="hidden" name="channel_id">
                    <input type="hidden" name="seal_code" value="{{ $sealcode }}">
                    <input type="hidden" name="pointer" value="{{ $pointer }}">
                </form>
                <div class="card-body body-summary" style="display: none">
                    <h5 class="mb-3">Detail Pesanan</h5>
                    <div class="row">
                        <div class="progress-indicator">
                            <button id="flag-status" class="btn btn-warning" type="button" disabled style="display: show">
                                <span class="spinner-border spinner-border-sm" aria-hidden="true"></span>
                                <span id="flag-msg" class="" role="status"></span>
                            </button>
                        </div>
                    </div>
                    <div id="box-instr-qris">
                        <div class="row">
                            <img class="img-fluid" id="box-qris" src="#" alt="">
                        </div>
                        <div class="row">
                            <div class="container text-center mt-5">
                                <h1 class="mb-4">Cara Membayar Menggunakan QRIS</h1>
                                <ol class="text-start mx-auto" style="max-width: 600px;">
                                    <li><strong>Buka Aplikasi Pembayaran</strong>: Gunakan aplikasi pembayaran yang
                                        mendukung
                                        QRIS (seperti Gojek, OVO, Dana, LinkAja, ShopeePay, atau aplikasi mobile banking).
                                    </li>
                                    <li><strong>Pindai Kode QR</strong>: Arahkan kamera ponsel Anda ke kode QR yang
                                        ditampilkan
                                        di layar ini.</li>
                                    <li><strong>Konfirmasi Pembayaran</strong>: Periksa detail pembayaran yang muncul di
                                        aplikasi Anda, lalu tekan tombol konfirmasi untuk menyelesaikan pembayaran.</li>
                                    <li><strong>Selesai!</strong>: Screenshot bukti pembayaran jika diperlukan.</li>
                                </ol>
                                <p class="mt-4">Terima kasih telah menggunakan layanan kami!</p>
                            </div>
                        </div>
                    </div>
                    <div class="summary-instructions">
                        <div class="summary-box d-flex align-items-center justify-content-between">
                            <h5>
                                Kupon: <span id="coupon-code"></span>
                            </h5>
                            <div class="box-copy-button" style="display: none">
                                <button class="btn btn-outline-primary btn-sm d-flex align-items-center copy-button">
                                    <i class="bi bi-clipboard"></i> <!-- Bootstrap Icon -->
                                    <span class="ms-1">Copy</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="summary-instructions">
                        <div class="summary-box">
                            <h5>Order Overview</h5>
                            <table class="table">
                                <tr>
                                    <td>Invoice Code</td>
                                    <td id="order_id">
                                        <strong>{{ $orderid ?? '' }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Item Name</td>
                                    <td id="item-name">
                                        <strong>{{ $item_name ?? '' }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Quantity</td>
                                    <td id="quantity">
                                        <strong>1</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Total Amount</td>
                                    <td id="total-amount">
                                        <strong>Rp {{ number_format($price ?? 0, 0, ',', '.') }}</strong>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div id="check-status-btn" style="display: show" class="pt-3">
                        <button class="btn btn-outline-primary w-100" onclick="checkStatus()">Check
                            Status</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Confirmation Modal -->
    <div class="modal fade" id="paymentConfirmModal" tabindex="-1" aria-labelledby="paymentConfirmModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="paymentConfirmModalLabel">
                        <i class="bi bi-credit-card me-2"></i>Konfirmasi Pembayaran
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="payment-details">
                        <h6 class="fw-bold mb-2">Detail Pembayaran:</h6>
                        <div class="d-flex justify-content-between mb-1">
                            <span>Metode Pembayaran:</span>
                            <span id="modal-payment-method" class="fw-bold"></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Total Pembayaran:</span>
                            <span id="modal-payment-total" class="fw-bold text-primary"></span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="whatsappNumber" class="form-label">
                            <i class="bi bi-whatsapp text-success me-1"></i>
                            Nomor WhatsApp <span class="text-muted">(Opsional)</span>
                        </label>
                        <div class="whatsapp-input-group">
                            <span class="whatsapp-prefix">+62</span>
                            <input type="tel" class="form-control" id="whatsappNumber" placeholder="8123456789"
                                maxlength="12">
                        </div>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Nomor WhatsApp untuk notifikasi voucher (tanpa +62 dan angka 0 di depan)
                        </div>
                    </div>

                    <div class="alert alert-info d-flex align-items-center" role="alert">
                        <i class="bi bi-info-circle-fill me-2"></i>
                        <div>
                            <small>Pastikan data pembayaran sudah sesuai sebelum melanjutkan.</small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <button type="button" class="btn btn-primary" id="confirmPaymentBtn">
                        <i class="bi bi-check-circle me-1"></i>Buat Tagihan
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="toast-copy" class="toast align-items-center text-bg-success border-0" role="alert"
            aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Voucher code copied to clipboard!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>

    <div class="request-qris-loader">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
        </div>
        <p class="mt-3">Please wait, processing your request...</p>
    </div>
@endpush

@push('script')
    <script>
        let paymentConfirmModal;

        document.addEventListener('DOMContentLoaded', function() {
            paymentConfirmModal = new bootstrap.Modal(document.getElementById('paymentConfirmModal'));
        });

        function goConfirm(el) {
            const c_name = $(el).find('#channel_name').text();
            const c_total = $(el).find('#channel_total').text();

            // Populate modal with payment details
            $('#modal-payment-method').text(c_name);
            $('#modal-payment-total').text(c_total);

            // Show the modal
            paymentConfirmModal.show();
        }

        // Handle confirm payment button click
        $('#confirmPaymentBtn').on('click', function() {
            // Disable the button to prevent multiple clicks
            $(this).prop('disabled', true);
            $(this).html('<span class="spinner-border spinner-border-sm me-1"></span>Memproses...');

            // Get WhatsApp number
            const whatsappNumber = $('#whatsappNumber').val().trim();

            // Hide modal
            paymentConfirmModal.hide();

            // Call requestQRIS with WhatsApp number
            requestQRIS(whatsappNumber);
        });

        // Reset modal when hidden
        $('#paymentConfirmModal').on('hidden.bs.modal', function() {
            $('#confirmPaymentBtn').prop('disabled', false);
            $('#confirmPaymentBtn').html('<i class="bi bi-check-circle me-1"></i>Buat Tagihan');
            $('#whatsappNumber').val('');
        });

        // WhatsApp number formatting
        $('#whatsappNumber').on('input', function() {
            let value = $(this).val().replace(/\D/g, ''); // Remove non-digits

            // Remove leading zeros
            value = value.replace(/^0+/, '');

            // Limit to 12 digits (typical Indonesian mobile number length without country code)
            if (value.length > 12) {
                value = value.substring(0, 12);
            }

            $(this).val(value);
        });

        function requestQRIS(whatsappNumber = '') {
            const loader = $('.request-qris-loader');
            loader.css('display', 'flex');

            const form = $('#form-req-voucher');

            // Prepare data for AJAX request
            const requestData = {
                pointer: form.find('[name="pointer"]').val(),
                channel_id: 'qris',
                seal_code: form.find('[name="seal_code"]').val()
            };

            // Add WhatsApp number if provided
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

                    let boxQris = $('#box-qris');
                    boxQris.attr('src', resp.url);

                    checkStatus();
                },
                // error: function(xhr, status, error) {
                //     loader.css('display', 'none');
                //     console.error('Error requesting QRIS:', error);

                //     // Show error message
                //     const errorMsg = xhr.responseJSON?.message || 'Terjadi kesalahan saat memproses pembayaran';
                //     alert('Error: ' + errorMsg);
                // }
            });
        }

        function disableButton() {
            $('#flag-status').show();
            $('#flag-msg').text('Pembayaran sedang berlangsung...');
            $('#check-status-btn').show();
        }

        function checkStatus() {
            const sealcode = $('[name="seal_code"]').val();
            $.ajax({
                url: '{{ route('voucherDetails') }}/' + sealcode,
                method: 'GET',
                success: function(response) {
                    if (!response.error && response.data.status == '1') {
                        // Optionally, handle further actions here
                        $('#flag-status .spinner-border').hide();
                        $('#flag-msg').text('Pembayaran telah berhasil');
                        $('#flag-status').removeClass('btn-warning').addClass('btn-success');
                        $('#coupon-code').html(`<strong>${response.data.code}</strong>`);
                        $('.box-copy-button').show();
                        $('#box-instr-qris').hide();
                    } else {
                        $('#order_id').html(`<strong>${response.data.order_id}</strong>`);
                        $('#item-name').html(`<strong>${response.data.description}</strong>`);
                    }

                    $('#total-amount').html(`<strong>Rp ${response.data.total_amount}</strong>`);
                },
                error: function(xhr, status, error) {
                    console.error('Error checking status:', error);
                }
            });
        }

        $('.copy-button').click(function() {
            const voucherCode = $('#coupon-code').text();
            if (navigator.clipboard && navigator.clipboard.writeText) {
                // Use Clipboard API if supported
                navigator.clipboard.writeText(voucherCode).then(() => {
                    showToast('Kode Voucher berhasil disalin!');
                }).catch(() => {
                    showToast('Gagal menyalin Kode Voucher');
                });
            } else {
                // Fallback for unsupported browsers
                const textArea = document.createElement('textarea');
                textArea.value = voucherCode;
                textArea.style.position = 'fixed'; // Avoid scrolling issues
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
            // Cancel the event as stated by the standard.
            event.preventDefault();
            // Chrome requires returnValue to be set.
            event.returnValue = '';
        });
    </script>
@endpush
