<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Cổng Thông Tin Sinh Viên</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-primary: #6366f1;
            --brand-primary-light: #eff6ff;
            --brand-secondary: #4f46e5;
            --sidebar-bg: #1e1b4b; /* Dark Indigo */
            --sidebar-text: #e2e8f0;
            --sidebar-hover: rgba(255, 255, 255, 0.1);
            --content-bg: #f8fafc;
            --text-title: #0f172a;
            --text-body: #64748b;
            --border-color: #f1f5f9;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        body {
            background-color: var(--content-bg);
            color: var(--text-body);
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar Styles */
        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            color: var(--sidebar-text);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
        }

        .sidebar-header {
            padding: 2rem;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .logo-box {
            width: 36px;
            height: 36px;
            background: var(--brand-primary);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
        }

        .sidebar-nav {
            padding: 1.5rem 1rem;
            flex-grow: 1;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 12px;
            margin-bottom: 6px;
            font-weight: 600;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .nav-item:hover {
            background: var(--sidebar-hover);
            color: white;
        }

        .nav-item.active {
            background: var(--brand-primary);
            color: white;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);
        }

        .nav-item i {
            width: 18px;
            height: 18px;
        }

        /* Main Content area */
        .main-wrapper {
            margin-left: 260px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .top-bar {
            height: 70px;
            background: white;
            border-bottom: 1px solid var(--border-color);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .content-area {
            padding: 2rem;
        }

        .profile-chip {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 6px 6px 6px 12px;
            background: #f8fafc;
            border-radius: 50px;
            border: 1px solid var(--border-color);
        }

        .avatar {
            width: 32px;
            height: 32px;
            background: var(--brand-primary);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.75rem;
        }

        /* Common Components */
        .card {
            background: white;
            border-radius: 16px;
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.02);
            margin-bottom: 1.5rem;
        }

        .btn {
            padding: 10px 20px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            display: inline-flex;
            align-items: center; gap: 8px;
            text-decoration: none;
        }

        .btn-primary { background: var(--brand-primary); color: white; }
        .btn-primary:hover { background: var(--brand-secondary); transform: translateY(-1px); }

        .hidden { display: none !important; }

        @media (max-width: 768px) {
            .sidebar { width: 80px; }
            .sidebar-header span, .nav-item span { display: none; }
            .main-wrapper { margin-left: 80px; }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo-box">
                <i data-lucide="graduation-cap" style="color: white; width: 20px;"></i>
            </div>
            <span style="font-weight: 800; font-size: 1.1rem; letter-spacing: -0.5px;">Student Portal</span>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('student.dashboard') }}" class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i> Bảng điều khiển
            </a>
            <a href="{{ route('student.detailed-schedule') }}" class="nav-item {{ request()->routeIs('student.detailed-schedule') ? 'active' : '' }}">
                <i data-lucide="calendar-days"></i> Lịch học chi tiết
            </a>
            <a href="{{ route('student.schedule') }}" class="nav-item {{ request()->routeIs('student.schedule') ? 'active' : '' }}">
                <i data-lucide="calendar"></i> Thời khóa biểu
            </a>
            <a href="{{ route('student.grades') }}" class="nav-item {{ Route::is('student.grades') ? 'active' : '' }}">
                <i data-lucide="award"></i>
                <span>Kết quả học tập</span>
            </a>
            <a href="{{ route('student.profile') }}" class="nav-item {{ Route::is('student.profile') ? 'active' : '' }}">
                <i data-lucide="user-cog"></i>
                <span>Thông tin cá nhân</span>
            </a>
            <a href="{{ route('student.support') }}" class="nav-item {{ Route::is('student.support') ? 'active' : '' }}">
                <i data-lucide="help-circle"></i>
                <span>Hỗ trợ</span>
            </a>

            <div style="margin-top: auto; padding-top: 2rem;">
                <form action="{{ route('student.logout') }}" method="POST" id="logout-form">
                    @csrf
                    <button type="submit" class="nav-item" style="width: 100%; border: none; background: rgba(239, 68, 68, 0.1); color: #f87171; cursor: pointer; text-align: left;">
                        <i data-lucide="log-out"></i>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <div class="main-wrapper">
        <header class="top-bar">
            <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--text-title);">@yield('title')</h2>
            
            <div class="user-actions" style="display: flex; gap: 1.5rem; align-items: center;">
                <div class="profile-chip">
                    <span style="font-size: 0.85rem; font-weight: 700; color: var(--text-title);">{{ Auth::guard('student')->user()->name }}</span>
                    <div class="avatar">{{ strtoupper(substr(Auth::guard('student')->user()->name, 0, 2)) }}</div>
                </div>
            </div>
        </header>

        <main class="content-area">
            @if(session('success'))
                <div style="background: #ecfdf5; border: 1px solid #d1fae5; color: #065f46; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 0.9rem;">
                    <i data-lucide="check-circle" style="width: 18px;"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 0.9rem;">
                    <i data-lucide="alert-circle" style="width: 18px;"></i>
                    {{ session('error') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
