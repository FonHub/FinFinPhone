<!DOCTYPE html>
<html lang="en" class="light">

<head>
    <title>เข้าสู่ระบบหลังบ้าน</title>
    @include('admin/inc_header')
    <style>
        body {
            min-height: 100vh;
            background:
                radial-gradient(circle at top left, rgba(59, 130, 246, 0.16), transparent 30%),
                radial-gradient(circle at bottom right, rgba(14, 165, 233, 0.14), transparent 25%),
                linear-gradient(135deg, #0f172a 0%, #111827 45%, #1e293b 100%);
        }

        .admin-login-wrapper {
            min-height: 100vh;
            padding: 32px 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-login-shell {
            width: 100%;
            max-width: 1180px;
            min-height: 700px;
            border-radius: 32px;
            overflow: hidden;
            display: grid;
            grid-template-columns: 1.05fr 0.95fr;
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(10px);
            box-shadow:
                0 20px 60px rgba(0, 0, 0, 0.28),
                inset 0 1px 0 rgba(255, 255, 255, 0.08);
            border: 1px solid rgba(255, 255, 255, 0.10);
        }

        .admin-login-left {
            position: relative;
            padding: 56px;
            color: #fff;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            background:
                linear-gradient(180deg, rgba(255, 255, 255, 0.03) 0%, rgba(255, 255, 255, 0.01) 100%);
        }

        .admin-login-left::before {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 18% 20%, rgba(96, 165, 250, 0.20), transparent 24%),
                radial-gradient(circle at 85% 80%, rgba(14, 165, 233, 0.16), transparent 22%);
            pointer-events: none;
        }

        .admin-login-left-content,
        .admin-login-left-footer {
            position: relative;
            z-index: 2;
        }

        .admin-login-brand {
            display: inline-flex;
            align-items: center;
            gap: 14px;
            color: #fff;
            text-decoration: none;
            font-weight: 700;
            font-size: 1.15rem;
            letter-spacing: 0.02em;
        }

        .admin-login-brand img {
            width: 42px;
            height: 42px;
            object-fit: contain;
        }

        .admin-login-title {
            font-size: clamp(2.4rem, 4vw, 4.2rem);
            line-height: 1.06;
            font-weight: 800;
            letter-spacing: -0.03em;
            margin: 0;
            max-width: 520px;
        }

        .admin-login-subtitle {
            margin-top: 22px;
            max-width: 430px;
            font-size: 1rem;
            line-height: 1.8;
            color: rgba(255, 255, 255, 0.72);
        }

        .admin-login-illustration {
            width: min(100%, 470px);
            max-height: 280px;
            object-fit: contain;
            margin-left: auto;
            filter: drop-shadow(0 14px 40px rgba(0, 0, 0, 0.28));
        }

        .admin-login-right {
            background: #ffffff;
            padding: 56px 52px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .admin-login-form-wrap {
            width: 100%;
            max-width: 420px;
        }

        .admin-login-form-head {
            margin-bottom: 28px;
        }

        .admin-login-form-title {
            font-size: 2rem;
            line-height: 1.15;
            font-weight: 800;
            color: #0f172a;
            margin: 0 0 10px 0;
            letter-spacing: -0.02em;
        }

        .admin-login-form-desc {
            color: #64748b;
            font-size: 0.98rem;
            line-height: 1.7;
            margin: 0;
        }

        .admin-login-mobile-brand {
            display: none;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 24px;
            text-decoration: none;
            color: #0f172a;
            font-weight: 700;
            font-size: 1.1rem;
        }

        .admin-login-mobile-brand img {
            width: 38px;
            height: 38px;
            object-fit: contain;
        }

        .admin-login-label {
            display: block;
            font-size: 0.92rem;
            font-weight: 600;
            color: #334155;
            margin-bottom: 8px;
        }

        .admin-login-input {
            height: 54px;
            border-radius: 16px !important;
            border: 1px solid #dbe3ee !important;
            background: #f8fafc !important;
            padding: 0 18px !important;
            font-size: 0.98rem !important;
            color: #0f172a !important;
            box-shadow: none !important;
            transition: all .18s ease;
        }

        .admin-login-input:focus {
            background: #fff !important;
            border-color: #60a5fa !important;
            box-shadow: 0 0 0 4px rgba(96, 165, 250, 0.14) !important;
        }

        .admin-login-checkbox-row {
            margin-top: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
        }

        .admin-login-checkbox-label {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            font-size: 0.92rem;
            color: #475569;
            cursor: pointer;
            user-select: none;
        }

        .admin-login-submit {
            margin-top: 28px;
            height: 54px;
            border-radius: 16px !important;
            font-size: 1rem !important;
            font-weight: 700 !important;
            letter-spacing: 0.01em;
            box-shadow: 0 10px 24px rgba(37, 99, 235, 0.22);
        }

        .admin-login-note {
            margin-top: 18px;
            text-align: center;
            font-size: 0.88rem;
            color: #94a3b8;
        }

        .alert {
            border-radius: 16px !important;
        }

        @media (max-width: 1279px) {
            .admin-login-shell {
                grid-template-columns: 1fr;
                max-width: 520px;
                min-height: auto;
            }

            .admin-login-left {
                display: none;
            }

            .admin-login-right {
                padding: 36px 28px;
            }

            .admin-login-mobile-brand {
                display: inline-flex;
            }
        }

        @media (max-width: 640px) {
            .admin-login-wrapper {
                padding: 16px;
            }

            .admin-login-right {
                padding: 28px 20px;
            }

            .admin-login-form-title {
                font-size: 1.7rem;
            }
        }
    </style>
</head>

<body>
    <div class="admin-login-wrapper">
        <div class="admin-login-shell">
            <div class="admin-login-left">
                <div class="admin-login-left-content">
                    <a href="{{ url('/') }}" class="admin-login-brand">
                        <img alt="Logo" src="{{ asset('dist/images/logo.svg') }}">
                        <span>Admin Cashkub</span>
                    </a>

                    <div class="mt-20">
                        {{-- <h1 class="admin-login-title">
                            จัดการระบบ
                            <br>
                            ได้อย่างมั่นใจ
                        </h1> --}}

                        <p class="admin-login-subtitle">
                            เข้าสู่ระบบเพื่อใช้งานพื้นที่จัดการข้อมูลสำหรับผู้ดูแลระบบ
                        </p>
                    </div>
                </div>

                <div class="admin-login-left-footer">
                    <img alt="Illustration"
                        class="admin-login-illustration"
                        src="{{ asset('dist/images/illustration.svg') }}">
                </div>
            </div>

            <div class="admin-login-right">
                <div class="admin-login-form-wrap">
                    <a href="{{ url('/') }}" class="admin-login-mobile-brand">
                        <img alt="Logo" src="{{ asset('dist/images/logo.svg') }}">
                        <span>Admin Panel</span>
                    </a>

                    <div class="admin-login-form-head">
                        <h2 class="admin-login-form-title">เข้าสู่ระบบ</h2>
                        <p class="admin-login-form-desc">
                            กรอกอีเมลและรหัสผ่านเพื่อเข้าใช้งานระบบจัดการหลังบ้าน
                        </p>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            {{ $errors->first() }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.login.submit') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="admin-login-label">อีเมล</label>
                            <input type="email"
                                name="email"
                                class="form-control admin-login-input"
                                placeholder="กรอกอีเมล"
                                value="{{ old('email') }}">
                        </div>

                        <div class="mb-2">
                            <label class="admin-login-label">รหัสผ่าน</label>
                            <input type="password"
                                name="password"
                                class="form-control admin-login-input"
                                placeholder="กรอกรหัสผ่าน">
                        </div>

                        <div class="admin-login-checkbox-row">
                            <label class="admin-login-checkbox-label" for="remember-me">
                                <input id="remember-me"
                                    type="checkbox"
                                    name="remember"
                                    class="form-check-input border">
                                <span>จดจำการเข้าสู่ระบบ</span>
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-full admin-login-submit">
                            เข้าสู่ระบบ
                        </button>

                        <div class="admin-login-note">
                            Protected administration area
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>