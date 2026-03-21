<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Giảng viên - Hệ thống Quản lý Đào tạo</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-teacher: #10b981;
            --brand-teacher-hover: #059669;
            --text-title: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --border-light: #f1f5f9;
            --radius-main: 12px;
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
            flex: 1.25;
            position: relative;
            background: #059669;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem;
            overflow: hidden;
        }

        .visual-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at top right, rgba(16, 185, 129, 0.4), transparent),
                        linear-gradient(135deg, #064e3b 0%, #10b981 100%);
            z-index: 1;
        }

        /* Abstract decorative circles */
        .visual-side::after {
            content: '';
            position: absolute;
            width: 400px;
            height: 400px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            top: -100px;
            left: -100px;
            z-index: 2;
        }

        .visual-content {
            position: relative;
            z-index: 10;
            color: white;
            max-width: 480px;
        }

        .visual-content h1 {
            font-size: 2.75rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.25rem;
            letter-spacing: -1px;
        }

        .visual-content p {
            font-size: 1rem;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 2rem;
            font-weight: 400;
        }

        .feature-tag {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: rgba(255,255,255,0.12);
            backdrop-filter: blur(8px);
            padding: 8px 16px;
            border-radius: 50px;
            font-weight: 700;
            border: 1px solid rgba(255,255,255,0.2);
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2rem;
        }

        /* Right Form Side */
        .form-side {
            flex: 0.75;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            background: white;
            position: relative;
            box-shadow: -10px 0 50px rgba(0,0,0,0.03);
            z-index: 20;
        }

        .login-box {
            width: 100%;
            max-width: 360px;
        }

        .logo-area {
            margin-bottom: 2.5rem;
        }

        .logo-area h2 {
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--text-title);
            letter-spacing: -0.5px;
        }

        .logo-area p {
            color: var(--text-muted);
            font-size: 0.85rem;
            font-weight: 500;
            margin-top: 6px;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.75rem;
            font-weight: 800;
            color: var(--text-muted);
            text-transform: uppercase;
            margin-bottom: 8px;
            letter-spacing: 0.5px;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            width: 16px;
        }

        .input-wrapper input {
            width: 100%;
            padding: 10px 14px 10px 40px;
            border-radius: 10px;
            border: 1.5px solid #f1f5f9;
            background: #f8fafc;
            font-size: 0.9rem;
            font-weight: 600;
            transition: all 0.2s;
            outline: none;
            color: var(--text-title);
        }

        .input-wrapper input:focus {
            border-color: var(--brand-teacher);
            background: white;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.08);
        }

        .login-btn {
            width: 100%;
            padding: 12px;
            background: var(--brand-teacher);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.9rem;
            font-weight: 800;
            cursor: pointer;
            transition: all 0.3s;
            margin-top: 1rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .login-btn:hover {
            background: var(--brand-teacher-hover);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.2);
        }

        .error-badge {
            background: #fff1f2;
            color: #e11d48;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 0.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #ffe4e6;
        }

        .footer-text {
            text-align: center;
            margin-top: 2.5rem;
            color: #cbd5e1;
            font-size: 0.75rem;
            font-weight: 600;
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
                    <i data-lucide="shield-check" style="width: 14px;"></i>
                    Hệ thống Giảng viên EduCore
                </div>
                <h1>Kiến tạo tương lai, khởi nguồn tri thức.</h1>
                <p>Nền tảng quản lý học tập thông minh giúp giảng viên tối ưu hóa quy trình giảng dạy và đánh giá sinh viên.</p>
                
                <div style="display: flex; gap: 2rem; margin-top: 4rem;">
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 800;" class="brand-font">Smart</div>
                        <div style="font-size: 0.7rem; opacity: 0.7; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Management</div>
                    </div>
                    <div style="width: 1px; background: rgba(255,255,255,0.2);"></div>
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 800;" class="brand-font">Fast</div>
                        <div style="font-size: 0.7rem; opacity: 0.7; font-weight: 700; text-transform: uppercase; letter-spacing: 1px;">Evaluation</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Form Side -->
        <div class="form-side">
            <div class="login-box">
                <div class="logo-area">
                    <h2>Đăng nhập Giảng viên</h2>
                    <p>Vui lòng sử dụng tài khoản công vụ được cấp.</p>
                </div>

                @if($errors->any())
                    <div class="error-badge">
                        <i data-lucide="alert-circle" style="width: 16px;"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('teacher.login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Tài khoản Email</label>
                        <div class="input-wrapper">
                            <i data-lucide="mail"></i>
                            <input type="email" name="email" value="{{ old('email') }}" placeholder="gv_name@educore.edu.vn" required autofocus>
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
                        <span>Truy cập Cổng Giảng viên</span>
                        <i data-lucide="arrow-right" style="width: 16px;"></i>
                    </button>
                </form>

                <div class="footer-text">
                    &copy; 2024 EduCore LMS &bull; Teacher Portal v2.0
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
