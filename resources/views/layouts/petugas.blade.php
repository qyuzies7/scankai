<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Scan</title>

    <link rel="shortcut icon" href="{{ asset('assets/img/favicon.png') }}" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: #e9e9e9;
            font-family: 'Poppins', sans-serif;
        }

        .login-page {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            padding: 24px 20px;
        }

        .login-card {
            width: 400px;
            min-height: 450px;
            background: #FFF;
            border-radius: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, .3);
            padding: 36px 30px 30px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-wrap {
            text-align: center;
            margin-bottom: 22px;
        }

        .logo-wrap img {
            width: 138px;
            display: block;
            margin: 0 auto 10px;
        }

        .logo-subtitle {
            font-weight: 600;
            font-size: 16px;
            color: #111;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-control {
            width: 100%;
            border: 2px solid #e7e7e7;
            padding: 15px 20px;
            font-size: 1rem;
            border-radius: 30px;
            background: transparent;
            outline: none;
            transition: .3s;
            font-family: 'Poppins', sans-serif;
            font-weight: 400;
            color: #444;
            min-height: 54px;
        }

        .form-control::placeholder {
            font-weight: 300;
            color: #9a9a9a;
        }

        .form-control:focus {
            border-color: #342b8b;
        }

        .btn-login {
            width: 100%;
            height: 58px;
            border: none;
            border-radius: 999px;
            background: #2f2c86;
            color: white;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
            transition: background-color 0.2s ease, transform 0.2s ease;
            margin-top: 6px;
            position: relative;
        }

        .btn-login:hover {
            background: #f58220;
        }

        .btn-login:active {
            transform: scale(0.99);
        }

        .btn-login.btn-loading {
            pointer-events: none;
            opacity: .9;
        }

        .btn-login.btn-loading .btn-text {
            opacity: 0;
        }

        .btn-login.btn-loading::after {
            content: '';
            position: absolute;
            width: 18px;
            height: 18px;
            top: 50%;
            left: 50%;
            margin-top: -9px;
            margin-left: -9px;
            border-radius: 50%;
            border: 2px solid rgba(255,255,255,.45);
            border-top-color: #fff;
            animation: spin .8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .login-footer {
            margin-top: 14px;
            text-align: center;
            font-size: 14px;
            color: #a0a0a0;
        }

        .alert-error {
            background: #ffe3e3;
            color: #b00020;
            padding: 10px 12px;
            border-radius: 10px;
            margin-bottom: 14px;
            font-size: 14px;
        }

        @media (max-width: 576px) {
            .login-page {
                padding: 20px;
            }

            .login-card {
                width: 100%;
                max-width: 400px;
                min-height: 450px;
                padding: 34px 24px 26px;
            }

            .logo-wrap img {
                width: 130px;
            }

            .logo-subtitle {
                font-size: 15px;
            }

            .form-control {
                min-height: 52px;
                font-size: 14px;
                padding: 14px 18px;
            }

            .btn-login {
                height: 56px;
                font-size: 15px;
            }

            .login-footer {
                font-size: 13px;
            }
        }
    </style>

    @stack('styles')
</head>
<body>
    @yield('content')
    @stack('scripts')
</body>
</html>
