<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment Error</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .error-container {
            max-width: 600px;
            margin: 50px auto;
            text-align: center;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .error-container h1 {
            color: #dc3545;
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .error-container p {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 20px;
        }

        .error-container .btn-primary {
            background: #dc3545;
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            font-size: 1rem;
        }

        .error-container .btn-primary:hover {
            background: #bb2d3b;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <h1>Payment Failed</h1>
        <p>We're sorry, but your payment could not be processed.</p>
        <p>Please try again or contact support if the issue persists.</p>
        <a href="{{ route('index') }}" class="btn btn-primary">Back to Home</a>
        <a href="{{ route('index', '#contact') }}" class="btn btn-outline-secondary ms-2">Contact Support</a>
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
