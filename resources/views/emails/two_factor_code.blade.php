<!DOCTYPE html>
<html>
<head>
    <title>Your Two-Factor Code</title>
</head>
<body>
    <p>Your two-factor authentication code is: <strong>{{ $code }}</strong></p>
    <p>This code will expire in {{ env('TWO_FACTOR_CODE_EXPIRATION', 10) }} minutes.</p>
</body>
</html>
