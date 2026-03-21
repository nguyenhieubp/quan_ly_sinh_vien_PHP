@extends('layouts.student')

@section('title', 'Bảng điều khiển')

@section('content')
<!-- Welcome Section -->
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: stretch; flex-wrap: wrap; gap: 1rem;">
    <div style="flex: 1; min-width: 300px; background: white; padding: 1.5rem; border-radius: var(--radius-main); border: 1px solid var(--border-light); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: center;">
        <div style="position: absolute; right: -20px; top: -20px; width: 150px; height: 150px; background: var(--brand-primary); opacity: 0.03; border-radius: 50%;"></div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.5rem; letter-spacing: -0.5px;">Chào bạn, {{ Auth::guard('student')->user()->name }} 👋</h2>
        <p style="color: var(--text-muted); font-size: 0.85rem; font-weight: 500; margin-bottom: 1.25rem;">Hôm nay là {{ \Carbon\Carbon::now()->translatedFormat('l, d/m/Y') }}. Chúc một ngày học tập hiệu quả!</p>
        
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('student.detailed-schedule') }}" class="btn-pro btn-primary" style="padding: 6px 14px; font-size: 0.75rem; border-radius: 8px;">
                <i data-lucide="calendar" style="width: 14px;"></i> Xem lịch học
            </a>
            <a href="{{ route('student.profile') }}" class="btn-pro" style="padding: 6px 14px; font-size: 0.75rem; background: white; border: 1px solid var(--border-light); color: var(--text-title); border-radius: 8px;">
                <i data-lucide="user" style="width: 14px;"></i> Hồ sơ cá nhân
            </a>
        </div>
    </div>

    <div style="width: 320px; background: var(--brand-gradient); padding: 1.5rem; border-radius: var(--radius-main); color: white; display: flex; flex-direction: column; justify-content: center; box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.2);">
        <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; opacity: 0.8; letter-spacing: 1px; margin-bottom: 8px;">Hệ thống EduCore SV</div>
        <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 12px; line-height: 1.3;">Chào mừng bạn đến với Cổng thông tin Sinh viên</h3>
        <p style="font-size: 0.8rem; opacity: 0.9; line-height: 1.5;">Theo dõi lịch học, kết quả học tập và quản lý thông tin cá nhân một cách dễ dàng.</p>
    </div>
</div>

<!-- Quick Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
    <div class="pro-card" style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem;">
        <div style="width: 42px; height: 42px; background: #eff6ff; color: #3b82f6; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="book-open" style="width: 20px;"></i>
        </div>
        <div>
            <div style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Môn đã đăng ký</div>
            <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-title);">{{ $registrations->count() }}</div>
        </div>
    </div>
    
    <div class="pro-card" style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem;">
        <div style="width: 42px; height: 42px; background: #ecfdf5; color: #10b981; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="award" style="width: 20px;"></i>
        </div>
        <div>
            <div style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Tín chỉ tích lũy</div>
            <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-title);">{{ $registrations->sum(fn($r) => $r->schedule->subject->credits) }}</div>
        </div>
    </div>

    <div class="pro-card" style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem;">
        <div style="width: 42px; height: 42px; background: #fff7ed; color: #f97316; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="layout" style="width: 20px;"></i>
        </div>
        <div>
            <div style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Lớp sinh viên</div>
            <div style="font-size: 0.9rem; font-weight: 800; color: var(--text-title);">{{ $student->classroom->name }}</div>
        </div>
    </div>
</div>

<!-- Filters Bar -->
<div class="pro-card" style="margin-bottom: 1.5rem; padding: 1.25rem;">
    <form action="{{ route('student.dashboard') }}" method="GET" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 250px;">
            <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Tìm kiếm môn học</label>
            <div style="position: relative;">
                <i data-lucide="search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 14px; color: var(--text-muted);"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Nhập tên hoặc mã môn..."
                    style="width: 100%; padding: 7px 12px 7px 34px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.8rem; font-weight: 500; outline: none;">
            </div>
        </div>

        <div style="width: 200px;">
            <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Năm học</label>
            <select name="academic_year_id" 
                style="width: 100%; padding: 7px 12px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.8rem; font-weight: 600; color: var(--text-title); background: white; outline: none; cursor: pointer;">
                <option value="">-- Tất cả năm --</option>
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                        {{ $year->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-pro btn-primary" style="padding: 7px 20px;">
            <i data-lucide="filter" style="width: 14px;"></i>
            Lọc
        </button>
        
        @if($search || $selectedYearId)
            <a href="{{ route('student.dashboard') }}" style="font-size: 0.75rem; color: #ef4444; font-weight: 700; text-decoration: none; margin-left: 4px;">Xóa lọc</a>
        @endif
    </form>
</div>

<!-- Registrations Table -->
<div class="pro-card" style="padding: 0; overflow: hidden;">
    <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; background: #f9fafb; border-bottom: 1.5px solid #f1f5f9;">
                    <th style="padding: 0.85rem 1.25rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 60px;">#</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 120px;">Mã môn</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px;">Tên môn học</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: center; width: 80px;">Tín chỉ</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: right; width: 180px;">Năm học / Học kỳ</th>
                    <th style="padding: 0.85rem 1.25rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: right; width: 100px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($registrations as $index => $reg)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s;">
                    <td style="padding: 0.85rem 1.25rem; color: #94a3b8; font-weight: 600; font-size: 0.8rem;">{{ $index + 1 }}</td>
                    <td style="padding: 0.85rem 1rem;">
                        <span style="font-weight: 700; color: var(--brand-primary); font-size: 0.75rem; background: #ecfdf5; padding: 2px 8px; border-radius: 4px;">{{ $reg->schedule->subject->code }}</span>
                    </td>
                    <td style="padding: 0.85rem 1rem;">
                        <div style="font-weight: 700; color: var(--text-title); font-size: 0.85rem;">{{ $reg->schedule->subject->name }}</div>
                    </td>
                    <td style="padding: 0.85rem 1rem; text-align: center;">
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-title);">{{ $reg->schedule->subject->credits }}</div>
                    </td>
                    <td style="padding: 0.85rem 1rem; text-align: right;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-title);">{{ $reg->schedule->academicYear->name ?? '-' }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600;">{{ $reg->semester->name ?? 'N/A' }}</div>
                    </td>
                    <td style="padding: 0.85rem 1.25rem; text-align: right;">
                        <a href="{{ route('student.detailed-schedule') }}" class="btn-pro" style="padding: 4px 8px; font-size: 0.7rem; background: white; border: 1px solid var(--border-light); color: var(--brand-primary); border-radius: 6px;">
                            <i data-lucide="eye" style="width: 14px;"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 3.5rem; text-align: center; color: var(--text-muted);">
                        <i data-lucide="inbox" style="width: 40px; height: 40px; margin-bottom: 1rem; opacity: 0.2;"></i>
                        <p style="font-weight: 600; font-size: 0.85rem;">Không tìm thấy đăng ký nào phù hợp.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
