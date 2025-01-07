<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Methods</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .payment-container {
            width: 50%;
            margin: auto;
            position: absolute;
            top: 40%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        @media (max-width: 768px) {
            .payment-container {
                width: 90%;
                /* Adjust width for smaller screens */
            }
        }
    </style>
</head>

<body>
    <div class="payment-container">
        <div class="card shadow">
            <div class="card-header bg-primary text-white text-center">
                <h4 class="mb-0">Ningrat ISP</h4>
                <p class="mb-0">Voucher {{ TaxCalculate::getLabelTax($price ?? 0) }} ({{ $hour }} Jam)</p>
                {{-- <small>Order ID MID-62215736789</small> --}}
            </div>
            <div class="card-body">
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
                                <form id="form-req-voucher" action="{{ route('postVoucher') }}" method="post">
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
                                    <input type="hidden" name="channel_id">
                                    <input type="hidden" name="hour" value="{{ $hour }}">
                                </form>
                            </div>

                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <!-- JS dependencies -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/6.0.0/bootbox.min.js"
        integrity="sha512-oVbWSv2O4y1UzvExJMHaHcaib4wsBMS5tEP3/YkMP6GmkwRJAa79Jwsv+Y/w7w2Vb/98/Xhvck10LyJweB8Jsw=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
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
                        label: 'Bayar',
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
                        $('[name="channel_id"]').val($(el).data('id'))
                        $('#form-req-voucher').submit()
                    }
                }
            });
        }
    </script>
</body>

</html>
