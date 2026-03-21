<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Sinh viên - EduCore SV</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-primary: #6366f1;
            --brand-secondary: #4f46e5;
            --text-main: #0f172a;
            --text-muted: #64748b;
            --border-light: #f1f5f9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, .brand-font {
            font-family: 'Outfit', sans-serif;
        }

        body, html {
            height: 100%;
            overflow: hidden;
            background: #f8fafc;
        }

        .login-container {
            display: flex;
            width: 100%;
            height: 100%;
        }

        /* Left Visual Side */
        .visual-side {
            flex: 1.2;
            position: relative;
            background: #4f46e5 url('{{ asset('brain/05d2aecb-26a3-4b92-9b1c-dc46472896dd/student_login_v2_bg_1774108857389.png') }}') center center / cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem;
        }

        /* Fallback if image not found in public path yet */
        .visual-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.9), rgba(79, 70, 229, 0.7));
            backdrop-filter: blur(2px);
        }

        .visual-content {
            position: relative;
            z-index: 10;
            color: white;
            max-width: 520px;
        }

        .feature-tag {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.1);
            backdrop-filter: blur(12px);
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 700;
            border: 1px solid rgba(255,255,255,0.2);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 2rem;
        }

        .visual-content h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            letter-spacing: -1.5px;
        }

        .visual-content p {
            font-size: 1.05rem;
            opacity: 0.9;
            line-height: 1.6;
            font-weight: 500;
        }

        /* Right Form Side */
        .form-side {
            flex: 0.8;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: white;
        }

        .login-box {
            width: 100%;
            max-width: 380px;
        }

        .logo-area {
            margin-bottom: 2.5rem;
        }

        .logo-icon {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 16px rgba(99, 102, 241, 0.2);
        }

        .logo-area h2 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        .logo-area p {
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 500;
            margin-top: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.7rem;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            width: 16px;
            stroke-width: 2.5px;
        }

        .input-wrapper input {
            width: 100%;
            padding: 10px 14px 10px 42px;
            border-radius: 10px;
            border: 1px solid var(--border-light);
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text-main);
            transition: all 0.2s;
            outline: none;
        }

        .input-wrapper input:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.05);
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: var(--brand-primary);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .login-btn:hover {
            background: var(--brand-secondary);
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(99, 102, 241, 0.3);
        }

        .error-badge {
            background: #fff1f2;
            color: #e11d48;
            padding: 10px 12px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 8px;
            border: 1px solid #ffe4e6;
        }

        .footer-text {
            text-align: center;
            margin-top: 3rem;
            color: var(--text-muted);
            font-size: 0.75rem;
            font-weight: 500;
        }

        @media (max-width: 1024px) {
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
                <div class="feature-tag">
                    <i data-lucide="sparkles" style="width: 14px;"></i>
                    Cổng Thông Tin Sinh Viên
                </div>
                <h1 class="brand-font">Chinh phục tri thức.<br>Làm chủ tương lai.</h1>
                <p>Cập nhật kết quả học tập, lịch học chi tiết và kết nối trực tiếp với đội ngũ giảng viên chuyên nghiệp.</p>
                
                <div style="display: flex; gap: 2.5rem; margin-top: 3rem;">
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 800; font-family: 'Outfit';">100%</div>
                        <div style="font-size: 0.7rem; opacity: 0.7; font-weight: 700; text-transform: uppercase;">Bảo mật</div>
                    </div>
                    <div style="width: 1px; background: rgba(255,255,255,0.2);"></div>
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 800; font-family: 'Outfit';">24/7</div>
                        <div style="font-size: 0.7rem; opacity: 0.7; font-weight: 700; text-transform: uppercase;">Truy cập</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Form Side -->
        <div class="form-side">
            <div class="login-box">
                <div class="logo-area">
                    <div class="logo-icon">
                        <i data-lucide="graduation-cap" style="color: white; width: 24px; stroke-width: 3px;"></i>
                    </div>
                    <h2>Chào mừng bạn!</h2>
                    <p>Vui lòng đăng nhập để tiếp tục.</p>
                </div>

                @if($errors->any())
                    <div class="error-badge">
                        <i data-lucide="alert-circle" style="width: 16px;"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('student.login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Mã sinh viên</label>
                        <div class="input-wrapper">
                            <i data-lucide="user"></i>
                            <input type="text" name="student_code" value="{{ old('student_code') }}" placeholder="VD: SV001" required autofocus>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 2rem;">
                        <label>Mật khẩu</label>
                        <div class="input-wrapper">
                            <i data-lucide="lock"></i>
                            <input type="password" name="password" placeholder="••••••••" required>
                        </div>
                    </div>

                    <button type="submit" class="login-btn">
                        Truy cập hệ thống
                        <i data-lucide="arrow-right" style="width: 16px;"></i>
                    </button>
                </form>

                <div class="footer-text">
                    &copy; 2024 EduCore System. Designed for Excellence.
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
