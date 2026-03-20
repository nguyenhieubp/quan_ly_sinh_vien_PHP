<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }} - Hệ thống Quản lý Đào tạo</title>
    <!-- Modern Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        :root {
            --brand-primary: #4f46e5;
            --brand-secondary: #6366f1;
            --bg-body: #f1f5f9;
            --bg-card: #ffffff;
            --sidebar-bg: #1e293b;
            --sidebar-text: #94a3b8;
            --sidebar-active: #ffffff;
            --text-title: #0f172a;
            --text-body: #475569;
            --border-color: #e2e8f0;
            --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
            --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
            --radius-main: 12px;
            
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
        }

        h1, h2, h3, h4, .brand-logo {
            font-family: 'Outfit', sans-serif;
            color: var(--text-title);
        }

        body {
            background-color: var(--bg-body);
            color: var(--text-body);
            display: flex;
            min-height: 100vh;
            overflow-x: hidden;
            font-size: var(--fs-base);
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        /* Sidebar Styling */
        .sidebar {
            width: 200px;
            background-color: var(--sidebar-bg);
            color: var(--sidebar-text);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            display: flex;
            flex-direction: column;
            padding: 1rem 0.75rem;
            z-index: 1000;
            transition: all 0.3s ease;
            overflow-y: auto;
        }

        .brand-section {
            padding: 0.5rem 0.5rem 2rem 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .brand-logo {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .brand-name {
            font-size: 0.9rem;
            font-weight: 800;
            color: white;
            letter-spacing: 0.5px;
        }

        .nav-menu {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        .nav-item {
            text-decoration: none;
            color: var(--sidebar-text);
            padding: 0.6rem 0.85rem;
            border-radius: 8px;
            display: flex;
            align-items: center;
            gap: 0.6rem;
            font-weight: 500;
            font-size: 0.8rem;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .nav-item:hover {
            background-color: rgba(255, 255, 255, 0.05);
            color: white;
        }

        .nav-item.active {
            background-color: var(--brand-primary);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .nav-item i, .nav-item svg {
            width: 16px;
            height: 16px;
        }

        .nav-section-label {
            font-size: 10px;
            font-weight: 800;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 1.5rem 0.85rem 0.5rem 0.85rem;
            display: block;
        }

        .nav-section:first-child .nav-section-label {
            margin-top: 0;
        }

        /* Main Content area */
        .page-wrapper {
            flex: 1;
            margin-left: 200px;
            display: flex;
            flex-direction: column;
            min-width: 0; /* Prevent flex overflow */
        }

        header.top-bar {
            height: 70px;
            background-color: var(--bg-card);
            border-bottom: 1px solid var(--border-color);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .search-box {
            position: relative;
            display: flex;
            align-items: center;
        }

        .search-box input {
            padding: 0.6rem 1rem 0.6rem 2.5rem;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            background: #f8fafc;
            width: 300px;
            outline: none;
            font-size: 0.9rem;
            transition: all 0.2s;
        }

        .search-box input:focus {
            border-color: var(--brand-primary);
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
            background: white;
        }

        .search-box svg {
            position: absolute;
            left: 10px;
            color: #94a3b8;
            width: 18px;
            height: 18px;
        }

        .user-actions {
            display: flex;
            align-items: center;
            gap: 1.5rem;
        }

        .profile-chip {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.4rem;
            border-radius: 50px;
            border: 1px solid var(--border-color);
            cursor: pointer;
            transition: all 0.2s;
        }

        .profile-chip:hover {
            background: #f8fafc;
        }

        .avatar {
            width: 32px;
            height: 32px;
            background: #e2e8f0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #64748b;
            font-weight: 600;
            font-size: 0.8rem;
        }

        main {
            padding: 2rem;
        }

        /* Card System */
        .card {
            background-color: var(--bg-card);
            border-radius: var(--radius-main);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-color);
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        /* Modern Tables */
        .table-responsive {
            overflow-x: auto;
        }

        table.data-table {
            width: 100%;
            border-collapse: collapse;
        }

        table.data-table th {
            text-align: left;
            padding: 0.75rem 1rem;
            font-weight: 600;
            font-size: 0.75rem;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            border-bottom: 1px solid var(--border-color);
        }

        table.data-table td {
            padding: 0.75rem 1rem;
            font-size: 0.85rem;
            color: var(--text-body);
            border-bottom: 1px solid #f1f5f9;
        }

        table.data-table tr:last-child td {
            border-bottom: none;
        }

        table.data-table tr:hover td {
            background-color: #f8fafc;
        }

        table.data-table tr.hidden {
            display: none;
        }

        /* Button styles */
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1.25rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            border: 1px solid transparent;
            text-decoration: none;
        }

        .btn-primary {
            background-color: var(--brand-primary);
            color: white;
        }

        .btn-primary:hover {
            background-color: var(--brand-secondary);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .btn-outline {
            border-color: var(--border-color);
            background: white;
            color: var(--text-body);
        }

        .btn-outline:hover {
            background: #f8fafc;
            color: var(--brand-primary);
            border-color: var(--brand-primary);
        }

        .badge {
            padding: 0.25rem 0.6rem;
            border-radius: 6px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }

        .badge-success { background: #dcfce7; color: #166534; }
        .badge-info { background: #e0f2fe; color: #075985; }

        /* Chatbot Refined */
        #chat-bubble {
            position: fixed;
            bottom: 30px;
            right: 30px;
            width: 65px;
            height: 65px;
            background: linear-gradient(135deg, var(--brand-primary), var(--brand-secondary));
            border-radius: 50% 50% 5px 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            cursor: pointer;
            box-shadow: 0 8px 24px rgba(79, 70, 229, 0.4);
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            z-index: 2000;
        }

        #chat-bubble:hover {
            transform: scale(1.05) translateY(-5px);
        }

        #chat-window {
            position: fixed;
            bottom: 110px;
            right: 30px;
            width: 380px;
            height: 550px;
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
            display: none;
            flex-direction: column;
            overflow: hidden;
            z-index: 2000;
            border: 1px solid var(--border-color);
            animation: slideUp 0.3s ease-out;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .chat-header {
            background: var(--sidebar-bg);
            color: white;
            padding: 1.25rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .chat-header h4 { color: white; margin: 0; }

        .chat-messages {
            flex: 1;
            padding: 1.25rem;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 1rem;
            background: #f8fafc;
        }

        .msg {
            max-width: 80%;
            padding: 0.75rem 1rem;
            border-radius: 15px;
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .msg.bot {
            align-self: flex-start;
            background: white;
            color: var(--text-body);
            border-bottom-left-radius: 2px;
            box-shadow: var(--shadow-sm);
        }

        .msg.user {
            align-self: flex-end;
            background: var(--brand-primary);
            color: white;
            border-bottom-right-radius: 2px;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.2);
        }

        .chat-input-area {
            padding: 1rem;
            background: white;
            display: flex;
            gap: 0.5rem;
            border-top: 1px solid var(--border-color);
        }

        .chat-input-area input {
            flex: 1;
            padding: 0.75rem 1rem;
            border: 1px solid var(--border-color);
            border-radius: 50px;
            outline: none;
            background: #f8fafc;
            transition: all 0.2s;
        }

        .chat-input-area input:focus {
            border-color: var(--brand-primary);
            background: white;
        }
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="brand-section">
            <div class="brand-logo">
                <i data-lucide="graduation-cap"></i>
            </div>
            <span class="brand-name">EDUPORTAL</span>
        </div>

        <nav class="nav-menu">
            <!-- CHUNG -->
            <div class="nav-section">
                <span class="nav-section-label">Chung</span>
                <a href="/" class="nav-item {{ Request::is('/') ? 'active' : '' }}">
                    <i data-lucide="layout-dashboard"></i>
                    Tổng quan
                </a>
            </div>

            <!-- TỔ CHỨC -->
            <div class="nav-section">
                <span class="nav-section-label">Tổ chức</span>
                <a href="/departments" class="nav-item {{ Request::is('departments*') ? 'active' : '' }}">
                    <i data-lucide="building"></i>
                    Khoa & Đơn vị
                </a>
                <a href="/teachers" class="nav-item {{ Request::is('teachers*') ? 'active' : '' }}">
                    <i data-lucide="users"></i>
                    Giảng viên
                </a>
                <a href="/classrooms" class="nav-item {{ Request::is('classrooms*') ? 'active' : '' }}">
                    <i data-lucide="hotel"></i>
                    Lớp học
                </a>
            </div>

            <!-- ĐÀO TẠO -->
            <div class="nav-section">
                <span class="nav-section-label">Đào tạo</span>
                <a href="/students" class="nav-item {{ Request::is('students*') ? 'active' : '' }}">
                    <i data-lucide="user-round"></i>
                    Sinh viên
                </a>
                <a href="/subjects" class="nav-item {{ Request::is('subjects*') ? 'active' : '' }}">
                    <i data-lucide="book-open"></i>
                    Môn học
                </a>
                <a href="/schedules" class="nav-item {{ Request::is('schedules*') ? 'active' : '' }}">
                    <i data-lucide="calendar"></i>
                    Lịch đào tạo
                </a>
            </div>

            <!-- KẾT QUẢ -->
            <div class="nav-section">
                <span class="nav-section-label">Kết quả</span>
                <a href="/grades" class="nav-item {{ Request::is('grades*') ? 'active' : '' }}">
                    <i data-lucide="award"></i>
                    Quản lý Điểm
                </a>
                <a href="/attendance" class="nav-item {{ (Request::is('attendance*') && !Request::is('attendance-rules*')) ? 'active' : '' }}">
                    <i data-lucide="check-square"></i>
                    Điểm danh (Mới)
                </a>
                <a href="{{ route('attendance-rules.index') }}" class="nav-item {{ Request::is('attendance-rules*') ? 'active' : '' }}">
                    <i data-lucide="settings"></i>
                    Cấu hình chuyên cần
                </a>
            </div>


            <div style="margin-top: auto; padding-top: 1rem; border-top: 1px solid rgba(255,255,255,0.05);">
                <form action="{{ route('logout') }}" method="POST" id="logout-form">
                    @csrf
                    <button type="submit" class="nav-item" style="width: 100%; border: none; background: rgba(239, 68, 68, 0.1); color: #f87171; cursor: pointer; text-align: left;">
                        <i data-lucide="log-out"></i>
                        <span>Đăng xuất</span>
                    </button>
                </form>
            </div>
        </nav>

    </aside>

    <div class="page-wrapper">
        <header class="top-bar">
            <div class="user-actions">
                <div style="position: relative; cursor:pointer">
                    <i data-lucide="bell" style="width: 20px; color: #64748b"></i>
                    <span style="position:absolute; top:-5px; right:-5px; width:8px; height:8px; background:#ef4444; border-radius:50%; border:2px solid white"></span>
                </div>
                <div class="profile-chip">
                    <div class="avatar">{{ strtoupper(substr(Auth::user()->name ?? 'AD', 0, 2)) }}</div>
                    <span style="font-size: 0.9rem; font-weight: 600;">{{ Auth::user()->name ?? 'Administrator' }}</span>
                    <i data-lucide="chevron-down" style="width: 14px; color: #64748b"></i>
                </div>
            </div>
        </header>

        <main>
            @yield('content')
        </main>
    </div>

    <!-- Smart Chat Agent -->
    <div id="chat-bubble">
        <i data-lucide="bot-message-square"></i>
    </div>

    <div id="chat-window">
        <div class="chat-header">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 32px; height: 32px; background: rgba(255,255,255,0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i data-lucide="bot" style="width: 18px;"></i>
                </div>
                <div>
                    <h4 style="font-size: 0.95rem;">EduAgent AI</h4>
                    <span style="font-size: 0.7rem; opacity: 0.7;">Đang trực tuyến</span>
                </div>
            </div>
            <i data-lucide="x" id="close-chat" style="cursor: pointer; width: 18px;"></i>
        </div>
        <div class="chat-messages" id="chat-msgs">
            <div class="msg bot">Chào bạn! Tôi là **EduAgent**. Tôi có thể giúp bạn tra cứu điểm số, lịch học, chuyên cần và nhiều thông tin khác.</div>
        </div>
        
        <!-- Quick Actions -->
        <div id="quick-actions" style="padding: 0.5rem 1rem; display: flex; gap: 6px; overflow-x: auto; white-space: nowrap; border-top: 1px solid #f1f5f9; background: #fff;">
            <div class="action-chip" onclick="quickAsk('Lịch học hôm nay')">📅 Lịch học</div>
            <div class="action-chip" onclick="quickAsk('Hệ thống có bao nhiêu sinh viên?')">📊 Thống kê</div>
            <div class="action-chip" onclick="quickAsk('Danh sách các khoa')">🏢 Các khoa</div>
            <div class="action-chip" onclick="quickAsk('Ai là sinh viên xuất sắc nhất?')">🏆 Top SV</div>
        </div>

        <div class="chat-input-area">
            <input type="text" id="chat-input" placeholder="Gửi câu hỏi của bạn...">
            <button id="chat-send" style="background: var(--brand-primary); color: white; border: none; width: 40px; height: 40px; border-radius: 50%; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                <i data-lucide="send" style="width: 18px;"></i>
            </button>
        </div>
    </div>

    <script>
        // Initialize Lucide Icons
        lucide.createIcons();

        // Chat Logic
        const bubble = document.getElementById('chat-bubble');
        const windowChat = document.getElementById('chat-window');
        const closeChat = document.getElementById('close-chat');
        const sendBtn = document.getElementById('chat-send');
        const chatInput = document.getElementById('chat-input');
        const chatMsgs = document.getElementById('chat-msgs');

        bubble.onclick = () => {
            windowChat.style.display = 'flex';
            bubble.style.display = 'none';
        };

        closeChat.onclick = () => {
            windowChat.style.display = 'none';
            bubble.style.display = 'flex';
        };

        async function postMessage(messageOverride = null) {
            const val = messageOverride || chatInput.value.trim();
            if(!val) return;

            // Clear input
            if (!messageOverride) chatInput.value = '';

            // User Message
            const userMsg = document.createElement('div');
            userMsg.className = 'msg user';
            userMsg.textContent = val;
            chatMsgs.appendChild(userMsg);
            chatMsgs.scrollTop = chatMsgs.scrollHeight;

            // Typing Indicator
            const typingIndicator = document.createElement('div');
            typingIndicator.className = 'msg bot typing';
            typingIndicator.innerHTML = '<span class="typing-dots">Đang xử lý...</span>';
            chatMsgs.appendChild(typingIndicator);
            chatMsgs.scrollTop = chatMsgs.scrollHeight;

            try {
                const res = await fetch('/chatbot', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ message: val })
                });
                const data = await res.json();
                
                // Remove typing indicator
                typingIndicator.remove();

                const botMsg = document.createElement('div');
                botMsg.className = 'msg bot';
                
                // Simple bolding formatter for bot response
                let reply = data.reply.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                reply = reply.replace(/\n/g, '<br>');
                
                botMsg.innerHTML = reply;
                chatMsgs.appendChild(botMsg);
                chatMsgs.scrollTop = chatMsgs.scrollHeight;
            } catch(e) { 
                console.error(e); 
                typingIndicator.innerHTML = '⚠️ Có lỗi xảy ra, vui lòng thử lại.';
            }
        }

        function quickAsk(msg) {
            postMessage(msg);
        }
        sendBtn.onclick = postMessage;
        chatInput.onkeydown = (e) => { if(e.key === 'Enter') postMessage(); };
        // Search Filter Logic
        function setupFilters() {
            const filters = document.querySelectorAll('#global-search, .table-filter');
            filters.forEach(filter => {
                filter.addEventListener('input', function() {
                    const query = this.value.toLowerCase();
                    const table = document.querySelector('.data-table');
                    if (!table) return;

                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const text = row.innerText.toLowerCase();
                        if (text.includes(query)) {
                            row.classList.remove('hidden');
                        } else {
                            row.classList.add('hidden');
                        }
                    });
                    
                    // Sync values across filters
                    filters.forEach(f => { if(f !== this) f.value = this.value; });
                });
            });
        }
        setupFilters();
    </script>
</body>
</html>
