@extends('layouts.teacher')

@section('title', 'Quản lý điểm số')

@section('context-navbar')
    <a href="{{ route('teacher.students', $schedule->id) }}" class="context-tab">Danh sách sinh viên</a>
    <a href="{{ route('teacher.attendance', $schedule->id) }}" class="context-tab">Ghi nhận điểm danh</a>
    <a href="{{ route('teacher.grades', $schedule->id) }}" class="context-tab active">Nhập điểm Giữa kỳ & Cuối kỳ</a>
@endsection

@section('content')
<div class="pro-card" style="margin-bottom: 1rem;">
    <div style="display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--text-title); margin-bottom: 2px;">Nhập điểm Giữa kỳ & Cuối kỳ</h2>
            <div style="display: flex; align-items: center; gap: 0.5rem; color: var(--text-muted); font-weight: 600; font-size: 0.8rem;">
                <span style="color: var(--text-title); font-weight: 800;">{{ $subject->name }}</span>
                <span style="width: 3px; height: 3px; background: #e2e8f0; border-radius: 50%;"></span>
                <span>Lớp {{ $classroom->name }}</span>
                <span style="width: 3px; height: 3px; background: #e2e8f0; border-radius: 50%;"></span>
                <span style="color: var(--brand-primary); font-weight: 800;">{{ $schedule->academicYear->name ?? $classroom->academicYear->name }}</span>
            </div>
        </div>

        <form action="{{ route('teacher.grades', $schedule->id) }}" method="GET" style="display: flex; gap: 10px; align-items: center;">
            <div style="width: 250px; position: relative;">
                <i data-lucide="search" style="position: absolute; left: 10px; top: 50%; transform: translateY(-50%); width: 14px; color: var(--text-muted);"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Tìm tên hoặc mã SV..." 
                       style="width: 100%; padding: 6px 10px 6px 30px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.8rem; font-weight: 500;">
            </div>
            <button type="submit" class="btn-pro btn-green" style="padding: 6px 12px; font-size: 0.8rem;">
                Tìm
            </button>
        </form>
    </div>
</div>

<form action="{{ route('teacher.grades.save', $schedule->id) }}" method="POST">
    @csrf
    <div class="pro-card" style="padding: 0; overflow: hidden;">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                <thead>
                    <tr style="text-align: left; background: #f8fafc; border-bottom: 1.5px solid #f1f5f9;">
                        <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; width: 60px;">STT</th>
                        <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; width: 120px;">Mã SV</th>
                        <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem;">Họ và tên</th>
                        <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; text-align: center; width: 100px;">Chuyên cần (Auto)</th>
                        <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; text-align: center; width: 100px;">Giữa kỳ</th>
                        <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; text-align: center; width: 100px;">Cuối kỳ</th>
                        <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; text-align: center; width: 80px;">Tổng kết</th>
                        <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; text-align: center; width: 60px;">Loại</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                    @php $grade = $existingGrades[$student->id] ?? null; @endphp
                    <tr style="border-bottom: 1px solid #f8fafc;">
                        <td style="padding: 8px 1rem; color: var(--text-muted); font-weight: 700;">{{ sprintf('%02d', $index + 1) }}</td>
                        <td style="padding: 8px 1rem; font-weight: 800; color: var(--brand-primary);">{{ $student->student_code }}</td>
                        <td style="padding: 8px 1rem; font-weight: 700; color: var(--text-title);">{{ $student->name }}</td>
                        <td style="padding: 8px 1rem; text-align: center; font-weight: 800; color: var(--text-muted); background: #f8fafc;">
                            {{ $grade?->attendance ?? 0 }}
                        </td>
                        <td style="padding: 8px 1rem;">
                            <input type="number" step="0.1" name="grades[{{ $student->id }}][midterm]" value="{{ $grade?->midterm }}" 
                                   style="width: 100%; padding: 6px; border-radius: 4px; border: 1px solid #e2e8f0; text-align: center; font-weight: 700;" min="0" max="10">
                        </td>
                        <td style="padding: 8px 1rem;">
                            <input type="number" step="0.1" name="grades[{{ $student->id }}][final]" value="{{ $grade?->final }}" 
                                   style="width: 100%; padding: 6px; border-radius: 4px; border: 1px solid #e2e8f0; text-align: center; font-weight: 700;" min="0" max="10">
                        </td>
                        <td style="padding: 8px 1rem; text-align: center; font-weight: 800; color: var(--text-title);">
                            {{ $grade?->total_score ?? '-' }}
                        </td>
                        <td style="padding: 8px 1rem; text-align: center;">
                            @if($grade)
                                <span style="font-weight: 800; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; 
                                      @if($grade->grade_letter == 'A') background: #f0fdf4; color: #166534;
                                      @elseif($grade->grade_letter == 'F') background: #fef2f2; color: #991b1b;
                                      @else background: #eff6ff; color: #1e40af; @endif">
                                    {{ $grade->grade_letter }}
                                </span>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1rem;">
        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; font-style: italic;">
             * Quy tắc: Chuyên cần (10%), Giữa kỳ (30%), Cuối kỳ (60%).
        </div>
        <button type="submit" class="btn-pro btn-green" style="padding: 10px 40px; font-size: 0.9rem; border-radius: 8px;">
            <i data-lucide="save" style="width: 18px;"></i>
            <span>Lưu bảng điểm lớp học</span>
        </button>
    </div>
</form>
@endsection
吐
吐
吐
吐
吐
