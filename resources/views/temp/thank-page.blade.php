<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Arial', sans-serif;
        }

        .thank-you-container {
            max-width: 600px;
            margin: 50px auto;
            text-align: center;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }

        .thank-you-container h1 {
            color: #3a7bd5;
            font-size: 2.5rem;
            margin-bottom: 15px;
        }

        .thank-you-container p {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 20px;
        }

        .thank-you-container .btn-primary {
            background: #3a7bd5;
            border: none;
            border-radius: 8px;
            padding: 12px 20px;
            font-size: 1rem;
        }

        .thank-you-container .btn-primary:hover {
            background: #00d2ff;
        }
    </style>
</head>

<body>
    <div class="thank-you-container">
        <div class="img ">

            <?xml version="1.0" encoding="utf-8"?>

            <!-- Uploaded to: SVG Repo, www.svgrepo.com, Generator: SVG Repo Mixer Tools -->
            <svg width="300px" height="300px" viewBox="0 -9 58 58" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0_1503_2374)">
                    <g clip-path="url(#clip1_1503_2374)">
                        <path
                            d="M3.5614 0.5H54.4386C56.1256 0.5 57.5 1.87958 57.5 3.58974V36.4103C57.5 38.1204 56.1256 39.5 54.4386 39.5H3.5614C1.87437 39.5 0.5 38.1204 0.5 36.4103V3.58974C0.5 1.87958 1.87437 0.5 3.5614 0.5Z"
                            fill="white" stroke="#F3F3F3" />
                        <path d="M46 8H12V32H46V8Z" fill="#E7F3F9" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M25 8H24V17.7639C23.4692 17.2889 22.7684 17 22 17C20.3431 17 19 18.3431 19 20C19 20.7684 19.2889 21.4692 19.7639 22H12V23H22H24V32H25V23H26.5H46V22H28.5002C28.814 21.5822 29 21.0628 29 20.5C29 19.1193 27.8807 18 26.5 18C25.9372 18 25.4178 18.186 25 18.4998V8ZM25 20.5V22H26.5C27.3284 22 28 21.3284 28 20.5C28 19.6716 27.3284 19 26.5 19C25.6716 19 25 19.6716 25 20.5ZM24 20.5V20C24 18.8954 23.1046 18 22 18C20.8954 18 20 18.8954 20 20C20 21.1046 20.8954 22 22 22H24V20.5Z"
                            fill="#00689D" />
                    </g>
                </g>
                <defs>
                    <clipPath id="clip0_1503_2374">
                        <rect width="58" height="40" fill="white" />
                    </clipPath>
                    <clipPath id="clip1_1503_2374">
                        <rect width="58" height="40" fill="white" />
                    </clipPath>
                </defs>
            </svg>
        </div>
        <h1>Thank You!</h1>
        <p>Your purchase was successful. Our team will contact you shortly.</p>
        <p>If needed, feel free to reach out with your order details.</p>
        <a href="{{ route('index') }}" class="btn btn-primary">Back to Home</a>
    </div>

    <script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
</body>

</html>
