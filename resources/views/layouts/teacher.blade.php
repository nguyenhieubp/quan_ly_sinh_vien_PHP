<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Cổng Thông Tin Giảng Viên</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-primary: #059669;
            --brand-primary-light: #ecfdf5;
            --brand-gradient: linear-gradient(135deg, #065f46 0%, #059669 100%);
            --sidebar-bg: #064e3b;
            --sidebar-width: 200px;
            --content-bg: #f8fafc;
            --border-light: #e2e8f0;
            --text-title: #0f172a;
            --text-body: #475569;
            --text-muted: #64748b;
            --radius-main: 12px;
            --shadow-subtle: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            /* Compact font sizes */
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

        h1, h2, h3, h4, .sidebar-logo h2 {
            font-family: 'Outfit', sans-serif;
            color: var(--text-title);
        }

        body {
            background-color: var(--content-bg);
            color: var(--text-body);
            display: flex;
            min-height: 100vh;
            font-size: var(--fs-base);
        }

        /* Streamlined Sidebar */
        .sidebar {
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            color: #ecfdf5;
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 1000;
        }

        .sidebar-logo {
            padding: 1.5rem 1.25rem;
            display: flex;
            align-items: center;
            gap: 10px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .logo-icon {
            width: 32px;
            height: 32px;
            background: var(--brand-gradient);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.15);
        }

        .sidebar-nav {
            padding: 1rem 0.6rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 0.6rem 0.85rem;
            color: rgba(236, 253, 245, 0.7);
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            font-size: 0.8rem;
            transition: all 0.2s ease;
        }

        .nav-item:hover {
            color: white;
            background: rgba(255,255,255,0.05);
        }

        .nav-item.active {
            background: var(--brand-primary);
            color: white;
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.2);
        }

        .nav-item i {
            width: 16px;
            height: 16px;
        }

        /* Main Context */
        .page-container {
            margin-left: var(--sidebar-width);
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-width: 0;
        }

        .header-bar {
            height: 65px;
            background: white;
            border-bottom: 1px solid var(--border-light);
            padding: 0 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .profile-section {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.35rem 0.35rem 0.35rem 1rem;
            border: 1px solid var(--border-light);
            border-radius: 50px;
            background: white;
            cursor: pointer;
            transition: all 0.2s;
        }

        .profile-section:hover {
            background: #f8fafc;
        }

        .profile-name {
            font-size: 0.8rem;
            font-weight: 600;
            color: var(--text-title);
        }

        .avatar-circle {
            width: 30px;
            height: 30px;
            background: var(--brand-gradient);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 0.75rem;
        }

        .main-content {
            padding: 1.5rem 2rem;
            width: 100%;
        }

        /* Professional Cards */
        .pro-card {
            background: white;
            border-radius: var(--radius-main);
            border: 1px solid var(--border-light);
            padding: 1.25rem;
            box-shadow: var(--shadow-subtle);
            margin-bottom: 1.5rem;
        }

        /* Buttons */
        .btn-pro {
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            border: 1px solid transparent;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .btn-green {
            background: var(--brand-primary);
            color: white;
        }

        .btn-green:hover {
            filter: brightness(1.05);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(5, 150, 105, 0.15);
        }

        @media (max-width: 992px) {
            .sidebar { width: 80px; }
            .sidebar span, .sidebar-logo h2 { display: none; }
            .page-container { margin-left: 80px; }
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-logo">
            <div class="logo-icon">
                <i data-lucide="book-check" style="color: white; width: 20px;"></i>
            </div>
            <h2 style="font-size: 1.1rem; font-weight: 800; letter-spacing: -0.5px;">Portal Giảng Viên</h2>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('teacher.dashboard') }}" class="nav-item {{ request()->routeIs('teacher.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-grid"></i>
                <span>Bảng điều khiển</span>
            </a>
            <a href="{{ route('teacher.schedule') }}" class="nav-item {{ request()->routeIs('teacher.schedule') ? 'active' : '' }}">
                <i data-lucide="calendar"></i>
                <span>Thời khóa biểu</span>
            </a>
            <a href="{{ route('teacher.subjects', ['mode' => 'attendance']) }}" class="nav-item {{ (request()->routeIs('teacher.subjects*') && request('mode') != 'grades') || request()->routeIs('teacher.attendance') || request()->routeIs('teacher.students') ? 'active' : '' }}">
                <i data-lucide="calendar-check"></i>
                <span>Lịch dạy & Điểm danh</span>
            </a>
            <a href="{{ route('teacher.subjects', ['mode' => 'grades']) }}" class="nav-item {{ (request()->routeIs('teacher.subjects*') && request('mode') == 'grades') || request()->routeIs('teacher.grades') ? 'active' : '' }}">
                <i data-lucide="graduation-cap"></i>
                <span>Nhập điểm học thuật</span>
            </a>
            <a href="{{ route('teacher.profile') }}" class="nav-item {{ request()->routeIs('teacher.profile') ? 'active' : '' }}">
                <i data-lucide="user-cog"></i>
                <span>Thiết lập hồ sơ</span>
            </a>

            <div style="margin-top: auto;">
                <form action="{{ route('teacher.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="nav-item" style="width: 100%; border: none; background: transparent; color: #f87171; cursor: pointer;">
                        <i data-lucide="power"></i>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </div>
        </nav>
    </aside>

    <div class="page-container">
        <header class="header-bar">
            <h1 style="font-size: 1.25rem; font-weight: 800; color: var(--text-title); letter-spacing: -0.5px;">@yield('title')</h1>
            
            <div class="profile-section">
                <span class="profile-name">{{ Auth::guard('teacher')->user()->name }}</span>
                <div class="avatar-circle">{{ strtoupper(substr(Auth::guard('teacher')->user()->name, 0, 2)) }}</div>
            </div>
        </header>

        @if(View::hasSection('context-navbar'))
            <div style="background: white; border-bottom: 1px solid var(--border-light); padding: 0 2rem; display: flex; gap: 2rem; position: sticky; top: 70px; z-index: 850;">
                @yield('context-navbar')
            </div>
            <style>
                .context-tab {
                    padding: 1rem 0;
                    font-size: 0.85rem;
                    font-weight: 700;
                    color: var(--text-muted);
                    text-decoration: none;
                    border-bottom: 3px solid transparent;
                    transition: all 0.2s;
                }
                .context-tab:hover { color: var(--brand-primary); }
                .context-tab.active {
                    color: var(--brand-primary);
                    border-bottom-color: var(--brand-primary);
                }
            </style>
        @endif

        <main class="main-content">
            @if(session('success'))
                <div style="background: #f0fdf4; border: 1px solid #dcfce7; color: #166534; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 0.9rem;">
                    <i data-lucide="check-circle" style="width: 18px;"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px; font-weight: 600; font-size: 0.9rem;">
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
