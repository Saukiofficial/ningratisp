<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Payment</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eef2f6;
            font-family: 'Arial', sans-serif;
        }

        .header {
            background: linear-gradient(90deg, #1e3c72, #2a5298);
            color: white;
            padding: 40px 0;
            text-align: center;
            border-bottom: 5px solid #2a5298;
        }

        .header h1 {
            font-size: 2.8rem;
            font-weight: bold;
        }

        .header p {
            font-size: 1rem;
            font-weight: 300;
        }

        .payment-container {
            max-width: 850px;
            margin: 40px auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            padding: 40px;
        }

        .btn-custom {
            background: #1e3c72;
            border: none;
            border-radius: 10px;
            padding: 14px;
            font-size: 1.2rem;
        }

        .btn-custom:hover {
            background: #2a5298;
        }

        .form-control,
        .form-select {
            border-radius: 10px;
            border: 1px solid #ccc;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #2a5298;
        }

        .summary-instructions {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 30px;
        }

        .instructions-box,
        .summary-box {
            flex: 1;
            background-color: #f9fafc;
            border-radius: 10px;
            padding: 25px;
            border-left: 6px solid #1e3c72;
        }

        .instructions-box h5,
        .summary-box h5 {
            font-size: 1.3rem;
            color: #222;
        }

        .instructions-box p,
        .summary-box p {
            font-size: 1rem;
            margin: 6px 0;
        }

        .instructions-box strong,
        .summary-box strong {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <header class="header">
        <h1>Secure Payment</h1>
        <p>Finalize your transaction to confirm your order.</p>
    </header>

    <div class="payment-container">
        <div class="summary-instructions">
            {{-- <div class="instructions-box">
                <h5>Transfer Instructions</h5>
                <p>Transfer the total amount to one of the following accounts:</p>
                <ul>
                    <li><strong>Rizal Abul Fata</strong></li>
                    <li><strong>Mandiri Bank:</strong> 1400021389102</li>
                    <li><strong>Seabank:</strong> 901640673570</li>
                    <li><strong>ShopeePay / GoPay:</strong> +6282301148188</li>
                </ul>
            </div> --}}

            <div class="summary-box">
                <h5>Order Overview</h5>
                <p>Invoice Code: <strong>{{ $orderid }}</strong></p>
                <p>Item Name: <strong>{{ $title }}</strong></p>
                <p>Quantity: <strong>1</strong></p>
                <p>Total Amount: <strong>Rp {{ number_format($price, 0, ',', '.') }}</strong></p>
            </div>
        </div>

        <div class="container py-3">
            <p class="text-muted"><em>*Take a screenshot of this page and keep your payment proof.</em></p>
        </div>

        <form action="{{ route('createinvoice') }}" method="post">
            @csrf
            <input type="hidden" name="amount" value="{{ $price }}">
            <input type="hidden" name="orderid" value="{{ $orderid }}">
            <input type="hidden" name="title" value="{{ $title }}">
            <button class="btn btn-custom w-100">Generate Invoice</button>
        </form>
        <br>
        <a href="{{ route('index') }}" class="btn btn-outline-secondary w-100">Return to Home</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
