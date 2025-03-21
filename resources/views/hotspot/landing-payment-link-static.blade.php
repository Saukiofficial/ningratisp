@extends('base-landing', ['title' => 'Ningrat ISP | Metode Pembayaran'])

@push('head')
    <link href="{{ asset('assets/css/bootstrap-icons.min.css') }}" rel="stylesheet">

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
                                                                    {{ TaxCalculate::getLabelTax($channel->fee()?->amount ?? 0, $channel->fee()?->unit) }}</small>
                                                            </div>
                                                            <div class="col">
                                                                <small id="channel_total" class="text-muted">Total:
                                                                    Rp{{ number_format(TaxCalculate::calculate($price, $channel->fee()?->amount, $channel->fee()?->unit), 0, ',', '.') }}</small>
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
        function goConfirm(el) {
            const c_name = $(el).find('#channel_name').text()
            const c_total = $(el).find('#channel_total').text()
            const msg = `Apakah data pembayaran sudah sesuai? <br><br>
        <p>Metode pembayaran : ${c_name} <br>${c_total}</p>
        <small class="text-muted text-small"><em>klik OK untuk melanjutkan</em></small>
        `
            bootbox.confirm({
                message: msg,
                buttons: {
                    confirm: {
                        label: 'Buat Tagihan',
                        className: 'btn-primary bootbox-bayar'
                    },
                    cancel: {
                        label: 'Batal',
                        className: 'btn-secondary'
                    }
                },
                callback: function(r) {
                    // Disable the confirm button after clicking "OK"
                    $('.bootbox-bayar').prop('disabled', true);
                    if (r) {
                        // $('[name="channel_id"]').val($(el).data('id'))
                        // $('#form-req-voucher').submit()
                        // $('.body-payment-method').remove()
                        // $('.body-summary').show()
                        // disableButton()
                        // setTimeout(() => {
                        //     checkStatus()
                        // }, 2000);

                        requestQRIS()
                    }
                }
            });
        }

        function requestQRIS() {
            const loader = $('.request-qris-loader')
            loader.css('display', 'flex')

            const form = $('#form-req-voucher');
            console.log()

            $.ajax({
                url: "{{ route('voucherRequest-qris') }}",
                method: "POST",
                data: {
                    pointer: form.find('[name="pointer"]').val(),
                    channel_id: 'qris',
                    seal_code: form.find('[name="seal_code"]').val()
                },
                success: function(resp) {
                    loader.css('display', 'none')
                    $('.body-payment-method').remove()
                    $('.body-summary').show()
                    disableButton()

                    let boxQris = $('#box-qris')
                    boxQris.attr('src', resp.url)

                    checkStatus()
                }
            })
        }

        function disableButton() {
            $('#flag-status').show()
            $('#flag-msg').text('Pembayaran sedang berlangsung...')

            $('#check-status-btn').show()
        }

        function checkStatus() {
            const sealcode = $('[name="seal_code"]').val();
            $.ajax({
                url: '{{ route('voucherDetails') }}/' + sealcode,
                method: 'GET',
                success: function(response) {
                    if (!response.error && response.data.status == '1') {
                        // Optionally, handle further actions here
                        $('#flag-status .spinner-border').hide()
                        $('#flag-msg').text('Pembayaran telah berhasil')
                        $('#flag-status').removeClass('btn-warning').addClass('btn-success')
                        $('#coupon-code').html(`<strong>${response.data.code}</strong>`)
                        $('.box-copy-button').show()
                        $('#box-instr-qris').hide()
                    } else {
                        $('#order_id').html(`<strong>${response.data.order_id}</strong>`)
                        $('#item-name').html(`<strong>${response.data.description}</strong>`)
                    }

                    $('#total-amount').html(`<strong>Rp ${response.data.total_amount}</strong>`)
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
        })

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
