<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Check Your Invoice & Claim Voucher</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .invoice-card {
            background-color: #ffffff;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-top: 30px;
        }

        .form-control:focus {
            border-color: #6610f2;
            box-shadow: 0 0 0 0.25rem rgba(102, 16, 242, 0.25);
        }

        .btn-primary {
            background-color: #6610f2;
            border-color: #6610f2;
            transition: background-color 0.3s ease, border-color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #5a0ec7;
            border-color: #5a0ec7;
        }

        .result-section {
            margin-top: 20px;
            padding: 20px;
            border-radius: 10px;
            background-color: #e9ecef;
            display: none;
            /* Hidden by default */
        }

        .voucher-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .voucher-fail {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #6610f2;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            margin: 0 auto 15px auto;
            display: none;
            /* Hidden by default */
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .alert {
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="invoice-card text-center">
                    <h2 class="mb-4 text-primary">Voucher Checker 🧾</h2>
                    <p class="text-muted">Silahkan masukkan kode invoice atau kode pembayaran dibawah untuk melihat
                        status dan klaim voucher wifi anda.
                    </p>
                    <form id="invoiceCheckForm">
                        <div class="mb-3">
                            <label for="invoiceNumber" class="form-label visually-hidden">Invoice Number</label>
                            <input type="text" class="form-control form-control-lg text-center" id="invoiceNumber"
                                placeholder="e.g., INV-2025-001" required>
                            <div class="invalid-feedback">
                                Please enter a valid invoice number.
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-lg w-100">Check Invoice & Claim</button>
                    </form>

                    <div class="loader mt-4" id="loader"></div>

                    <div id="resultSection" class="result-section">
                        <h4 id="resultTitle"></h4>
                        <p id="resultMessage"></p>
                        <div id="voucherCode" class="text-success fw-bold fs-4"></div>
                        <button id="copyVoucherBtn" class="btn btn-outline-secondary btn-sm mt-3 d-none">Copy Voucher
                            Code</button>
                    </div>

                    <div id="errorMessage" class="alert alert-danger d-none" role="alert">
                        <span id="errorText"></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('invoiceCheckForm');
            const invoiceNumberInput = document.getElementById('invoiceNumber');
            const loader = document.getElementById('loader');
            const resultSection = document.getElementById('resultSection');
            const resultTitle = document.getElementById('resultTitle');
            const resultMessage = document.getElementById('resultMessage');
            const voucherCodeDisplay = document.getElementById('voucherCode');
            const copyVoucherBtn = document.getElementById('copyVoucherBtn');
            const errorMessageDiv = document.getElementById('errorMessage');
            const errorTextSpan = document.getElementById('errorText');

            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevent default form submission

                // Reset previous states
                resultSection.style.display = 'none';
                errorMessageDiv.classList.add('d-none');
                invoiceNumberInput.classList.remove('is-invalid');
                copyVoucherBtn.classList.add('d-none');
                resultSection.classList.remove('voucher-success', 'voucher-fail');

                const invoiceNumber = invoiceNumberInput.value.trim();

                if (!invoiceNumber) {
                    invoiceNumberInput.classList.add('is-invalid');
                    return;
                }

                loader.style.display = 'block'; // Show loader

                // Simulate API call
                fetch('/api/check-invoice', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            invoice_number: invoiceNumber
                        })
                    })
                    .then(response => {
                        loader.style.display = 'none'; // Hide loader
                        if (!response.ok) {
                            return response.json().then(err => {
                                throw err;
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        resultSection.style.display = 'block';
                        if (data.status === 'success') {
                            resultSection.classList.add('voucher-success');
                            resultTitle.textContent = '🎉 Voucher Claimed Successfully!';
                            resultMessage.textContent = data.message ||
                                'Your invoice is valid and a voucher has been issued.';
                            voucherCodeDisplay.textContent = data.voucher_code;
                            copyVoucherBtn.classList.remove('d-none');
                        } else if (data.status === 'fail') {
                            resultSection.classList.add('voucher-fail');
                            resultTitle.textContent = '❌ Voucher Not Available';
                            resultMessage.textContent = data.message ||
                                'This invoice is not eligible for a voucher, or the voucher has already been claimed.';
                            voucherCodeDisplay.textContent = ''; // Clear voucher code
                            copyVoucherBtn.classList.add('d-none');
                        } else {
                            // Fallback for unexpected status
                            resultSection.classList.add('voucher-fail');
                            resultTitle.textContent = '⚠️ Something Went Wrong';
                            resultMessage.textContent = data.message ||
                                'An unexpected error occurred. Please try again.';
                            voucherCodeDisplay.textContent = '';
                            copyVoucherBtn.classList.add('d-none');
                        }
                    })
                    .catch(error => {
                        loader.style.display = 'none'; // Hide loader
                        errorMessageDiv.classList.remove('d-none');
                        errorTextSpan.textContent = error.message ||
                            'An error occurred during the request. Please check your network connection and try again.';
                        console.error('Error:', error);
                    });
            });

            copyVoucherBtn.addEventListener('click', function() {
                const voucherCode = voucherCodeDisplay.textContent;
                navigator.clipboard.writeText(voucherCode).then(() => {
                    const originalText = copyVoucherBtn.textContent;
                    copyVoucherBtn.textContent = 'Copied!';
                    setTimeout(() => {
                        copyVoucherBtn.textContent = originalText;
                    }, 2000);
                }).catch(err => {
                    console.error('Failed to copy text: ', err);
                    alert('Failed to copy voucher code. Please copy it manually.');
                });
            });
        });
    </script>
</body>

</html>
