<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập QT | Hệ thống Quản lý Sinh viên</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --brand-primary: #4f46e5;
            --brand-secondary: #6366f1;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --bg-light: #f8fafc;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        h1, h2, h3 {
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: white;
            height: 100vh;
            display: flex;
            overflow: hidden;
        }

        /* Split Layout */
        .login-container {
            display: flex;
            width: 100%;
            height: 100%;
        }

        /* Left Side: Image & Branding */
        .visual-side {
            flex: 1.2;
            position: relative;
            background: #1e293b url('/images/login-bg.png') center center / cover no-repeat;
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 4rem;
            color: white;
        }

        .visual-side::after {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(to top, rgba(15, 23, 42, 0.9) 0%, rgba(15, 23, 42, 0.4) 50%, transparent 100%);
        }

        .visual-content {
            position: relative;
            z-index: 10;
            max-width: 500px;
            animation: fadeIn 0.8s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .brand-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(8px);
            padding: 0.5rem 1rem;
            border-radius: 50px;
            border: 1px solid rgba(255, 255, 255, 0.2);
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        .visual-title {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1rem;
            letter-spacing: -0.02em;
        }

        .visual-desc {
            font-size: 1.125rem;
            color: rgba(255, 255, 255, 0.8);
            line-height: 1.6;
        }

        /* Right Side: Form */
        .form-side {
            flex: 0.8;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: white;
            position: relative;
        }

        .login-box {
            width: 100%;
            max-width: 400px;
            animation: slideInRight 0.6s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .form-header {
            margin-bottom: 2.5rem;
        }

        .form-header h2 {
            font-size: 1.875rem;
            font-weight: 800;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .form-header p {
            color: var(--text-muted);
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            font-size: 0.875rem;
            font-weight: 700;
            color: var(--text-main);
            margin-bottom: 0.5rem;
        }

        .input-group {
            position: relative;
        }

        .input-group i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            color: #94a3b8;
            transition: color 0.2s;
        }

        .input-group input {
            width: 100%;
            padding: 0.875rem 1rem 0.875rem 2.75rem;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            font-size: 1rem;
            font-weight: 500;
            color: var(--text-main);
            background: var(--bg-light);
            transition: all 0.2s;
        }

        .input-group input:focus {
            outline: none;
            border-color: var(--brand-primary);
            background: white;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .input-group input:focus + i {
            color: var(--brand-primary);
        }

        .error-badge {
            background: #fef2f2;
            color: #dc2626;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            border: 1px solid #fecaca;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-submit {
            width: 100%;
            padding: 1rem;
            background: var(--brand-primary);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2);
        }

        .btn-submit:hover {
            background: var(--brand-secondary);
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(79, 70, 229, 0.3);
        }

        .footer-text {
            margin-top: 3rem;
            text-align: center;
            font-size: 0.8rem;
            color: #94a3b8;
        }

        @media (max-width: 900px) {
            .visual-side { display: none; }
            .form-side { flex: 1; }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <!-- Left Visual Side -->
        <div class="visual-side">
            <div class="visual-content">
                <div class="brand-pill">
                    <i data-lucide="graduation-cap" style="width: 16px;"></i>
                    Hệ thống Quản lý Đào tạo
                </div>
                <h1 class="visual-title">Kiến tạo tương lai giáo dục.</h1>
                <p class="visual-desc">Nền tảng quản trị thông minh giúp tối ưu hóa quy trình giảng dạy và quản lý sinh viên hiện đại.</p>
            </div>
        </div>

        <!-- Right Form Side -->
        <div class="form-side">
            <div class="login-box">
                <div class="form-header">
                    <h2>Chào mừng trở lại</h2>
                    <p>Đăng nhập tài quản trị viên để tiếp tục</p>
                </div>

                @if($errors->any())
                    <div class="error-badge">
                        <i data-lucide="alert-circle" style="width: 18px;"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="username">Tên đăng nhập</label>
                        <div class="input-group">
                            <i data-lucide="user"></i>
                            <input type="text" name="username" id="username" placeholder="Nhập tên đăng nhập..." value="{{ old('username') }}" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password">Mật khẩu</label>
                        <div class="input-group">
                            <i data-lucide="lock"></i>
                            <input type="password" name="password" id="password" placeholder="Nhập mật khẩu..." required>
                        </div>
                    </div>

                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem;">
                        <input type="checkbox" name="remember" id="remember" style="width: 18px; height: 18px; border-radius: 4px; border: 1px solid #cbd5e1; accent-color: var(--brand-primary); cursor: pointer;">
                        <label for="remember" style="margin-bottom: 0; font-weight: 500; font-size: 0.9rem; color: var(--text-muted); cursor: pointer;">Ghi nhớ đăng nhập</label>
                    </div>

                    <button type="submit" class="btn-submit">
                        Tiếp theo
                        <i data-lucide="arrow-right" style="width: 20px;"></i>
                    </button>
                </form>

                <div class="footer-text">
                    &copy; 2026 EduPortal Admin Console. Powered by Antigravity AI.
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
