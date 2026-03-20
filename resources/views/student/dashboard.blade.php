@extends('layouts.student')

@section('title', 'Bảng điều khiển')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Welcome Card -->
    <div class="card" style="grid-column: span 2; display: flex; align-items: center; gap: 2rem; background: linear-gradient(135deg, white, #f1f5f9); border-left: 4px solid var(--brand-primary);">
        <div style="width: 80px; height: 80px; background: var(--brand-primary-light); border-radius: 20px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="sparkles" style="width: 40px; color: var(--brand-primary);"></i>
        </div>
        <div style="flex-grow: 1;">
            <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.5rem;">Chào mừng quay trở lại, {{ $student->name }}!</h3>
            <p style="font-size: 0.95rem; color: var(--text-body);">Mã sinh viên: <span style="font-weight: 700; color: var(--brand-primary);">{{ $student->student_code }}</span> | Lớp: <span style="font-weight: 700; color: var(--brand-primary);">{{ $student->classroom->name }}</span></p>
        </div>
    </div>

    <!-- Stats -->
    <div class="card" style="display: flex; align-items: center; gap: 1rem;">
        <div style="width: 48px; height: 48px; background: #ecfdf5; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="book-open" style="color: #059669; width: 24px;"></i>
        </div>
        <div>
            <div style="font-size: 1.25rem; font-weight: 800; color: var(--text-title);">{{ $registrations->count() }}</div>
            <div style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: #94a3b8; letter-spacing: 0.5px;">Môn đăng ký</div>
        </div>
    </div>
</div>

<div class="card" style="padding: 1.5rem; margin-bottom: 1.5rem;">
    <form action="{{ route('student.dashboard') }}" method="GET" style="display: grid; grid-template-columns: 1fr 250px auto; align-items: flex-end; gap: 1.25rem;">
        <div>
            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 8px;">Tìm kiếm môn học</label>
            <div style="position: relative;">
                <i data-lucide="search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Nhập tên hoặc mã môn..."
                    style="width: 100%; padding: 10px 10px 10px 40px; border-radius: 10px; border: 1.5px solid var(--border-color); font-size: 0.9rem; font-weight: 600; color: var(--text-title); outline: none;">
            </div>
        </div>

        <div>
            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 8px;">Năm học</label>
            <select name="academic_year_id" 
                style="width: 100%; padding: 10px 12px; border-radius: 10px; border: 1.5px solid var(--border-color); font-size: 0.9rem; font-weight: 600; color: var(--text-title); outline: none;">
                <option value="">-- Tất cả năm --</option>
                @foreach($academicYears as $year)
                    <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                        {{ $year->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display: flex; gap: 8px;">
            <button type="submit" class="btn btn-primary" style="padding: 11px 24px;">Lọc dữ liệu</button>
            @if($search || $selectedYearId)
                <a href="{{ route('student.dashboard') }}" class="btn" style="background: white; border: 1.5px solid var(--border-color); color: #64748b; padding: 10px 24px;">Xóa lọc</a>
            @endif
        </div>
    </form>
</div>

<div class="card" style="padding: 0; overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 1px solid var(--border-color);">
                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">STT</th>
                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Mã môn</th>
                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Tên môn học</th>
                <th style="padding: 1rem 1.5rem; text-align: center; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Tín chi</th>
                <th style="padding: 1rem 1.5rem; text-align: right; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Thông tin khác</th>
                <th style="padding: 1rem 1.5rem; text-align: right; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrations as $index => $reg)
                <tr style="border-bottom: 1px solid var(--border-color);">
                    <td style="padding: 1rem 1.5rem; font-size: 0.9rem; font-weight: 600; color: #94a3b8;">{{ $index + 1 }}</td>
                    <td style="padding: 1rem 1.5rem; font-size: 0.9rem; font-weight: 700; color: var(--brand-primary);">{{ $reg->schedule->subject->code }}</td>
                    <td style="padding: 1rem 1.5rem; font-size: 0.9rem; font-weight: 600; color: var(--text-title);">{{ $reg->schedule->subject->name }}</td>
                    <td style="padding: 1rem 1.5rem; text-align: center; font-size: 0.9rem; font-weight: 700; color: var(--text-title);">{{ $reg->schedule->subject->credits }}</td>
                    <td style="padding: 1rem 1.5rem; text-align: right;">
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-title);">{{ $reg->schedule->academicYear->name ?? '-' }}</div>
                        <div style="font-size: 0.75rem; color: #94a3b8;">{{ $reg->semester->name ?? 'N/A' }}</div>
                    </td>
                    <td style="padding: 1rem 1.5rem; text-align: right;">
                        <a href="{{ route('student.schedule') }}" style="color: var(--brand-primary); font-size: 0.8rem; font-weight: 700; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;">
                            Chi tiết
                            <i data-lucide="chevron-right" style="width: 14px;"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 4rem; text-align: center;">
                        <div style="background: #f8fafc; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                            <i data-lucide="inbox" style="width: 32px; color: #cbd5e1;"></i>
                        </div>
                        <p style="color: #94a3b8; font-weight: 600;">Không tìm thấy đăng ký nào trong niên khóa này.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
