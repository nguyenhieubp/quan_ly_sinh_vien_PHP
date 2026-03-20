@extends('layouts.app')

@section('title', 'Nhập điểm: ' . $classroom->name)

@section('content')
<div class="card" style="padding: 0; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                <a href="{{ route('grades.index', ['department_id' => $classroom->department_id]) }}" style="text-decoration: none; color: #94a3b8; display: flex; align-items: center;" title="Quay lại">
                    <i data-lucide="arrow-left" style="width: 16px;"></i>
                </a>
                <h3 style="font-size: 1.15rem; color: var(--text-title); margin: 0; font-weight: 700;">Nhập điểm lớp {{ $classroom->name }}</h3>
            </div>
            <p style="color: var(--text-body); font-size: var(--fs-xs);">Quản lý điểm số cho môn học trong niên khóa đã chọn.</p>
        </div>
        
        <div style="display: flex; gap: 0.75rem;">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.4rem 0.75rem; border-radius: 6px; display: flex; align-items: center; gap: 6px;">
                <i data-lucide="layers" style="width: 14px; color: var(--brand-primary);"></i>
                <span style="font-size: 11px; font-weight: 700; color: #475569;">Lớp: {{ $classroom->code }}</span>
            </div>
        </div>
    </div>

    <!-- Selection Bar -->
    <div style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border-color); background: #fbfcfe;">
        <form action="{{ route('grades.bulk') }}" method="GET" id="context-form" style="display: flex; gap: 1.5rem; align-items: flex-end;">
            <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
            <input type="hidden" name="subject_id" value="{{ $subject->id }}">
            
            <div style="flex: 1;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Môn học học cụ thể</label>
                <div style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px; font-weight: 700; color: var(--brand-primary); background: #f8fafc;">
                    <i data-lucide="book" style="width: 14px; display: inline-block; vertical-align: middle; margin-right: 6px;"></i>
                    {{ $subject->name }} ({{ $subject->code }})
                </div>
            </div>

            <div style="flex: 1;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Niên khóa</label>
                <select name="academic_year_id" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px; font-weight: 600; color: var(--text-title); background: #f5f7ff; border-color: var(--brand-primary);">
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            Niên khóa {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="width: 120px;">
                <a href="{{ route('grades.index', ['department_id' => $classroom->department_id, 'classroom_id' => $classroom->id]) }}" 
                   style="width: 100%; display: inline-flex; align-items: center; justify-content: center; background: none; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px; font-size: 11px; font-weight: 600; color: #64748b; text-decoration: none;">
                    Đổi môn học
                </a>
            </div>
        </form>
    </div>

    @if($students->count() > 0)
        <form action="{{ route('grades.store_bulk') }}" method="POST">
            @csrf
            <input type="hidden" name="subject_id" value="{{ $subject->id }}">

            <div style="padding: 0.75rem 1.5rem; background: #eff6ff; border-bottom: 1px solid #dbeafe; display: flex; align-items: center; gap: 8px;">
                <i data-lucide="info" style="width: 14px; color: var(--brand-primary);"></i>
                <span style="font-size: 11px; font-weight: 600; color: #1e40af;">Có <b>{{ $students->count() }} sinh viên</b> đăng ký môn "{{ $subject->name }}" trong niên khóa này.</span>
            </div>

            <div class="table-responsive">
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 60px;">STT</th>
                            <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0;">SINH VIÊN</th>
                            <th style="padding: 0.75rem 1rem; text-align: center; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 120px;">CHUYÊN CẦN</th>
                            <th style="padding: 0.75rem 1rem; text-align: center; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 120px;">GIỮA KỲ</th>
                            <th style="padding: 0.75rem 1rem; text-align: center; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 120px;">CUỐI KỲ</th>
                            <th style="padding: 0.75rem 1rem; text-align: center; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 100px;">TỔNG KẾT</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                            @php 
                                $sId = $student->id;
                                $semId = $student->registration_semester_id;
                                $grade = $existingGrades["{$sId}_{$semId}"] ?? null; 
                            @endphp
                            <tr class="table-row" style="border-bottom: 1px solid #f1f5f9;">
                                <td style="padding: 0.85rem 1rem; font-weight: 700; color: #94a3b8; font-family: monospace; font-size: var(--fs-xs);">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </td>
                                <td style="padding: 0.85rem 1rem;">
                                    <div style="font-weight: 700; color: var(--text-title); font-size: var(--fs-sm);">{{ $student->name }}</div>
                                    <div style="font-size: 11px; color: #94a3b8;">
                                        <span style="font-family: monospace;">MSV: {{ $student->student_code }}</span>
                                        <span style="margin: 0 4px;">•</span>
                                        <span style="font-weight: 600; color: var(--brand-primary);">{{ $student->registration_semester_name }}</span>
                                    </div>
                                    <input type="hidden" name="grades[{{ $index }}][student_id]" value="{{ $student->id }}">
                                    <input type="hidden" name="grades[{{ $index }}][semester_id]" value="{{ $student->registration_semester_id }}">
                                </td>
                                <td style="padding: 0.85rem 1rem; text-align: center; position: relative;">
                                    @php 
                                        $calculatedScore = $calculatedAttendanceScores["{$sId}_{$semId}"] ?? null;
                                        $displayScore = $grade ? $grade->attendance : $calculatedScore;
                                        $isAutoMapped = !$grade && $calculatedScore !== null;
                                    @endphp
                                    <input type="number" step="0.1" name="grades[{{ $index }}][attendance]" value="{{ $displayScore }}" placeholder="-" class="grade-input" data-index="{{ $index }}"
                                        style="width: 70px; padding: 6px; border-radius: 6px; border: 1px solid {{ $isAutoMapped ? 'var(--brand-primary)' : '#e2e8f0' }}; text-align: center; font-weight: 700; font-size: 13px; background: {{ $isAutoMapped ? '#f5f7ff' : '#fdfdfd' }};"
                                        title="{{ $isAutoMapped ? 'Điểm được tự động tính từ lịch sử điểm danh' : '' }}">
                                    @if($isAutoMapped)
                                        <div style="position: absolute; top: 4px; right: 4px; width: 6px; height: 6px; background: var(--brand-primary); border-radius: 50%;" title="Tự động map"></div>
                                    @endif
                                </td>
                                <td style="padding: 0.85rem 1rem; text-align: center;">
                                    <input type="number" step="0.1" name="grades[{{ $index }}][midterm]" value="{{ $grade ? $grade->midterm : '' }}" placeholder="-" class="grade-input" data-index="{{ $index }}"
                                        style="width: 70px; padding: 6px; border-radius: 6px; border: 1px solid #e2e8f0; text-align: center; font-weight: 700; font-size: 13px; background: #fdfdfd;">
                                </td>
                                <td style="padding: 0.85rem 1rem; text-align: center;">
                                    <input type="number" step="0.1" name="grades[{{ $index }}][final]" value="{{ $grade ? $grade->final : '' }}" placeholder="-" class="grade-input" data-index="{{ $index }}"
                                        style="width: 70px; padding: 6px; border-radius: 6px; border: 1px solid #e2e8f0; text-align: center; font-weight: 700; font-size: 13px; background: #fdfdfd;">
                                </td>
                                <td style="padding: 0.85rem 1rem; text-align: center;">
                                    <span id="total-{{ $index }}" style="font-weight: 800; color: var(--brand-primary); font-size: var(--fs-sm);">
                                        {{ $grade ? number_format($grade->total_score, 1) : '—' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div style="padding: 1.5rem; background: #ffffff; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; align-items: center; gap: 1rem;">
                <p style="margin: 0; font-size: 11px; color: #94a3b8; font-weight: 500;">
                    <i data-lucide="info" style="width: 12px; display: inline-block; vertical-align: middle; margin-right: 4px;"></i>
                    Hệ thống sẽ lưu điểm cho từng sinh viên theo đúng học kỳ đã đăng ký.
                </p>
                <button type="submit" class="btn btn-primary" style="padding: 0.6rem 2rem; font-weight: 700; font-size: var(--fs-xs); display: flex; align-items: center; gap: 8px; border-radius: 6px;">
                    <i data-lucide="check-check" style="width: 16px;"></i>
                    Xác nhận & Lưu bảng điểm
                </button>
            </div>
        </form>
    @else
        <div style="padding: 5rem 2rem; text-align: center; background: #ffffff;">
            <div style="width: 48px; height: 48px; background: #fef2f2; color: #ef4444; border-radius: 12px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto;">
                <i data-lucide="user-minus" style="width: 24px;"></i>
            </div>
            <h4 style="font-size: 1rem; font-weight: 700; color: var(--text-title); margin: 0;">Không có sinh viên đăng ký</h4>
            <p style="color: #64748b; font-size: 13px; margin-top: 0.5rem; max-width: 400px; margin-left: auto; margin-right: auto;">
                Hiện tại chưa có sinh viên nào trong lớp <b>{{ $classroom->name }}</b> đăng ký học phần môn <b>{{ $subject->name }}</b> trong niên khóa này.
            </p>
            <div style="margin-top: 1.5rem;">
                <a href="{{ route('grades.index') }}" style="font-size: 12px; font-weight: 700; color: var(--brand-primary); text-decoration: none;">&larr; Quay lại danh sách lớp</a>
            </div>
        </div>
    @endif
</div>

<style>
    .table-row:hover { background: #fbfcfe; }
    .grade-input:focus { border-color: var(--brand-primary) !important; outline: none; box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1); background: white !important; }
</style>

<script>
    lucide.createIcons();

    function calculateTotal(index) {
        const attInput = document.querySelector(`input[name="grades[${index}][attendance]"]`);
        const midInput = document.querySelector(`input[name="grades[${index}][midterm]"]`);
        const finInput = document.querySelector(`input[name="grades[${index}][final]"]`);
        
        if (!attInput || !midInput || !finInput) return;

        const att = parseFloat(attInput.value) || 0;
        const mid = parseFloat(midInput.value) || 0;
        const fin = parseFloat(finInput.value) || 0;

        const total = (att * 0.1) + (mid * 0.3) + (fin * 0.6);
        const totalEl = document.getElementById(`total-${index}`);
        if (totalEl) {
            totalEl.innerText = total.toFixed(1);
        }
    }

    document.querySelectorAll('.grade-input').forEach(input => {
        input.addEventListener('input', () => {
            calculateTotal(input.dataset.index);
        });
    });

    window.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('input[name$="[student_id]"]').forEach((input, index) => {
            calculateTotal(index);
        });
    });
</script>
@endsection
