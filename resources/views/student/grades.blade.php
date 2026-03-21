@extends('layouts.student')

@section('title', 'Kết quả học tập')

@section('content')
<!-- Filter Section -->
<div class="pro-card" style="margin-bottom: 2rem; padding: 1.25rem;">
    <form action="{{ route('student.grades') }}" method="GET" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
        <div style="flex: 1; min-width: 200px;">
            <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Niên khóa</label>
            <div style="position: relative;">
                <i data-lucide="award" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 14px; color: var(--text-muted);"></i>
                <select name="academic_year_id" onchange="this.form.submit()" style="width: 100%; padding: 7px 12px 7px 34px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.8rem; font-weight: 600; color: var(--text-title); background: white; outline: none; cursor: pointer; appearance: none;">
                    <option value="">Tất cả niên khóa</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            {{ $year->name }}
                        </option>
                    @endforeach
                </select>
                <i data-lucide="chevron-down" style="position: absolute; right: 10px; top: 50%; transform: translateY(-50%); width: 12px; color: var(--text-muted); pointer-events: none;"></i>
            </div>
        </div>

        <div style="flex: 2; min-width: 250px;">
            <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Tìm kiếm môn học</label>
            <div style="position: relative;">
                <i data-lucide="search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 14px; color: var(--text-muted);"></i>
                <input type="text" name="search" value="{{ $search }}" placeholder="Nhập tên hoặc mã môn học..." style="width: 100%; padding: 7px 12px 7px 34px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.8rem; font-weight: 500; outline: none;">
            </div>
        </div>

        <button type="submit" class="btn-pro btn-primary" style="padding: 7px 20px;">
            <i data-lucide="filter" style="width: 14px;"></i>
            Lọc kết quả
        </button>
        
        @if($selectedYearId || $search)
            <a href="{{ route('student.grades') }}" style="font-size: 0.75rem; color: #ef4444; font-weight: 700; text-decoration: none; margin-left: 4px;">Xóa lọc</a>
        @endif
    </form>
</div>

<!-- Grades Content -->
<div style="display: flex; flex-direction: column; gap: 1.5rem;">
    @forelse($grades as $semesterName => $semesterGrades)
        <div class="pro-card" style="padding: 0; overflow: hidden;">
            <div style="background: #f9fafb; padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; display: flex; align-items: center; justify-content: space-between;">
                <h3 style="font-size: 1rem; font-weight: 800; color: var(--text-title); display: flex; align-items: center; gap: 8px;" class="brand-font">
                    <i data-lucide="layers" style="width: 18px; color: var(--brand-primary); stroke-width: 2.5px;"></i>
                    {{ $semesterName }}
                </h3>
                <span style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted); background: white; padding: 4px 10px; border-radius: 50px; border: 1px solid var(--border-light); text-transform: uppercase; letter-spacing: 0.5px;">
                    {{ $semesterGrades->count() }} môn học
                </span>
            </div>

            <div class="table-responsive">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="text-align: left; background: white; border-bottom: 1px solid #f1f5f9;">
                            <th style="padding: 0.85rem 1.25rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px;">Môn học & Tín chỉ</th>
                            <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: center;">Chuyên cần</th>
                            <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: center;">Giữa kỳ</th>
                            <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: center;">Cuối kỳ</th>
                            <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: center; width: 120px;">Tổng điểm</th>
                            <th style="padding: 0.85rem 1.25rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: center; width: 80px;">Điểm chữ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($semesterGrades as $grade)
                            <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s;">
                                <td style="padding: 1rem 1.25rem;">
                                    <div style="font-weight: 700; color: var(--text-title); font-size: 0.85rem;">{{ $grade->subject->name }}</div>
                                    <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600;">{{ $grade->subject->code }} • {{ $grade->subject->credits }} tín chỉ</div>
                                </td>
                                <td style="padding: 1rem 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; color: var(--text-title);">
                                    {{ $grade->attendance ?? '-' }}
                                </td>
                                <td style="padding: 1rem 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; color: var(--text-title);">
                                    {{ $grade->midterm ?? '-' }}
                                </td>
                                <td style="padding: 1rem 1rem; text-align: center; font-size: 0.85rem; font-weight: 700; color: var(--text-title);">
                                    {{ $grade->final ?? '-' }}
                                </td>
                                <td style="padding: 1rem 1rem; text-align: center;">
                                    <span style="font-family: 'Outfit', sans-serif; font-size: 1.1rem; font-weight: 800; color: {{ ($grade->total_score >= 5) ? '#10b981' : '#ef4444' }};">
                                        {{ $grade->total_score ?? '-' }}
                                    </span>
                                </td>
                                <td style="padding: 1rem 1.25rem; text-align: center;">
                                    @if($grade->grade_letter)
                                        <div style="width: 32px; height: 32px; border-radius: 8px; background: {{ in_array($grade->grade_letter, ['A', 'B+', 'B']) ? '#ecfdf5' : '#fff1f2' }}; color: {{ in_array($grade->grade_letter, ['A', 'B+', 'B']) ? '#059669' : '#e11d48' }}; display: flex; align-items: center; justify-content: center; font-weight: 800; border: 1.5px solid {{ in_array($grade->grade_letter, ['A', 'B+', 'B']) ? '#10b98133' : '#f43f5e33' }}; margin: 0 auto; font-size: 0.8rem; font-family: 'Outfit', sans-serif;">
                                            {{ $grade->grade_letter }}
                                        </div>
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
    @empty
        <div class="pro-card" style="padding: 4rem; text-align: center; color: var(--text-muted);">
            <i data-lucide="award" style="width: 40px; height: 40px; margin-bottom: 1rem; opacity: 0.2;"></i>
            <h4 style="font-size: 1rem; font-weight: 800; color: var(--text-title); margin-bottom: 4px;">Chưa có kết quả học tập</h4>
            <p style="color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">Dữ liệu điểm số của bạn chưa được cập nhật trong hệ thống.</p>
        </div>
    @endforelse
</div>
@endsection
