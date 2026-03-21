@extends('layouts.teacher')

@section('title', 'Bảng điều khiển Giảng viên')

@section('content')
<!-- Welcome Section -->
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: stretch; flex-wrap: wrap; gap: 1rem;">
    <div style="flex: 1; min-width: 300px; background: white; padding: 1.5rem; border-radius: var(--radius-main); border: 1px solid var(--border-light); position: relative; overflow: hidden; display: flex; flex-direction: column; justify-content: center;">
        <div style="position: absolute; right: -20px; top: -20px; width: 150px; height: 150px; background: var(--brand-primary); opacity: 0.03; border-radius: 50%;"></div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.5rem; letter-spacing: -0.5px;">Chào bạn, {{ $teacher->name }} 👋</h2>
        <p style="color: var(--text-muted); font-size: 0.85rem; font-weight: 500; margin-bottom: 1.25rem;">Hôm nay là {{ \Carbon\Carbon::now()->translatedFormat('l, d/m/Y') }}. Chúc một ngày làm việc hiệu quả!</p>
        
        <div style="display: flex; gap: 8px; flex-wrap: wrap;">
            <a href="{{ route('teacher.schedule') }}" class="btn-pro btn-green" style="padding: 6px 14px; font-size: 0.75rem; border-radius: 8px;">
                <i data-lucide="calendar" style="width: 14px;"></i> Xem lịch dạy
            </a>
            <a href="{{ route('teacher.profile') }}" class="btn-pro" style="padding: 6px 14px; font-size: 0.75rem; background: white; border: 1px solid var(--border-light); color: var(--text-title); border-radius: 8px;">
                <i data-lucide="user" style="width: 14px;"></i> Hồ sơ cá nhân
            </a>
        </div>
    </div>

    <div style="width: 320px; background: var(--brand-gradient); padding: 1.5rem; border-radius: var(--radius-main); color: white; display: flex; flex-direction: column; justify-content: center; box-shadow: 0 10px 25px -5px rgba(16, 185, 129, 0.2);">
        <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; opacity: 0.8; letter-spacing: 1px; margin-bottom: 8px;">Hệ thống EduCore</div>
        <h3 style="font-size: 1.25rem; font-weight: 800; margin-bottom: 12px; line-height: 1.3;">Chào mừng bạn đến với Cổng thông tin Giảng viên</h3>
        <p style="font-size: 0.8rem; opacity: 0.9; line-height: 1.5;">Quản lý điểm danh, điểm số và theo dõi lớp học một cách chuyên nghiệp.</p>
    </div>
</div>

<!-- Quick Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
    <div class="pro-card" style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem;">
        <div style="width: 42px; height: 42px; background: #ecfdf5; color: #10b981; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="book-open" style="width: 20px;"></i>
        </div>
        <div>
            <div style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Tổng môn học</div>
            <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-title);">{{ $schedules->unique('subject_id')->count() }}</div>
        </div>
    </div>
    
    <div class="pro-card" style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem;">
        <div style="width: 42px; height: 42px; background: #eff6ff; color: #3b82f6; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="users" style="width: 20px;"></i>
        </div>
        <div>
            <div style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Lớp đang dạy</div>
            <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-title);">{{ $schedules->unique('classroom_id')->count() }}</div>
        </div>
    </div>

    <div class="pro-card" style="display: flex; align-items: center; gap: 1rem; padding: 1.25rem;">
        <div style="width: 42px; height: 42px; background: #fffbeb; color: #f59e0b; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="clock" style="width: 20px;"></i>
        </div>
        <div>
            <div style="font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Tiết học/Tuần</div>
            @php
                $totalPeriods = $schedules->sum(function($s) {
                    return (int)$s->end_period - (int)$s->start_period + 1;
                });
            @endphp
            <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-title);">{{ $totalPeriods }}</div>
        </div>
    </div>
</div>

<!-- Current Classes Table -->
<div style="margin-bottom: 1.25rem; display: flex; justify-content: space-between; align-items: center;">
    <h3 style="font-size: 1.15rem; font-weight: 800; color: var(--text-title); letter-spacing: -0.5px;">Phân công học phần trong tuần</h3>
    <a href="{{ route('teacher.schedule') }}" style="font-size: 0.75rem; font-weight: 700; color: var(--brand-primary); text-decoration: none; display: flex; align-items: center; gap: 4px;">
        Xem chi tiết <i data-lucide="chevron-right" style="width: 14px;"></i>
    </a>
</div>

<div class="pro-card" style="padding: 0; overflow: hidden;">
    <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; background: #f9fafb; border-bottom: 1.5px solid #f1f5f9;">
                    <th style="padding: 0.85rem 1.25rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px;">Môn học & Mã HP</th>
                    <th style="padding: 0.85rem 0.75rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px;">Năm học</th>
                    <th style="padding: 0.85rem 0.75rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px;">Lớp sinh viên</th>
                    <th style="padding: 0.85rem 0.75rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px;">Thời gian & Địa điểm</th>
                    <th style="padding: 0.85rem 1.25rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: right;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules->sortBy('day_of_week') as $s)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s;">
                    <td style="padding: 0.85rem 1.25rem;">
                        <div style="font-weight: 700; color: var(--text-title); font-size: 0.85rem;">{{ $s->subject->name }}</div>
                        <div style="font-size: 0.7rem; color: var(--brand-primary); font-weight: 700;">{{ $s->subject->code }}</div>
                    </td>
                    <td style="padding: 0.85rem 0.75rem;">
                        <span style="font-weight: 700; color: #64748b; font-size: 0.75rem; background: #f1f5f9; padding: 2px 8px; border-radius: 4px;">{{ $s->academicYear->name ?? $s->classroom->academicYear->name }}</span>
                    </td>
                    <td style="padding: 0.85rem 0.75rem;">
                        <div style="font-weight: 700; color: var(--text-title); font-size: 0.85rem;">{{ $s->classroom->name }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600;">Sĩ số: {{ $s->classroom->students_count ?? '0' }} SV</div>
                    </td>
                    <td style="padding: 0.85rem 0.75rem;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-title);">Thứ {{ $s->day_of_week + 1 }} • Ca {{ $s->start_period }}-{{ $s->end_period }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">Phòng {{ $s->room }}</div>
                    </td>
                    <td style="padding: 0.85rem 1.25rem; text-align: right;">
                        <div style="display: flex; justify-content: flex-end; gap: 4px;">
                            <a href="{{ route('teacher.attendance', $s->id) }}" class="btn-pro btn-green" style="padding: 5px 10px; font-size: 0.7rem; border-radius: 6px;" title="Điểm danh">
                                 <i data-lucide="user-check" style="width: 14px;"></i>
                            </a>
                            <a href="{{ route('teacher.students', $s->id) }}" class="btn-pro" style="padding: 5px 10px; font-size: 0.7rem; background: white; border: 1px solid var(--border-light); color: var(--text-title); border-radius: 6px;" title="Danh sách lớp">
                                 <i data-lucide="users" style="width: 14px;"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        <i data-lucide="calendar-off" style="width: 40px; height: 40px; margin-bottom: 1rem; opacity: 0.2;"></i>
                        <p style="font-weight: 600; font-size: 0.85rem;">Không có dữ liệu giảng dạy trong tuần này.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
