<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Cổng Thông Tin Sinh Viên</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-primary: #6366f1;
            --brand-primary-light: #eff6ff;
            --brand-secondary: #4f46e5;
            --brand-gradient: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
            
            --sidebar-width: 240px;
            --sidebar-bg: #ffffff;
            --sidebar-text: #64748b;
            --sidebar-hover: #f1f5f9;
            --sidebar-active-bg: #eff6ff;
            --sidebar-active-text: #6366f1;
            
            --content-bg: #f8fafc;
            --text-title: #0f172a;
            --text-body: #334155;
            --text-muted: #64748b;
            --border-light: #f1f5f9;
            --radius-main: 12px;
            
            --fs-base: 0.9375rem;
            --fs-sm: 0.8125rem;
            --fs-xs: 0.75rem;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        h1, h2, h3, h4, .brand-font {
            font-family: 'Outfit', sans-serif;
        }

        body {
            background-color: var(--content-bg);
            color: var(--text-body);
            display: flex;
            min-height: 100vh;
            font-size: var(--fs-base);
        }

        /* Sidebar Styles */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-light);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-header {
            padding: 1.5rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-box {
            width: 32px;
            height: 32px;
            background: var(--brand-gradient);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
            flex-shrink: 0;
        }

        .sidebar-nav {
            padding: 0.5rem 0.75rem;
            flex-grow: 1;
        }

        .nav-label {
            padding: 0 0.75rem;
            font-size: 0.65rem;
            font-weight: 800;
            color: #94a3b8;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 1.5rem 0 0.5rem;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.65rem 0.85rem;
            color: var(--sidebar-text);
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 2px;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.2s;
        }

        .nav-item:hover {
            background: var(--sidebar-hover);
            color: var(--text-title);
        }

        .nav-item.active {
            background: var(--sidebar-active-bg);
            color: var(--sidebar-active-text);
        }

        .nav-item i {
            width: 17px;
            height: 17px;
            stroke-width: 2.5px;
        }

        /* Main Content area */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .top-bar {
            height: 65px;
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-light);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 90;
        }

        .content-area {
            padding: 1.5rem 2rem;
        }

        .profile-chip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 4px 4px 4px 12px;
            background: white;
            border-radius: 50px;
            border: 1px solid var(--border-light);
            cursor: pointer;
            transition: all 0.2s;
        }

        .profile-chip:hover {
            border-color: var(--brand-primary);
            box-shadow: 0 4px 12px rgba(0,0,0,0.03);
        }

        .avatar {
            width: 32px;
            height: 32px;
            background: var(--brand-gradient);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.7rem;
            font-family: 'Outfit', sans-serif;
        }

        /* Layout Utilities */
        .pro-card {
            background: white;
            border-radius: var(--radius-main);
            border: 1px solid var(--border-light);
            padding: 1.25rem;
            box-shadow: 0 1px 2px rgba(0,0,0,0.02);
            margin-bottom: 1.25rem;
        }

        .btn-pro {
            padding: 7px 16px;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-pro.btn-primary { 
            background: var(--brand-primary); 
            color: white; 
            box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
        }
        
        .btn-pro.btn-primary:hover { 
            background: var(--brand-secondary); 
            transform: translateY(-1px);
            box-shadow: 0 6px 15px rgba(99, 102, 241, 0.3);
        }

        .btn-pro.btn-green {
            background: #10b981;
            color: white;
        }

        .btn-pro i {
            width: 14px;
            stroke-width: 3px;
        }

        .table-responsive {
            overflow-x: auto;
        }

        /* Context Navbar (Custom Tabs) */
        .context-navbar {
            display: flex;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
            border-bottom: 1.5px solid var(--border-light);
            padding: 0 0.5rem;
        }

        .context-tab {
            padding: 0.75rem 0.25rem;
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-muted);
            text-decoration: none;
            border-bottom: 2.5px solid transparent;
            transition: all 0.2s;
        }

        .context-tab:hover { color: var(--brand-primary); }
        .context-tab.active {
            color: var(--brand-primary);
            border-bottom-color: var(--brand-primary);
        }

        @media (max-width: 1024px) {
            :root { --sidebar-width: 80px; }
            .sidebar-header span, .nav-item span, .nav-label { display: none; }
            .sidebar-header { justify-content: center; }
            .nav-item { justify-content: center; padding: 0.75rem; }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-header">
            <div class="logo-box">
                <i data-lucide="graduation-cap" style="color: white; width: 18px; stroke-width: 3px;"></i>
            </div>
            <span style="font-weight: 800; font-size: 1.05rem; letter-spacing: -0.5px; color: var(--text-title);" class="brand-font">EduCore SV</span>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Chính</div>
            <a href="{{ route('student.dashboard') }}" class="nav-item {{ request()->routeIs('student.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i>
                <span>Bảng điều khiển</span>
            </a>

            <div class="nav-label">Học tập</div>
            <a href="{{ route('student.detailed-schedule') }}" class="nav-item {{ request()->routeIs('student.detailed-schedule') ? 'active' : '' }}">
                <i data-lucide="calendar-days"></i>
                <span>Lịch học chi tiết</span>
            </a>
            <a href="{{ route('student.schedule') }}" class="nav-item {{ request()->routeIs('student.schedule') ? 'active' : '' }}">
                <i data-lucide="calendar"></i>
                <span>Thời khóa biểu</span>
            </a>
            <a href="{{ route('student.grades') }}" class="nav-item {{ Route::is('student.grades') ? 'active' : '' }}">
                <i data-lucide="award"></i>
                <span>Kết quả học tập</span>
            </a>

            <div class="nav-label">Cá nhân</div>
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
                    <button type="submit" class="nav-item" style="width: 100%; border: none; background: transparent; cursor: pointer; text-align: left; color: #f87171;">
                        <i data-lucide="log-out"></i>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <div class="main-wrapper">
        <header class="top-bar">
            <h2 style="font-size: 1.1rem; font-weight: 800; color: var(--text-title); letter-spacing: -0.3px;">@yield('title')</h2>
            
            <div class="user-actions">
                <div class="profile-chip">
                    <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-title);">{{ Auth::guard('student')->user()->name }}</span>
                    <div class="avatar">{{ strtoupper(substr(Auth::guard('student')->user()->name, 0, 2)) }}</div>
                </div>
            </div>
        </header>

        <main class="content-area">
            @if(session('success'))
                <div style="background: #f0fdf4; border: 1px solid #dcfce7; color: #166534; padding: 0.85rem 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 0.85rem;">
                    <i data-lucide="check-circle" style="width: 18px;"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; padding: 0.85rem 1rem; border-radius: 12px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 0.85rem;">
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
