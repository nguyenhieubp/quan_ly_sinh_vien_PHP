@extends('layouts.teacher')

@section('title', 'Danh sách sinh viên')

@section('context-navbar')
    <a href="{{ route('teacher.students', $schedule->id) }}" class="context-tab active">Danh sách sinh viên</a>
    <a href="{{ route('teacher.attendance', $schedule->id) }}" class="context-tab">Ghi nhận điểm danh</a>
    <a href="{{ route('teacher.grades', $schedule->id) }}" class="context-tab">Nhập điểm</a>
@endsection

@section('content')
<div class="pro-card" style="margin-bottom: 1.25rem; padding: 1.25rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.15rem; font-weight: 800; color: var(--text-title); margin-bottom: 4px; letter-spacing: -0.5px;">{{ $subject->name }}</h2>
            <div style="display: flex; align-items: center; gap: 8px; color: var(--text-muted); font-weight: 600; font-size: 0.75rem;">
                <span style="color: var(--text-title); font-weight: 800; background: #f1f5f9; padding: 2px 8px; border-radius: 4px;">{{ $classroom->name }}</span>
                <span style="width: 3px; height: 3px; background: #e2e8f0; border-radius: 50%;"></span>
                <span style="font-weight: 700;">{{ $subject->code }}</span>
                <span style="width: 3px; height: 3px; background: #e2e8f0; border-radius: 50%;"></span>
                <span style="color: var(--brand-primary); font-weight: 800;">{{ $schedule->academicYear->name ?? $classroom->academicYear->name }}</span>
                <span style="width: 3px; height: 3px; background: #e2e8f0; border-radius: 50%;"></span>
                <span>{{ $students->count() }} SV</span>
            </div>
        </div>

        <form action="{{ route('teacher.students', $schedule->id) }}" method="GET" style="display: flex; gap: 8px; align-items: center;">
            <div style="width: 260px; position: relative;">
                <i data-lucide="search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 14px; color: var(--text-muted);"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên, mã SV, email..." 
                       style="width: 100%; padding: 6px 10px 6px 30px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.8rem; font-weight: 500; outline: none;">
            </div>
            <button type="submit" class="btn-pro btn-green" style="padding: 6px 12px; font-size: 0.75rem;">
                Tìm
            </button>
            @if(request()->has('search'))
                 <a href="{{ route('teacher.students', $schedule->id) }}" style="font-size: 0.7rem; color: #ef4444; font-weight: 700; text-decoration: none; margin-left: 4px;">Xóa</a>
            @endif
        </form>
    </div>
</div>

<div class="pro-card" style="padding: 0; overflow: hidden;">
    <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; background: #f9fafb; border-bottom: 1.5px solid #f1f5f9;">
                    <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 60px;">#</th>
                    <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 130px;">Mã SV</th>
                    <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px;">Họ tên sinh viên</th>
                    <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px;">Email cá nhân / Học viện</th>
                    <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 140px;">Điện thoại</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s;">
                    <td style="padding: 0.85rem 1rem; color: #94a3b8; font-weight: 600; font-size: 0.8rem;">{{ sprintf('%02d', $index + 1) }}</td>
                    <td style="padding: 0.85rem 1rem;">
                        <span style="font-weight: 700; color: var(--brand-primary); font-size: 0.8rem; letter-spacing: 0.2px;">{{ $student->student_code }}</span>
                    </td>
                    <td style="padding: 0.85rem 1rem;">
                        <div style="font-weight: 700; color: var(--text-title); font-size: 0.85rem;">{{ $student->name }}</div>
                    </td>
                    <td style="padding: 0.85rem 1rem; color: var(--text-body); font-size: 0.8rem;">{{ $student->email }}</td>
                    <td style="padding: 0.85rem 1rem; color: var(--text-body); font-weight: 600; font-size: 0.8rem;">{{ $student->phone }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 3.5rem; text-align: center; color: var(--text-muted);">
                        <i data-lucide="users" style="width: 40px; height: 40px; margin-bottom: 1rem; opacity: 0.2;"></i>
                        <p style="font-weight: 600; font-size: 0.85rem;">Chưa có dữ liệu sinh viên cho lớp này.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 1.5rem; display: flex; justify-content: flex-end;">
    <a href="{{ route('teacher.attendance', $schedule->id) }}" class="btn-pro btn-green" style="padding: 8px 18px; font-size: 0.8rem; border-radius: 8px;">
        <i data-lucide="user-check" style="width: 16px;"></i>
        <span>Tiến hành điểm danh</span>
    </a>
</div>
@endsection
