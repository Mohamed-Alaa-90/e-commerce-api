<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
</head>

<body>

    <h1>Admin Login</h1>

    <form method="POST" action="{{ route('admin.login.submit') }}">
        @csrf

        <input type="email" name="email" placeholder="Email">

        <input type="password" name="password" placeholder="Password">

        <button type="submit">Login</button>
    </form>

</body>

</html>