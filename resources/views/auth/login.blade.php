<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
</head>
<body>
    <div style="max-width: 400px; margin: 50px auto; padding: 20px; border: 1px solid #ccc;">
        <h2>Login</h2>
        
        @if(session('error'))
            <div style="color: red; margin-bottom: 10px;">{{ session('error') }}</div>
        @endif
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <div style="margin-bottom: 15px;">
                <label>Email:</label><br>
                <input type="email" name="email" required style="width: 100%; padding: 8px;">
            </div>
            
            <div style="margin-bottom: 15px;">
                <label>Password:</label><br>
                <input type="password" name="password" required style="width: 100%; padding: 8px;">
            </div>
            
            <button type="submit" style="width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer;">
                Login
            </button>
        </form>
    </div>
</body>
</html>