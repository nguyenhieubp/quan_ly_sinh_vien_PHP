<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Cổng Thông Tin Giảng Viên</title>
    <script src="https://unpkg.com/lucide@latest"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --brand-primary: #059669;
            --brand-primary-light: #ecfdf5;
            --brand-gradient: linear-gradient(135deg, #065f46 0%, #059669 100%);
            --sidebar-bg: #064e3b;
            --sidebar-width: 240px;
            --content-bg: #fdfdfd;
            --border-light: #f1f5f9;
            --text-title: #0f172a;
            --text-body: #475569;
            --text-muted: #94a3b8;
            --radius-main: 12px;
            --shadow-subtle: 0 1px 3px rgba(0,0,0,0.02);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Plus Jakarta Sans', sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        body {
            background-color: var(--content-bg);
            color: var(--text-body);
            display: flex;
            min-height: 100vh;
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
            box-shadow: 2px 0 12px rgba(0,0,0,0.05);
        }

        .sidebar-logo {
            padding: 2rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .logo-icon {
            width: 34px;
            height: 34px;
            background: var(--brand-gradient);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }

        .sidebar-nav {
            padding: 1.5rem 0.75rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            color: rgba(236, 253, 245, 0.65);
            text-decoration: none;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.88rem;
            transition: all 0.2s ease;
        }

        .nav-item:hover {
            color: white;
            background: rgba(255,255,255,0.06);
        }

        .nav-item.active {
            background: rgba(255,255,255,0.1);
            color: white;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.1);
        }

        .nav-item i {
            width: 18px;
            height: 18px;
        }

        /* Main Context */
        .page-container {
            margin-left: 200px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .header-bar {
            height: 70px;
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
            gap: 1rem;
            padding: 4px 4px 4px 14px;
            border: 1px solid #f1f5f9;
            border-radius: 50px;
            background: #fbfcfd;
        }

        .profile-name {
            font-size: 0.85rem;
            font-weight: 700;
            color: var(--text-title);
        }

        .avatar-circle {
            width: 32px;
            height: 32px;
            background: var(--brand-gradient);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 0.75rem;
        }

        .main-content {
            padding: 1.25rem 1.5rem;
            max-width: 1400px;
            margin: 0;
            width: 100%;
        }

        /* Professional Cards */
        .pro-card {
            background: white;
            border-radius: 8px;
            border: 1px solid var(--border-light);
            padding: 1rem;
            box-shadow: var(--shadow-subtle);
            margin-bottom: 1rem;
        }

        /* Buttons */
        .btn-pro {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.85rem;
            border: none;
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
            filter: brightness(1.1);
            transform: translateY(-1px);
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
