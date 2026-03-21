@extends('layouts.teacher')

@section('title', 'Bảng điều khiển')

@section('content')
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-title); margin-bottom: 4px;">Chào bạn, {{ $teacher->name }}</h2>
        <p style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">Hôm nay là {{ \Carbon\Carbon::now()->format('l, d/m/Y') }}. Chúc bạn một ngày làm việc hiệu quả!</p>
    </div>
    
    <div class="pro-card" style="padding: 10px 15px; margin-bottom: 0;">
        <form action="{{ route('teacher.dashboard') }}" method="GET" style="display: flex; align-items: center; gap: 10px;">
            <label style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Năm học:</label>
            <select name="year" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.85rem; font-weight: 700; background: white; min-width: 150px;">
                <option value="">-- Tất cả --</option>
                @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}" {{ request('year') == $ay->id ? 'selected' : '' }}>{{ $ay->name }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

<!-- Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.25rem; margin-bottom: 2rem;">
    <div class="pro-card" style="display: flex; align-items: center; gap: 1.25rem;">
        <div style="width: 44px; height: 44px; background: #f0fdf4; color: #16a34a; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="book-open" style="width: 22px; height: 22px;"></i>
        </div>
        <div>
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 2px;">Môn giảng dạy</div>
            <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-title);">{{ $subjectsCount }}</div>
        </div>
    </div>

    <div class="pro-card" style="display: flex; align-items: center; gap: 1.25rem;">
        <div style="width: 44px; height: 44px; background: #eff6ff; color: #2563eb; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="calendar" style="width: 22px; height: 22px;"></i>
        </div>
        <div>
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 2px;">Tổng ca học</div>
            <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-title);">{{ $activeSchedules->count() }}</div>
        </div>
    </div>

     <div class="pro-card" style="display: flex; align-items: center; gap: 1.25rem;">
        <div style="width: 44px; height: 44px; background: #fef2f2; color: #dc2626; border-radius: 10px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="graduation-cap" style="width: 22px; height: 22px;"></i>
        </div>
        <div>
            <div style="font-size: 0.75rem; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 2px;">Khoa phụ trách</div>
            <div style="font-size: 0.95rem; font-weight: 800; color: var(--text-title); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; max-width: 150px;">{{ $teacher->department->name }}</div>
        </div>
    </div>
</div>

<!-- Schedule Section -->
<div class="pro-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
        <h3 style="font-size: 1rem; font-weight: 800; color: var(--text-title); display: flex; align-items: center; gap: 8px;">
            <i data-lucide="table-2" style="width: 18px; color: var(--brand-primary);"></i>
            Lịch giảng dạy trong tuần
        </h3>
        <a href="{{ route('teacher.subjects') }}" style="font-size: 0.8rem; font-weight: 700; color: var(--brand-primary); text-decoration: none;">Xem chi tiết &rarr;</a>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.9rem;">
            <thead>
                <tr style="text-align: left; border-bottom: 1.5px solid #f1f5f9;">
                    <th style="padding: 12px 0.75rem; color: var(--text-muted); font-weight: 700;">Học phần</th>
                    <th style="padding: 12px 0.75rem; color: var(--text-muted); font-weight: 700;">Năm học</th>
                    <th style="padding: 12px 0.75rem; color: var(--text-muted); font-weight: 700;">Lớp học</th>
                    <th style="padding: 12px 0.75rem; color: var(--text-muted); font-weight: 700;">Thời gian & Địa điểm</th>
                    <th style="padding: 12px 0.75rem; color: var(--text-muted); font-weight: 700; text-align: right;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($activeSchedules as $s)
                <tr style="border-bottom: 1px solid #f8fafc; transition: background 0.2s;">
                    <td style="padding: 1rem 0.75rem;">
                        <div style="font-weight: 700; color: var(--text-title);">{{ $s->subject->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">{{ $s->subject->subject_code }}</div>
                    </td>
                    <td style="padding: 1rem 0.75rem;">
                         <span style="font-weight: 700; color: var(--brand-primary); font-size: 0.85rem;">{{ $s->academicYear->name ?? $s->classroom->academicYear->name }}</span>
                    </td>
                    <td style="padding: 1rem 0.75rem;">
                        <span style="font-weight: 600; color: var(--text-title);">{{ $s->classroom->name }}</span>
                    </td>
                    <td style="padding: 1rem 0.75rem;">
                        <div style="display: flex; flex-direction: column; gap: 4px;">
                            <div style="display: flex; align-items: center; gap: 6px; font-weight: 700; color: var(--text-title); font-size: 0.85rem;">
                                 Thứ {{ $s->day_of_week + 1 }} • Ca {{ $s->start_period }}-{{ $s->end_period }}
                            </div>
                            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">
                                Phòng {{ $s->room }} • ({{ \Carbon\Carbon::parse($s->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($s->end_time)->format('H:i') }})
                            </div>
                        </div>
                    </td>
                    <td style="padding: 1rem 0.75rem; text-align: right;">
                        <a href="{{ route('teacher.students', $s->id) }}" class="btn-pro" style="background: #f8fafc; border: 1px solid #e2e8f0; color: var(--text-title); padding: 6px 14px; font-size: 0.75rem;">
                             <i data-lucide="users-2" style="width: 14px;"></i>
                             <span>Sinh viên</span>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        <p style="font-weight: 600;">Không có dữ liệu giảng dạy được ghi nhận.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
吐
吐
吐
吐
吐
