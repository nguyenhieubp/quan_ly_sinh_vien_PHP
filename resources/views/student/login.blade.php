<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập Sinh viên - Hệ thống Quản lý Đào tạo</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-student: #6366f1;
            --brand-student-hover: #4f46e5;
            --bg-glass: rgba(255, 255, 255, 0.85);
            --text-main: #1e293b;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
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
            background: #4f46e5 url('/images/student-login-bg.png') center center / cover no-repeat;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 4rem;
        }

        .visual-side::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.8), rgba(99, 102, 241, 0.4));
        }

        .visual-content {
            position: relative;
            z-index: 10;
            color: white;
            max-width: 500px;
        }

        .visual-content h1 {
            font-size: 3rem;
            font-weight: 800;
            line-height: 1.1;
            margin-bottom: 1.5rem;
            text-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .visual-content p {
            font-size: 1.1rem;
            opacity: 0.9;
            line-height: 1.6;
            margin-bottom: 2rem;
        }

        .feature-tag {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            padding: 10px 20px;
            border-radius: 50px;
            font-weight: 600;
            border: 1px solid rgba(255,255,255,0.2);
            font-size: 0.9rem;
        }

        /* Right Form Side */
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
        }

        .logo-area {
            margin-bottom: 2.5rem;
        }

        .logo-area h2 {
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        .logo-area p {
            color: #64748b;
            font-size: 0.95rem;
            margin-top: 0.5rem;
        }

        .form-group {
            margin-bottom: 1.25rem;
        }

        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 700;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .input-wrapper {
            position: relative;
        }

        .input-wrapper i {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            width: 18px;
        }

        .input-wrapper input {
            width: 100%;
            padding: 12px 14px 12px 42px;
            border-radius: 12px;
            border: 1.5px solid #e2e8f0;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s;
            outline: none;
        }

        .input-wrapper input:focus {
            border-color: var(--brand-student);
            box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        }

        .login-btn {
            width: 100%;
            padding: 14px;
            background: var(--brand-student);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-top: 1rem;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
        }

        .login-btn:hover {
            background: var(--brand-student-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(99, 102, 241, 0.35);
        }

        .error-badge {
            background: #fff1f2;
            color: #e11d48;
            padding: 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #ffe4e6;
        }

        .footer-text {
            text-align: center;
            margin-top: 2rem;
            color: #94a3b8;
            font-size: 0.85rem;
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
                    <i data-lucide="graduation-cap" style="width: 18px;"></i>
                    Cổng Thông Tin Sinh Viên
                </div>
                <h1 style="margin-top: 1.5rem;">Kiến tạo tương lai từ hôm nay.</h1>
                <p>Theo dõi lịch học, cập nhật thông tin cá nhân và quản lý kết quả học tập một cách thông minh và hiệu quả.</p>
                <div style="display: flex; gap: 2rem; margin-top: 3rem;">
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 800;">100%</div>
                        <div style="font-size: 0.8rem; opacity: 0.7;">Bảo mật</div>
                    </div>
                    <div style="width: 1px; background: rgba(255,255,255,0.2);"></div>
                    <div>
                        <div style="font-size: 1.5rem; font-weight: 800;">24/7</div>
                        <div style="font-size: 0.8rem; opacity: 0.7;">Hỗ trợ</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Form Side -->
        <div class="form-side">
            <div class="login-box">
                <div class="logo-area">
                    <h2>Đăng nhập</h2>
                    <p>Hệ thống tự động đồng bộ hóa thông tin của bạn.</p>
                </div>

                @if($errors->any())
                    <div class="error-badge">
                        <i data-lucide="alert-circle" style="width: 18px;"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form action="{{ route('student.login') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Mã sinh viên</label>
                        <div class="input-wrapper">
                            <i data-lucide="user"></i>
                            <input type="text" name="student_code" value="{{ old('student_code') }}" placeholder="Nhập mã sinh viên của bạn" required autofocus>
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
                    </button>
                </form>

                <div class="footer-text">
                    &copy; 2024 EduSystem. Bảo mật & Chuyên nghiệp.
                </div>
            </div>
        </div>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
