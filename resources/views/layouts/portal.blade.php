<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portal - {{ config('app.name', 'Laravel') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --brand-primary: #6366f1;
            --brand-secondary: #4f46e5;
            --bg-body: #f8fafc;
            --bg-card: #ffffff;
            --sidebar-bg: #ffffff;
            --sidebar-text: #64748b;
            --sidebar-active: #6366f1;
            --text-title: #1e293b;
            --text-body: #475569;
            --border-color: #f1f5f9;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --radius-main: 16px;
            --fs-base: 0.8125rem;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Inter', sans-serif; }
        body { background-color: var(--bg-body); color: var(--text-body); font-size: var(--fs-base); display: flex; min-height: 100vh; }
        h1, h2, h3, h4 { font-family: 'Outfit', sans-serif; color: var(--text-title); }

        .sidebar {
            width: 260px;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            padding: 2rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
            position: fixed;
            height: 100vh;
        }

        .nav-menu { display: flex; flex-direction: column; gap: 0.5rem; }
        .nav-item {
            text-decoration: none;
            color: var(--sidebar-text);
            padding: 0.8rem 1rem;
            border-radius: 12px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .nav-item:hover { background: #f1f5f9; color: var(--brand-primary); }
        .nav-item.active { background: #eef2ff; color: var(--brand-primary); }
        .nav-item i { width: 18px; height: 18px; }

        .main-content { flex: 1; margin-left: 260px; padding: 2rem; }
        .card { background: white; border-radius: var(--radius-main); padding: 1.5rem; border: 1px solid var(--border-color); box-shadow: var(--shadow-sm); margin-bottom: 2rem; }
        .badge { padding: 0.25rem 0.6rem; border-radius: 6px; font-size: 0.75rem; font-weight: 600; }
        .badge-info { background: #e0f2fe; color: #075985; }
        .btn { padding: 0.6rem 1.25rem; border-radius: 10px; font-weight: 600; cursor: pointer; border: none; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; font-size: 0.85rem; }
        .btn-primary { background: var(--brand-primary); color: white; }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
            <div style="width: 32px; height: 32px; background: var(--brand-primary); border-radius: 8px; display: flex; align-items: center; justify-content: center; color: white;">
                <i data-lucide="graduation-cap" style="width: 20px;"></i>
            </div>
            <h2 style="font-size: 1.25rem; letter-spacing: -0.5px;">Portal</h2>
        </div>

        <nav class="nav-menu">
            <a href="{{ route('portal.dashboard', $student->id) }}" class="nav-item {{ Request::is('*/portal/'.$student->id) ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i> Bảng điều khiển
            </a>
            <a href="{{ route('portal.grades', $student->id) }}" class="nav-item {{ Request::is('*grades*') ? 'active' : '' }}">
                <i data-lucide="award"></i> Kết quả học tập
            </a>
            <a href="{{ route('portal.schedule', $student->id) }}" class="nav-item {{ Request::is('*schedule*') ? 'active' : '' }}">
                <i data-lucide="calendar"></i> Lịch học cá nhân
            </a>
            <a href="{{ route('portal.registration', $student->id) }}" class="nav-item {{ Request::is('*registration*') ? 'active' : '' }}">
                <i data-lucide="edit-3"></i> Đăng ký học phần
            </a>
        </nav>

        <div style="margin-top: auto;">
             <a href="/" class="nav-item" style="color: #ef4444;">
                <i data-lucide="log-out"></i> Thoát Portal
            </a>
        </div>
    </aside>

    <main class="main-content">
        <header style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <div>
                <h1 style="font-size: 1.5rem;">Xin chào, {{ $student->name }}</h1>
                <p style="color: #64748b;">Mã SV: {{ $student->student_code }} | Lớp: {{ $student->classroom->name }}</p>
            </div>
            <div style="display: flex; gap: 1rem; align-items: center;">
                <div style="width: 40px; height: 40px; background: #e2e8f0; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; color: #475569;">
                    {{ substr($student->name, 0, 1) }}
                </div>
            </div>
        </header>

        @yield('content')
    </main>

    <script>lucide.createIcons();</script>
</body>
</html>
