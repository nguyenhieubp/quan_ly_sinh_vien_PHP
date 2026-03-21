@extends('layouts.teacher')

@section('title', 'Thời khóa biểu giảng dạy')

@section('content')
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.5rem;">Thời khóa biểu & Tiến độ giảng dạy</h3>
        <p style="font-size: 0.95rem; color: var(--text-muted); font-weight: 500;">Theo dõi lịch dạy cố định và tình trạng ghi nhận điểm danh của các học phần.</p>
    </div>

    <div class="pro-card" style="padding: 10px 15px; margin-bottom: 0;">
        <form action="{{ route('teacher.schedule') }}" method="GET" style="display: flex; align-items: center; gap: 10px;">
            <label style="font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Năm học:</label>
            <select name="year" onchange="this.form.submit()" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 0.85rem; font-weight: 700; background: white; min-width: 150px;">
                <option value="">-- Tất cả --</option>
                @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}" {{ request('year') == $ay->id ? 'selected' : '' }}>{{ $ay->name }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

<div class="pro-card" style="padding: 0; overflow: hidden; border-radius: 12px; border: 1px solid #e2e8f0;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 1.5px solid #f1f5f9;">
                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; width: 60px;">STT</th>
                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Môn học</th>
                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Lớp & Phòng</th>
                <th style="padding: 1rem 1.5rem; text-align: center; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; width: 220px;">Tiến độ giảng dạy</th>
                <th style="padding: 1rem 1.5rem; text-align: right; font-size: 0.7rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; width: 180px;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $index => $s)
                @php
                    $periodsPerSession = (int)$s->end_period - (int)$s->start_period + 1;
                    $totalSessions = $periodsPerSession > 0 ? ceil((int)$s->total_periods / $periodsPerSession) : 0;
                    
                    // Count unique dates where attendance was recorded for this schedule
                    $completedSessions = \App\Models\Attendance::where('schedule_id', $s->id)
                        ->distinct('attendance_date')
                        ->count();
                        
                    $progressPercent = $totalSessions > 0 ? min(100, ($completedSessions / $totalSessions) * 100) : 0;
                @endphp
                
                <tr style="border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: background 0.2s;" 
                    onclick="toggleDetail('detail-{{ $index }}', this)">
                    <td style="padding: 1.15rem 1.5rem; font-size: 0.9rem; font-weight: 700; color: #94a3b8;">{{ $index + 1 }}</td>
                    <td style="padding: 1.15rem 1.5rem;">
                        <div style="font-size: 0.95rem; font-weight: 800; color: var(--text-title); margin-bottom: 2px;">{{ $s->subject->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--brand-primary); font-weight: 700; background: #eff6ff; display: inline-block; padding: 2px 8px; border-radius: 4px;">{{ $s->subject->code }}</div>
                    </td>
                    <td style="padding: 1.15rem 1.5rem;">
                        <div style="font-size: 0.9rem; font-weight: 700; color: var(--text-title); margin-bottom: 2px;">{{ $s->classroom->name }}</div>
                        <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600;">Phòng {{ $s->room }} • Thứ {{ $s->day_of_week+1 }} (Ca {{ $s->start_period }}-{{ $s->end_period }})</div>
                    </td>
                    <td style="padding: 1.15rem 1.5rem;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 800; margin-bottom: 6px;">
                            <span style="color: var(--text-muted);">Đã dạy: {{ $completedSessions }}/{{ $totalSessions }} buổi</span>
                            <span style="color: var(--brand-primary);">{{ round($progressPercent) }}%</span>
                        </div>
                        <div style="width: 100%; height: 7px; background: #f1f5f9; border-radius: 10px; overflow: hidden;">
                            <div style="width: {{ $progressPercent }}%; height: 100%; background: linear-gradient(90deg, var(--brand-primary), #60a5fa); border-radius: 10px;"></div>
                        </div>
                    </td>
                    <td style="padding: 1.15rem 1.5rem; text-align: right;">
                        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                            <span id="label-detail-{{ $index }}" style="font-size: 0.8rem; font-weight: 700; color: var(--brand-primary);">Lịch sử lên lớp</span>
                            <i data-lucide="chevron-down" id="icon-detail-{{ $index }}" style="width: 18px; color: var(--brand-primary); transition: transform 0.3s;"></i>
                        </div>
                    </td>
                </tr>

                <!-- Details Row -->
                <tr id="detail-{{ $index }}" style="display: none; background: #fafbfc;">
                    <td colspan="5" style="padding: 2rem 3rem; border-bottom: 1.5px solid #f1f5f9;">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                            <h5 style="font-size: 0.85rem; font-weight: 800; color: var(--text-title); text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 10px; margin: 0;">
                                <i data-lucide="history" style="width: 18px; color: var(--brand-primary);"></i> 
                                Các buổi học đã ghi nhận điểm danh
                            </h5>
                            <a href="{{ route('teacher.attendance', $s->id) }}" class="btn-pro btn-green" style="padding: 6px 14px; font-size: 0.75rem;">
                                <i data-lucide="plus-circle" style="width: 14px;"></i> Tiếp tục điểm danh
                            </a>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px;">
                            @php
                                $completedDates = \App\Models\Attendance::where('schedule_id', $s->id)
                                    ->select('attendance_date', 'session_number')
                                    ->distinct()
                                    ->orderBy('attendance_date', 'asc')
                                    ->get();
                            @endphp

                            @forelse($completedDates as $date)
                                <div style="padding: 14px; border-radius: 12px; border: 1px solid #e2e8f0; background: #fff; box-shadow: 0 2px 4px rgba(0,0,0,0.02); position: relative; overflow: hidden;">
                                    <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 4px; background: #22c55e;"></div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 700; margin-bottom: 6px;">{{ \Carbon\Carbon::parse($date->attendance_date)->format('d/m/Y') }}</div>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span style="font-size: 0.9rem; font-weight: 800; color: var(--text-title);">Buổi {{ $date->session_number }}</span>
                                        <div style="width: 22px; height: 22px; background: #f0fdf4; color: #16a34a; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                            <i data-lucide="check" style="width: 14px;"></i>
                                        </div>
                                    </div>
                                    <div style="margin-top: 6px; font-size: 0.7rem; font-weight: 700; color: #16a34a; text-transform: uppercase;">Đã hoàn thành</div>
                                </div>
                            @empty
                                <div style="grid-column: 1 / -1; padding: 3rem; text-align: center; background: #f8fafc; border-radius: 12px; border: 1.5px dashed #e2e8f0;">
                                    <i data-lucide="clipboard-x" style="width: 32px; height: 32px; color: #cbd5e1; margin-bottom: 1rem;"></i>
                                    <p style="color: #94a3b8; font-size: 0.85rem; font-weight: 700; margin: 0;">Chưa có buổi học nào được ghi nhận điểm danh.</p>
                                </div>
                            @endforelse
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 5rem; text-align: center;">
                        <div style="width: 64px; height: 64px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                            <i data-lucide="calendar-off" style="width: 32px; height: 32px; color: #cbd5e1;"></i>
                        </div>
                        <h4 style="font-size: 1.1rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.5rem;">Không tìm thấy thời khóa biểu</h4>
                        <p style="color: var(--text-muted); font-size: 0.9rem; font-weight: 500;">Bạn hiện đang không có lịch dạy nào được ghi nhận trong năm học này.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function toggleDetail(id, row) {
        const detail = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        const label = document.getElementById('label-' + id);

        if (detail.style.display === 'none') {
            detail.style.display = 'table-row';
            row.style.background = '#fcfdfe';
            icon.style.transform = 'rotate(180deg)';
            label.innerText = 'Thu gọn';
        } else {
            detail.style.display = 'none';
            row.style.background = 'white';
            icon.style.transform = 'rotate(0)';
            label.innerText = 'Lịch sử lên lớp';
        }
    }
</script>

<style>
    .pro-card tr:hover td {
        background: #f8fafc !important;
    }
</style>
@endsection
吐
吐
吐
吐
吐
