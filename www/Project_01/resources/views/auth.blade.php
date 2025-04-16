<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>登入頁面</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            background: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            background: #fff;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
            width: 360px;
        }

        h2 {
            margin-bottom: 20px;
            color: #333;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 12px;
            border: 1px solid #ccc;
            border-radius: 8px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #3498db;
            border: none;
            border-radius: 8px;
            color: white;
            font-weight: bold;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #2980b9;
        }

        .message {
            margin-bottom: 12px;
            color: red;
            font-size: 14px;
            text-align: center;
        }

        .success {
            color: green;
        }

        .section {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="section">
            <h2>登入</h2>
            @if(session('error')) <p class="message">{{ session('error') }}</p> @endif
            <form method="POST" action="/login">
                @csrf
                <input type="text" name="username" required placeholder="帳號">
                <input type="password" name="password" required placeholder="密碼">
                <button type="submit">登入</button>
            </form>
        </div>

        <div class="section">
            <h2>註冊</h2>
            @if(session('success')) <p class="message success">{{ session('success') }}</p> @endif
            <form method="POST" action="/register">
                @csrf
                <input type="text" name="username" required placeholder="帳號">
                <input type="password" name="password" required placeholder="密碼">
                <button type="submit">註冊</button>
            </form>
        </div>
    </div>
</body>
</html>

