@extends('layouts.teacher')

@section('title', 'Quản lý điểm số')

@section('context-navbar')
    <a href="{{ route('teacher.students', $schedule->id) }}" class="context-tab">Danh sách sinh viên</a>
    <a href="{{ route('teacher.attendance', $schedule->id) }}" class="context-tab">Ghi nhận điểm danh</a>
    <a href="{{ route('teacher.grades', $schedule->id) }}" class="context-tab active">Nhập điểm</a>
@endsection

@section('content')
<div class="pro-card" style="margin-bottom: 1.25rem; padding: 1.25rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.15rem; font-weight: 800; color: var(--text-title); margin-bottom: 4px; letter-spacing: -0.5px;">Quản lý điểm số học phần</h2>
            <div style="display: flex; align-items: center; gap: 8px; color: var(--text-muted); font-weight: 600; font-size: 0.75rem;">
                <span style="color: var(--text-title); font-weight: 800; background: #f1f5f9; padding: 2px 8px; border-radius: 4px;">{{ $subject->name }}</span>
                <span style="width: 3px; height: 3px; background: #e2e8f0; border-radius: 50%;"></span>
                <span style="font-weight: 700;">Lớp {{ $classroom->name }}</span>
                <span style="width: 3px; height: 3px; background: #e2e8f0; border-radius: 50%;"></span>
                <span style="color: var(--brand-primary); font-weight: 800;">NH {{ $schedule->academicYear->name ?? $classroom->academicYear->name }}</span>
            </div>
        </div>

        <form action="{{ route('teacher.grades', $schedule->id) }}" method="GET" style="display: flex; gap: 8px; align-items: center;">
            <div style="width: 240px; position: relative;">
                <i data-lucide="search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 14px; color: var(--text-muted);"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên hoặc mã SV..." 
                       style="width: 100%; padding: 6px 10px 6px 30px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.8rem; font-weight: 500; outline: none;">
            </div>
            <button type="submit" class="btn-pro btn-green" style="padding: 6px 12px; font-size: 0.75rem;">
                Tìm
            </button>
        </form>
    </div>
</div>

<form action="{{ route('teacher.grades.save', $schedule->id) }}" method="POST">
    @csrf
    <div class="pro-card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; background: #f9fafb; border-bottom: 1.5px solid #f1f5f9;">
                        <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 60px;">#</th>
                        <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 130px;">Mã SV</th>
                        <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px;">Họ và tên sinh viên</th>
                        <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: center; width: 110px;">Chuyên cần</th>
                        <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: center; width: 100px;">Giữa kỳ</th>
                        <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: center; width: 100px;">Cuối kỳ</th>
                        <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: center; width: 80px;">Tổng kết</th>
                        <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: center; width: 70px;">Loại</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                    @php $grade = $existingGrades[$student->id] ?? null; @endphp
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s;">
                        <td style="padding: 0.85rem 1rem; color: #94a3b8; font-weight: 600; font-size: 0.8rem;">{{ sprintf('%02d', $index + 1) }}</td>
                        <td style="padding: 0.85rem 1rem;">
                            <span style="font-weight: 700; color: var(--text-title); font-size: 0.8rem; font-family: monospace; letter-spacing: 0.5px;">{{ $student->student_code }}</span>
                        </td>
                        <td style="padding: 0.85rem 1rem;">
                            <div style="font-weight: 700; color: var(--text-title); font-size: 0.85rem;">{{ $student->name }}</div>
                        </td>
                        <td style="padding: 0.85rem 1rem; text-align: center; font-weight: 800; color: #64748b; background: #f9fafb;">
                            {{ $grade?->attendance ?? 0 }}
                        </td>
                        <td style="padding: 0.6rem 0.8rem;">
                            <input type="number" step="0.1" name="grades[{{ $student->id }}][midterm]" value="{{ $grade?->midterm }}" 
                                   style="width: 100%; height: 28px; border-radius: 6px; border: 1px solid var(--border-light); text-align: center; font-weight: 700; font-size: 0.85rem; outline: none; color: var(--brand-primary);" min="0" max="10">
                        </td>
                        <td style="padding: 0.6rem 0.8rem;">
                            <input type="number" step="0.1" name="grades[{{ $student->id }}][final]" value="{{ $grade?->final }}" 
                                   style="width: 100%; height: 28px; border-radius: 6px; border: 1px solid var(--border-light); text-align: center; font-weight: 700; font-size: 0.85rem; outline: none; color: var(--brand-primary);" min="0" max="10">
                        </td>
                        <td style="padding: 0.85rem 1rem; text-align: center; font-weight: 900; color: var(--text-title); font-size: 0.85rem;">
                            {{ $grade?->total_score ?? '-' }}
                        </td>
                        <td style="padding: 0.85rem 1rem; text-align: center;">
                            @if($grade)
                                <span style="font-weight: 800; padding: 2px 8px; border-radius: 4px; font-size: 0.725rem; 
                                      @if($grade->grade_letter == 'A') background: #ecfdf5; color: #059669;
                                      @elseif($grade->grade_letter == 'F') background: #fff1f2; color: #e11d48;
                                      @else background: #eff6ff; color: #2563eb; @endif">
                                    {{ $grade->grade_letter }}
                                </span>
                            @else
                                <span style="color: #cbd5e1;">-</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.5rem;">
        <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600; font-style: italic;">
             <i data-lucide="info" style="width: 12px; margin-right: 2px;"></i> Quy tắc: Chuyên cần (10%), Giữa kỳ (30%), Cuối kỳ (60%).
        </div>
        <button type="submit" class="btn-pro btn-green" style="padding: 10px 40px; font-size: 0.85rem; border-radius: 10px; font-weight: 700;">
            <i data-lucide="save" style="width: 18px;"></i>
            <span>Lưu & Tính toán kết quả</span>
        </button>
    </div>
</form>
@endsection
