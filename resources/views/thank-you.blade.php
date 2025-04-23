<!DOCTYPE html>
<html>
<head>
    <title>Thank You!</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .thank-you {
            text-align: center;
            padding: 5rem 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="thank-you">
            <h1 class="display-4">🎉 Thank You!</h1>
            <p class="lead">Your responses have been recorded.</p>
            <a href="/welcome" class="btn btn-primary mt-3">Submit Another Response</a>
        </div>
    </div>
    <script>
        // Redirect back timer
        setTimeout(function() {
            window.location.href = "/welcome";
        }, 5000);
    </script>
</body>
</html>