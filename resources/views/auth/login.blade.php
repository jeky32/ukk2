<!DOCTYPE html>
<html>
<head>
    <title>Login - Manajemen Proyek</title>
</head>
<body>
    <h1>Login</h1>
    @if(session('error'))
        <p style="color:red">{{ session('error') }}</p>
    @endif
    <form method="POST" action="{{ route('login.post') }}">
        @csrf
        <label>Username:</label>
        <input type="text" name="username" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
