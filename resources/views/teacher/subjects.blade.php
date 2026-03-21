@extends('layouts.teacher')

@section('title', request('mode') == 'grades' ? 'Quản lý Điểm số' : 'Lịch dạy & Điểm danh')

@section('content')
<div class="pro-card" style="margin-bottom: 1.25rem; padding: 1.25rem;">
    <form action="{{ route('teacher.subjects') }}" method="GET" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 250px; position: relative;">
            <i data-lucide="search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 14px; color: var(--text-muted);"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm môn học, mã môn, lớp..." 
                   style="width: 100%; padding: 7px 12px 7px 34px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.8rem; font-weight: 500; outline: none; transition: border-color 0.2s;">
        </div>
        
        <div style="width: 180px;">
            <select name="year" style="width: 100%; padding: 7px 12px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.8rem; font-weight: 600; background: white; outline: none; cursor: pointer;">
                <option value="">Tất cả năm học</option>
                @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}" {{ request('year') == $ay->id ? 'selected' : '' }}>Năm học {{ $ay->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-pro btn-green" style="padding: 7px 16px; font-size: 0.8rem;">
            <i data-lucide="filter" style="width: 14px;"></i>
            Lọc
        </button>
        
        @if(request()->hasAny(['search', 'year']))
            <a href="{{ route('teacher.subjects') }}" style="font-size: 0.75rem; color: #ef4444; font-weight: 700; text-decoration: none; margin-left: 4px;">Xóa lọc</a>
        @endif
    </form>
</div>

<div class="pro-card" style="padding: 0; overflow: hidden;">
    <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; background: #f9fafb; border-bottom: 1.5px solid #f1f5f9;">
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 110px;">Mã môn</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px;">Tên môn học</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 90px;">Lớp</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 160px;">Lịch học</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 90px;">Phòng</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 100px;">Năm học</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 50px; text-align: center;">TC</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: right; width: 220px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $s)
                <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s;">
                    <td style="padding: 0.9rem 1rem;">
                        <span style="font-weight: 700; color: var(--brand-primary); font-size: 0.75rem; background: #ecfdf5; padding: 2px 8px; border-radius: 4px;">{{ $s->subject->code }}</span>
                    </td>
                    <td style="padding: 0.9rem 1rem;">
                        <div style="font-weight: 700; color: var(--text-title); font-size: 0.85rem;">{{ $s->subject->name }}</div>
                    </td>
                    <td style="padding: 0.9rem 1rem;">
                        <div style="font-weight: 700; color: var(--text-title); font-size: 0.85rem;">{{ $s->classroom->name }}</div>
                    </td>
                    <td style="padding: 0.9rem 1rem;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-title);">Thứ {{ $s->day_of_week + 1 }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600;">Ca {{ $s->start_period }}-{{ $s->end_period }}</div>
                    </td>
                    <td style="padding: 0.9rem 1rem;">
                        <div style="font-size: 0.8rem; font-weight: 600; color: var(--text-title);">{{ $s->room }}</div>
                    </td>
                    <td style="padding: 0.9rem 1rem;">
                        <div style="font-size: 0.75rem; font-weight: 700; color: #64748b; background: #f1f5f9; padding: 2px 8px; border-radius: 4px; display: inline-block;">{{ $s->academicYear->name ?? $s->classroom->academicYear->name }}</div>
                    </td>
                    <td style="padding: 0.9rem 1rem; text-align: center;">
                        <div style="font-size: 0.8rem; font-weight: 600; color: var(--text-title);">{{ $s->subject->credits }}</div>
                    </td>
                    <td style="padding: 0.9rem 1rem; text-align: right;">
                        <div style="display: flex; gap: 6px; justify-content: flex-end;">
                            @if(request('mode') != 'grades')
                                <a href="{{ route('teacher.students', $s->id) }}" class="btn-pro" style="padding: 5px 10px; font-size: 0.7rem; background: white; color: var(--text-title); border: 1px solid var(--border-light); border-radius: 6px;">
                                    <i data-lucide="users" style="width: 14px;"></i> DS SV
                                </a>
                                <a href="{{ route('teacher.attendance', $s->id) }}" class="btn-pro btn-green" style="padding: 5px 10px; font-size: 0.7rem; border-radius: 6px;">
                                    <i data-lucide="user-check" style="width: 14px;"></i> Điểm danh
                                </a>
                            @else
                                <a href="{{ route('teacher.grades', $s->id) }}" class="btn-pro" style="padding: 5px 10px; font-size: 0.7rem; background: #fff7ed; color: #f97316; border: 1px solid #ffedd5; border-radius: 6px;">
                                    <i data-lucide="award" style="width: 14px;"></i> Nhập điểm
                                </a>
                                <a href="{{ route('teacher.attendance.report', $s->id) }}" class="btn-pro" style="padding: 5px 10px; font-size: 0.7rem; background: #eff6ff; color: #3b82f6; border: 1px solid #dbeafe; border-radius: 6px;">
                                    <i data-lucide="file-text" style="width: 14px;"></i> Báo cáo
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding: 3.5rem; text-align: center; color: var(--text-muted);">
                        <i data-lucide="search-x" style="width: 40px; height: 40px; margin-bottom: 1rem; opacity: 0.2;"></i>
                        <p style="font-weight: 600; font-size: 0.85rem;">Không tìm thấy môn học nào phù hợp với tìm kiếm.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@if($schedules->hasPages())
<div style="margin-top: 1.5rem;">
    {{ $schedules->appends(request()->query())->links() }}
</div>
@endif
@endsection
