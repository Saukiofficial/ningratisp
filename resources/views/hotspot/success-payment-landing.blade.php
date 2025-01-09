@extends('base-landing', ['title' => 'Ningrat ISP | Pembayaran Berhasil'])

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .payment-container {
            width: 25%;
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
@endpush

@push('body')
    <div class="payment-container">
        <div class="card shadow">
            <div class="card-header bg-success text-white text-center">
                {{-- <h4 class="mb-0">Ningrat ISP</h4>
            <p class="mb-0">Voucher {{ TaxCalculate::getLabelTax($price ?? 0) }} ({{ $hour }} Jam)</p> --}}
                {{-- <small>Order ID MID-62215736789</small> --}}
            </div>
            <div class="card-body text-center">
                <div class="text-success mb-3">
                    <div class="payment-pending-logo">
                        <?xml version="1.0" encoding="utf-8"?><!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
                        <svg fill="#000000" width="100px" height="100px" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M23,12A11,11,0,0,1,4.963,20.451l-.256.256A1,1,0,0,1,4,21a.987.987,0,0,1-.383-.076A1,1,0,0,1,3,20V18a1,1,0,0,1,1-1H6a1,1,0,0,1,.707,1.707l-.322.322A9,9,0,1,0,3,12a9.107,9.107,0,0,0,.18,1.8,1,1,0,0,1-1.96.4A11,11,0,1,1,23,12ZM12,5a1,1,0,0,0-1,1v6a1,1,0,0,0,1,1h5a1,1,0,0,0,0-2H13V6A1,1,0,0,0,12,5Z" />
                        </svg>
                    </div>
                    <div class="payment-finish-logo" style="display: none">
                        <svg fill="#000000" width="200px" height="200px" viewBox="0 0 64 64"
                            xmlns="http://www.w3.org/2000/svg">
                            <g id="a" />
                            <g id="b">
                                <path
                                    d="M26.5,51c1.3789,0,2.5-1.1211,2.5-2.5s-1.1211-2.5-2.5-2.5-2.5,1.1211-2.5,2.5,1.1211,2.5,2.5,2.5Zm0-4c.8271,0,1.5,.6729,1.5,1.5s-.6729,1.5-1.5,1.5-1.5-.6729-1.5-1.5,.6729-1.5,1.5-1.5Zm1.0254-18.9443c-.6943-.46-1.0898-.7627-1.1455-.8086-.0186-.127,.0234-.2441,.0703-.2969,.0156-.0186,.0303-.0352,.1006-.04,.1572-.002,.2617,.1045,.2676,.1113,.4609,.6377,1.3604,.8164,2.0488,.4062,.6826-.4043,.8916-1.25,.4854-1.9678-.3096-.5462-1.1729-1.2924-2.4286-1.4048-.0156-.0726-.0285-.1354-.0327-.1725-.0724-.444-.7509-.436-.8206,0l-.0422,.2158c-.7497,.1201-1.421,.4658-1.9034,1.0188-.6045,.6904-.874,1.6113-.7402,2.5264,.1904,1.292,1.4189,2.1074,2.4072,2.7637,.3105,.2061,.9561,.6348,1.0645,.8047,.0928,.1523,.1816,.3721,.0762,.5537-.085,.1455-.2637,.2373-.4971,.2539-.1924,.0078-.5342-.291-.6699-.498-.4258-.6729-1.3262-.8975-2.0449-.5068-.6699,.3633-.9062,1.1299-.5869,1.9072,.3528,.8613,1.5208,1.7751,2.8857,1.934l.0511,.2616c.0697,.436,.7482,.4439,.8206,0,.0062-.0555,.0311-.1678,.0576-.29,1.1255-.1612,2.1003-.7606,2.6313-1.6771,.6045-1.043,.5654-2.2734-.1074-3.376-.4355-.7168-1.2041-1.2256-1.9473-1.7188Zm1.1895,4.5928c-.4102,.71-1.2041,1.1709-2.1182,1.2344l-.1699,.0059c-1.125,0-2.1377-.7842-2.3672-1.3467-.0586-.1406-.1611-.4863,.1367-.6475,.085-.0459,.1787-.0684,.2715-.0684,.1826,0,.3613,.085,.458,.2373,.0059,.0098,.6982,1.0029,1.584,.9531,.5635-.0391,1.0322-.3115,1.2871-.748,.2715-.4688,.2393-1.0439-.0869-1.5781-.2012-.3291-.6924-.6699-1.3662-1.1172-.8643-.5742-1.8447-1.2246-1.9688-2.0742-.0928-.626,.0908-1.2539,.5029-1.7236,.3721-.4268,.9131-.6855,1.5215-.7285,1.1299-.0791,1.8955,.5742,2.083,.9053,.1055,.1875,.1367,.46-.126,.6152-.2393,.1416-.5723,.084-.7383-.1465-.1016-.1289-.4688-.5527-1.1348-.5088-.3203,.0234-.585,.1504-.7861,.3789-.251,.2881-.3662,.6992-.3076,1.1006,.0557,.3809,.4258,.7314,1.583,1.498,.6523,.4316,1.3262,.8789,1.6465,1.4053,.4766,.7803,.5107,1.6377,.0957,2.3535Zm26.0879-12.5459c-.123-.0938-.2822-.124-.4355-.085-.0156,.0059-1.6318,.4346-3.3975-.3428-.5264-.2314-1.127-.5078-1.7568-.7969-1.9437-.8945-3.9327-1.8059-5.2129-2.1177v-2.2603c0-2.4814-2.0186-4.5-4.5-4.5H13.5c-2.4814,0-4.5,2.0186-4.5,4.5V49.5c0,2.4814,2.0186,4.5,4.5,4.5h26c2.4814,0,4.5-2.0186,4.5-4.5v-15.8103c.3678,.1519,.7612,.3335,1.1855,.533,1.6982,.7969,3.8115,1.7891,6.4727,1.7891,2.1777,0,3.1797-1.1377,3.2207-1.1855,.0781-.0908,.1211-.207,.1211-.3262v-14c0-.1562-.0732-.3027-.1973-.3975Zm-.8027,14.1836c-.2754,.2324-1.0186,.7256-2.3418,.7256-2.4375,0-4.4395-.9395-6.0479-1.6943-1.0498-.4932-1.957-.9189-2.7715-.9893-.5498-.0479-1.1416-.0752-1.7471-.1025-2.2402-.1035-4.7783-.2217-5.752-1.4678-.3682-.4717-.4424-1.1826-.1895-1.8135,.3125-.7754,1.0566-1.3018,2.043-1.4434,.7441-.1074,1.6709-.1426,2.6533-.1807,2.3857-.0908,5.0898-.1943,6.9961-1.3281,.2373-.1406,.3154-.4482,.1738-.6855-.1426-.2373-.4492-.3145-.6855-.1738-1.6865,1.0039-4.2559,1.1016-6.5225,1.1885-.4174,.0157-.8212,.0326-1.2123,.0535-1.4095-5.455-6.4222-9.3748-12.0963-9.3748-6.8926,0-12.5,5.6074-12.5,12.5s5.6074,12.5,12.5,12.5c5.582,0,10.4257-3.6604,11.9813-8.9614,.8444,.1016,1.7225,.1468,2.5646,.186,.5918,.0273,1.1699,.0537,1.707,.0996,.0767,.0066,.1649,.0322,.2471,.048v16.1277c0,1.9297-1.5703,3.5-3.5,3.5H13.5c-1.9297,0-3.5-1.5703-3.5-3.5V14.5c0-1.9297,1.5703-3.5,3.5-3.5h26c1.9297,0,3.5,1.5703,3.5,3.5v2.1494c-1.6783,.1625-5.3053,2.1736-5.7402,2.4122-.2422,.1328-.3311,.4365-.1982,.6787,.1338,.2432,.4385,.3301,.6787,.1982,1.6641-.9111,4.6211-2.3096,5.5303-2.2979,.9609,.0459,3.4912,1.21,5.5244,2.1455,.6348,.292,1.2412,.5713,1.7725,.8047,1.3877,.6094,2.6914,.5908,3.4326,.5039v13.1914Zm-19.4482-2.9131c.6519,.8354,1.7186,1.2675,2.9341,1.5099-1.4708,4.8082-5.8954,8.1171-10.9859,8.1171-6.3408,0-11.5-5.1592-11.5-11.5s5.1592-11.5,11.5-11.5c5.1597,0,9.7238,3.5217,11.0817,8.4492-.1823,.0181-.3618,.0373-.5309,.0616-1.3486,.1943-2.3789,.9453-2.8281,2.0605-.3896,.9697-.2637,2.043,.3291,2.8018Z" />
                            </g>

                        </svg>
                    </div>

                </div>
                <h5 class="card-title fw-bold payment-title" style="display: none">Payment successful!</h5>
                <p class="card-text text-muted payment-desc" style="display: none">
                    Kamu telah berhasil membeli voucher! 🎉 Berikut detail vouchernya.
                </p>

                <div class="row border-top pt-3 mb-3 voucher-box" style="display: none">
                    <div class="col">
                        <p class=""><strong>Voucher:</strong></p>
                        <p class=""><strong>Order ID:</strong></p>
                        <p class=""><strong>Keterangan:</strong></p>
                    </div>
                    <div class="col text-start">
                        <p class="voucher-text">-</p>
                        <p class="voucher-orderid">#</p>
                        <p class="voucher-desc">#</p>
                    </div>
                </div>
                <div class="row border-top pt-3 mb-3 voucher-box-pending" style="display: none">
                    <div class="col text-start">
                        <p class=""><strong>Kode Tagihan:</strong></p>
                        {{-- <p class=""><strong>Keterangan:</strong></p> --}}
                    </div>
                    <div class="col text-start">
                        <p class="voucher-orderid">#</p>
                        {{-- <p class="voucher-desc">#</p> --}}
                    </div>
                </div>

                <a href="#" class="btn btn-outline-success w-100 mb-2" style="display: none" id="copy-btn">Salin
                    Voucher</a>
                <a href="#" onclick="location.reload()" class="btn btn-success w-100">refresh</a>

            </div>
        </div>
    </div>

    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
        <div id="toast-copy" class="toast align-items-center text-bg-success border-0" role="alert" aria-live="assertive"
            aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    Voucher code copied to clipboard!
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"
                    aria-label="Close"></button>
            </div>
        </div>
    </div>
@endpush

@push('script')
    <script>
        const pending_logo = $('.payment-pending-logo')
        const success_logo = $('.payment-finish-logo')
        var payment_title = $('.payment-title')
        var payment_desc = $('.payment-desc')
        var voucher_box = $('.voucher-box')
        var voucher_box_pending = $('.voucher-box-pending')

        $(function() {
            const orderId = localStorage.getItem('orderid')

            getVoucher(orderId)
        })
        $('#copy-btn').click(function() {
            const voucherCode = $('.voucher-text').text();
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

        function getVoucher(orderId) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch('{{ route('voucherDetails') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken, // Add CSRF token to the headers
                    },
                    body: JSON.stringify({
                        orderid: orderId
                    })
                })
                .then(async (response) => {
                    const result = await response.json();
                    statePayment(result, !result.error)
                })
                .catch(error => console.error('Error:', error));
        }

        function statePayment(data, paid = false) {
            payment_title.show()
            payment_desc.show()
            if (paid) {
                pending_logo.hide()
                success_logo.show()
                payment_title.text('Payment successfull!')
                payment_desc.text('Kamu telah berhasil membeli voucher! 🎉 Berikut detail vouchernya.')
                voucher_box.show()
                $('#copy-btn').show()
                $('.voucher-text').text(data.data.code)
                $('.voucher-orderid').text(data.data.order_id)
                $('.voucher-desc').text(data.data.description)
                voucher_box_pending.hide()
            } else {
                $('.voucher-desc').text(data.data.description)
                $('.voucher-orderid').append(data.data.order_id)
                voucher_box_pending.show()
                payment_title.text('Payment on process!')
                payment_desc.text('Proses pembayaran sedang berlangsung, silahkan refresh halaman ini.')
                pending_logo.show()
                success_logo.hide()
                $('#copy-btn').hide()
            }

        }
    </script>
@endpush
