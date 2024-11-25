<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="public/assets/some.css">
</head>
<body>
<div class="container">
    <h1>Login</h1>
    <form action="index.php?action=login" method="POST">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" placeholder="Enter your email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" placeholder="Enter your password" required>

        <button type="submit">Login</button>
    </form>
    <p>Don't have an account? <a href="index.php?action=register">Register</a></p>
</div>
</body>
</html>
