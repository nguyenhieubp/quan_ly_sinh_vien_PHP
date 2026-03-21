@extends('layouts.teacher')

@section('title', request('mode') == 'grades' ? 'Quản lý Điểm số' : 'Lịch dạy & Điểm danh')

@section('content')
<div class="pro-card" style="margin-bottom: 1rem;">
    <form action="{{ route('teacher.subjects') }}" method="GET" style="display: flex; gap: 10px; align-items: center; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 250px; position: relative;">
            <i data-lucide="search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; color: var(--text-muted);"></i>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm môn học, mã môn, lớp..." 
                   style="width: 100%; padding: 8px 12px 8px 36px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.85rem; font-weight: 500;">
        </div>
        
        <div style="width: 180px;">
            <select name="year" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.85rem; font-weight: 600; background: white;">
                <option value="">-- Tất cả năm học --</option>
                @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}" {{ request('year') == $ay->id ? 'selected' : '' }}>Năm học {{ $ay->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="btn-pro btn-green" style="padding: 8px 16px; font-size: 0.85rem;">
            <i data-lucide="filter" style="width: 16px;"></i>
            Lọc dữ liệu
        </button>
        
        @if(request()->hasAny(['search', 'year']))
            <a href="{{ route('teacher.subjects') }}" style="font-size: 0.8rem; color: #ef4444; font-weight: 700; text-decoration: none;">Xóa lọc</a>
        @endif
    </form>
</div>

<div class="pro-card" style="padding: 0;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
            <thead>
                <tr style="text-align: left; background: #f8fafc; border-bottom: 1.5px solid #f1f5f9;">
                    <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; width: 120px;">Mã môn</th>
                    <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem;">Tên môn học</th>
                    <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; width: 100px;">Lớp</th>
                    <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; width: 150px;">Lịch học</th>
                    <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; width: 100px;">Phòng</th>
                    <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; width: 100px;">Năm học</th>
                    <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; width: 60px;">TC</th>
                    <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; text-align: right; width: 220px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $s)
                <tr style="border-bottom: 1px solid #f8fafc; transition: background 0.1s;">
                    <td style="padding: 10px 1rem; font-weight: 800; color: var(--brand-primary);">{{ $s->subject->code }}</td>
                    <td style="padding: 10px 1rem; font-weight: 700; color: var(--text-title); font-size: 0.9rem;">{{ $s->subject->name }}</td>
                    <td style="padding: 10px 1rem; font-weight: 700; color: var(--text-title);">{{ $s->classroom->name }}</td>
                    <td style="padding: 10px 1rem;">
                        <span style="font-weight: 700; color: var(--text-title);">Thứ {{ $s->day_of_week + 1 }}</span>
                        <span style="color: var(--text-muted); font-weight: 600; font-size: 0.8rem;">(Ca {{ $s->start_period }}-{{ $s->end_period }})</span>
                    </td>
                    <td style="padding: 10px 1rem; font-weight: 600; color: var(--text-title);">{{ $s->room }}</td>
                    <td style="padding: 10px 1rem; font-weight: 800; color: var(--brand-primary); font-size: 0.8rem;">{{ $s->academicYear->name ?? $s->classroom->academicYear->name }}</td>
                    <td style="padding: 10px 1rem; font-weight: 600; color: var(--text-title);">{{ $s->subject->credits }}</td>
                    <td style="padding: 10px 1rem; text-align: right;">
                        <div style="display: flex; gap: 6px; justify-content: flex-end;">
                            @if(request('mode') != 'grades')
                                <a href="{{ route('teacher.students', $s->id) }}" class="btn-pro" style="padding: 5px 10px; font-size: 0.7rem; background: #f1f5f9; color: var(--text-title); border: 1px solid #e2e8f0;">
                                    <i data-lucide="users" style="width: 12px;"></i> SV
                                </a>
                                <a href="{{ route('teacher.attendance', $s->id) }}" class="btn-pro btn-green" style="padding: 5px 10px; font-size: 0.7rem;">
                                    <i data-lucide="user-check" style="width: 12px;"></i> Điểm danh
                                </a>
                            @else
                                <a href="{{ route('teacher.grades', $s->id) }}" class="btn-pro" style="padding: 5px 10px; font-size: 0.7rem; background: #fffbeb; color: #92400e; border: 1px solid #fde68a;">
                                    <i data-lucide="award" style="width: 12px;"></i> Nhập điểm thi
                                </a>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        <p style="font-weight: 700;">Không tìm thấy môn học nào phù hợp.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 1rem;">
    {{ $schedules->appends(request()->query())->links() }}
</div>
@endsection
吐
吐
吐
吐
吐
