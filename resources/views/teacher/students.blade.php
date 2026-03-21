@section('title', 'Danh sách lớp')

@section('context-navbar')
    <a href="{{ route('teacher.students', $schedule->id) }}" class="context-tab active">Danh sách sinh viên</a>
    <a href="{{ route('teacher.attendance', $schedule->id) }}" class="context-tab">Ghi nhận điểm danh</a>
    <a href="{{ route('teacher.grades', $schedule->id) }}" class="context-tab">Nhập điểm Giữa kỳ & Cuối kỳ</a>
@endsection

@section('content')
<div class="pro-card" style="margin-bottom: 1rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--text-title); margin-bottom: 2px;">{{ $subject->name }}</h2>
            <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-weight: 600; font-size: 0.8rem;">
                <span style="color: var(--text-title); font-weight: 800;">Lớp {{ $classroom->name }}</span>
                <span style="width: 3px; height: 3px; background: #e2e8f0; border-radius: 50%;"></span>
                <span>{{ $subject->subject_code }}</span>
                <span style="width: 3px; height: 3px; background: #e2e8f0; border-radius: 50%;"></span>
                <span style="color: var(--brand-primary); font-weight: 800;">{{ $schedule->academicYear->name ?? $classroom->academicYear->name }}</span>
            </div>
        </div>

        <form action="{{ route('teacher.students', $schedule->id) }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <div style="width: 280px; position: relative;">
                <i data-lucide="search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 14px; color: var(--text-muted);"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm kiếm tên, mã SV, email..." 
                       style="width: 100%; padding: 6px 10px 6px 30px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.8rem; font-weight: 500;">
            </div>
            <button type="submit" class="btn-pro btn-green" style="padding: 6px 12px; font-size: 0.8rem;">
                Tìm
            </button>
            @if(request()->has('search'))
                 <a href="{{ route('teacher.students', $schedule->id) }}" style="font-size: 0.75rem; color: #ef4444; font-weight: 700; text-decoration: none;">Xóa</a>
            @endif
        </form>
    </div>
</div>

<div class="pro-card" style="padding: 0;">
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
            <thead>
                <tr style="text-align: left; background: #f8fafc; border-bottom: 1.5px solid #f1f5f9;">
                    <th style="padding: 8px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; width: 50px;">STT</th>
                    <th style="padding: 8px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; width: 120px;">Mã sinh viên</th>
                    <th style="padding: 8px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem;">Họ và tên</th>
                    <th style="padding: 8px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem;">Email học viện</th>
                    <th style="padding: 8px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; width: 130px;">Số điện thoại</th>
                </tr>
            </thead>
            <tbody>
                @forelse($students as $index => $student)
                <tr style="border-bottom: 1px solid #f8fafc; transition: background 0.1s;">
                    <td style="padding: 8px 1rem; color: var(--text-muted); font-weight: 700;">{{ sprintf('%02d', $index + 1) }}</td>
                    <td style="padding: 8px 1rem; font-weight: 800; color: var(--brand-primary); letter-spacing: 0.5px;">{{ $student->student_code }}</td>
                    <td style="padding: 8px 1rem; font-weight: 700; color: var(--text-title); font-size: 0.9rem;">{{ $student->name }}</td>
                    <td style="padding: 8px 1rem; color: var(--text-body); font-weight: 500;">{{ $student->email }}</td>
                    <td style="padding: 8px 1rem; color: var(--text-body); font-weight: 500;">{{ $student->phone }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 3rem; text-align: center; color: var(--text-muted);">
                        <p style="font-weight: 700;">Không có sinh viên nào khớp với tìm kiếm.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 1.5rem; display: flex; justify-content: flex-end;">
    <a href="{{ route('teacher.attendance', $schedule->id) }}" class="btn-pro btn-green" style="padding: 10px 24px; font-size: 0.9rem; border-radius: 8px; box-shadow: 0 4px 12px rgba(5, 150, 105, 0.15);">
        <i data-lucide="user-check" style="width: 18px;"></i>
        <span>Bắt đầu điểm danh lớp này</span>
    </a>
</div>
@endsection
吐
吐
吐
吐
吐
