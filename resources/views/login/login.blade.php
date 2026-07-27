<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    @include('inc_header')
    <title>Login</title>
    <style>
        /* เต็มหน้าจอ */
        html,
        body {
            height: 100%;
            margin: 0;
            display: flex;
            justify-content: center;
            /* จัด horizontal */
            align-items: center;
            /* จัด vertical */
            background-color: #f5f5f5;
            /* พื้นหลังรอบ ๆ กล่อง */
        }

        /* กล่อง login */
        .login-box {
            width: 100%;
            max-width: 400px;
            background: #ffffff;
            /* กล่องเป็นสีขาว */
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .login-box h1 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
            color: #252627;
            text-align: center;
        }

        .form-control {
            width: 100%;
            margin-bottom: 15px;
            padding: 12px 15px;
            font-size: 16px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-control:focus {
            outline: none;
            border-color: #252627;
            box-shadow: 0 0 5px rgba(52, 56, 60, 0.5);
        }

        .btn-primary {
            background-color: #2d2f32;
            color: #fff;
            border: none;
            width: 100%;
            padding: 12px;
            font-size: 18px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn-primary:hover {
            background-color: #1f2022;
        }

        .alert {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            color: #721c24;
            background-color: #f8d7da;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h1>เข้าสู่ระบบ</h1>

        @if ($errors->any())
            <div class="alert">
                {{ $errors->first('error') }}
            </div>
        @endif

        <form method="POST" action="{{ url('/login-user') }}">
            @csrf
            <input type="text" class="form-control" placeholder="email" name="email" required>
            <input type="password" class="form-control" placeholder="รหัสผ่าน" name="password" required>
            <button type="submit" class="btn-primary">เข้าสู่ระบบ</button>
        </form>
    </div>
</body>

</html>
