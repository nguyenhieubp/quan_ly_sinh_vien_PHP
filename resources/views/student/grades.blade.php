@extends('layouts.student')

@section('title', 'Kết quả học tập')

@section('content')
<!-- Filter Section -->
<div class="card" style="margin-bottom: 2rem; padding: 1.5rem; border-radius: 16px; background: white; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
    <form action="{{ route('student.grades') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
        <div style="flex: 1; min-width: 240px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 8px; margin-left: 4px;">Niên khóa</label>
            <div style="position: relative;">
                <select name="academic_year_id" onchange="this.form.submit()" style="width: 100%; padding: 0.75rem 1rem; border-radius: 12px; border: 1.5px solid var(--border-color); font-size: 0.95rem; font-weight: 600; color: var(--text-title); appearance: none; background: white; cursor: pointer; transition: all 0.2s;">
                    <option value="">Tất cả niên khóa</option>
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            Niên khóa {{ $year->name }}
                        </option>
                    @endforeach
                </select>
                <i data-lucide="chevron-down" style="position: absolute; right: 1rem; top: 50%; transform: translateY(-50%); width: 18px; color: #94a3b8; pointer-events: none;"></i>
            </div>
        </div>

        <div style="flex: 2; min-width: 300px;">
            <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 8px; margin-left: 4px;">Tìm kiếm môn học</label>
            <div style="position: relative;">
                <input type="text" name="search" value="{{ $search }}" placeholder="Nhập tên hoặc mã môn học..." style="width: 100%; padding: 0.75rem 1rem 0.75rem 2.8rem; border-radius: 12px; border: 1.5px solid var(--border-color); font-size: 0.95rem; font-weight: 600; color: var(--text-title); transition: all 0.2s;" onfocus="this.style.borderColor='var(--brand-primary)'; this.style.boxShadow='0 0 0 4px var(--brand-primary-light)'" onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'">
                <i data-lucide="search" style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); width: 18px; color: #94a3b8;"></i>
            </div>
        </div>

        <button type="submit" style="padding: 0.75rem 1.5rem; background: var(--brand-primary); color: white; border: none; border-radius: 12px; font-weight: 700; font-size: 0.95rem; cursor: pointer; display: flex; align-items: center; gap: 8px; transition: all 0.2s; height: 48px;">
            <i data-lucide="filter" style="width: 18px;"></i>
            Lọc kết quả
        </button>
        
        @if($selectedYearId || $search)
            <a href="{{ route('student.grades') }}" style="padding: 0.75rem 1rem; background: #f1f5f9; color: #64748b; border: none; border-radius: 12px; font-weight: 700; font-size: 0.9rem; text-decoration: none; display: flex; align-items: center; gap: 8px; transition: all 0.2s; height: 48px;">
                <i data-lucide="rotate-ccw" style="width: 16px;"></i>
                Xóa lọc
            </a>
        @endif
    </form>
</div>

<div style="display: flex; flex-direction: column; gap: 2rem;">
    @forelse($grades as $semesterName => $semesterGrades)
        <div class="card" style="padding: 0; overflow: hidden; border-radius: 16px;">
            <div style="background: #f8fafc; padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between;">
                <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-title); display: flex; align-items: center; gap: 10px;">
                    <i data-lucide="layers" style="width: 20px; color: var(--brand-primary);"></i>
                    {{ $semesterName }}
                </h3>
                <span style="font-size: 0.8rem; font-weight: 700; color: #64748b; background: white; padding: 6px 14px; border-radius: 50px; border: 1.5px solid var(--border-color);">
                    {{ $semesterGrades->count() }} môn học
                </span>
            </div>

            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 1rem 1.5rem; text-align: left; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Môn học</th>
                        <th style="padding: 1rem 1.5rem; text-align: center; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Chuyên cần</th>
                        <th style="padding: 1rem 1.5rem; text-align: center; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Giữa kỳ</th>
                        <th style="padding: 1rem 1.5rem; text-align: center; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Cuối kỳ</th>
                        <th style="padding: 1rem 1.5rem; text-align: center; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Tổng điểm</th>
                        <th style="padding: 1rem 1.5rem; text-align: center; font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase;">Điểm chữ</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($semesterGrades as $grade)
                        <tr style="border-bottom: 1px solid var(--border-color); transition: background 0.2s;" onmouseover="this.style.background='#fcfdfe'" onmouseout="this.style.background='white'">
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="font-size: 0.95rem; font-weight: 700; color: var(--text-title);">{{ $grade->subject->name }}</div>
                                <div style="font-size: 0.8rem; color: #94a3b8; font-weight: 600;">{{ $grade->subject->code }} • {{ $grade->subject->credits }} tín chỉ</div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem; text-align: center; font-size: 0.95rem; font-weight: 700; color: var(--text-title);">
                                {{ $grade->attendance ?? '-' }}
                            </td>
                            <td style="padding: 1.25rem 1.5rem; text-align: center; font-size: 0.95rem; font-weight: 700; color: var(--text-title);">
                                {{ $grade->midterm ?? '-' }}
                            </td>
                            <td style="padding: 1.25rem 1.5rem; text-align: center; font-size: 0.95rem; font-weight: 700; color: var(--text-title);">
                                {{ $grade->final ?? '-' }}
                            </td>
                            <td style="padding: 1.25rem 1.5rem; text-align: center;">
                                <span style="font-size: 1.1rem; font-weight: 800; color: {{ ($grade->total_score >= 5) ? '#059669' : '#e11d48' }};">
                                    {{ $grade->total_score ?? '-' }}
                                </span>
                            </td>
                            <td style="padding: 1.25rem 1.5rem; text-align: center;">
                                @if($grade->grade_letter)
                                    <div style="width: 36px; height: 36px; border-radius: 10px; background: {{ in_array($grade->grade_letter, ['A', 'B+', 'B']) ? '#ecfdf5' : '#fff1f2' }}; color: {{ in_array($grade->grade_letter, ['A', 'B+', 'B']) ? '#059669' : '#e11d48' }}; display: flex; align-items: center; justify-content: center; font-weight: 800; border: 1.5px solid {{ in_array($grade->grade_letter, ['A', 'B+', 'B']) ? '#10b98133' : '#f43f5e33' }}; margin: 0 auto; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
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
    @empty
        <div class="card" style="padding: 5rem; text-align: center; border-radius: 16px;">
            <div style="background: #f8fafc; width: 64px; height: 64px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i data-lucide="award" style="width: 32px; color: #cbd5e1;"></i>
            </div>
            <h4 style="font-size: 1.1rem; font-weight: 800; color: #64748b;">Chưa có kết quả học tập</h4>
            <p style="color: #94a3b8; font-size: 0.9rem;">Không tìm thấy kết quả phù hợp với tiêu chí lọc hoặc dữ liệu chưa được cập nhật.</p>
        </div>
    @endforelse
</div>
@endsection
