@extends('layouts.teacher')

@section('title', 'Thời khóa biểu giảng dạy')

@section('content')
<div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h3 style="font-size: 1.25rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.25rem; letter-spacing: -0.5px;">Thời khóa biểu & Tiến độ</h3>
        <p style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">Theo dõi lịch dạy cố định và tình trạng ghi nhận điểm danh.</p>
    </div>

    <div class="pro-card" style="padding: 0.75rem 1rem; margin-bottom: 0; background: white;">
        <form action="{{ route('teacher.schedule') }}" method="GET" style="display: flex; align-items: center; gap: 8px;">
            <label style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Năm học:</label>
            <select name="year" onchange="this.form.submit()" style="padding: 5px 10px; border-radius: 6px; border: 1px solid var(--border-light); font-size: 0.8rem; font-weight: 600; background: white; min-width: 140px; outline: none; cursor: pointer;">
                <option value="">Tất cả</option>
                @foreach($academicYears as $ay)
                    <option value="{{ $ay->id }}" {{ request('year') == $ay->id ? 'selected' : '' }}>{{ $ay->name }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

<div class="pro-card" style="padding: 0; overflow: hidden;">
    <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f9fafb; border-bottom: 1.5px solid #f1f5f9;">
                    <th style="padding: 0.85rem 1.25rem; text-align: left; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; width: 50px;">#</th>
                    <th style="padding: 0.85rem 1rem; text-align: left; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Môn học</th>
                    <th style="padding: 0.85rem 1rem; text-align: left; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase;">Lớp & Phòng</th>
                    <th style="padding: 0.85rem 1rem; text-align: center; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; width: 220px;">Tiến độ giảng dạy</th>
                    <th style="padding: 0.85rem 1.25rem; text-align: right; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; width: 160px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($schedules as $index => $s)
                    @php
                        $periodsPerSession = (int)$s->end_period - (int)$s->start_period + 1;
                        $totalSessions = $periodsPerSession > 0 ? ceil((int)$s->total_periods / $periodsPerSession) : 0;
                        
                        $completedSessions = \App\Models\Attendance::where('schedule_id', $s->id)
                            ->distinct('attendance_date')
                            ->count();
                            
                        $progressPercent = $totalSessions > 0 ? min(100, ($completedSessions / $totalSessions) * 100) : 0;
                    @endphp
                    
                    <tr style="border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: background 0.2s;" 
                        onclick="toggleDetail('detail-{{ $index }}', this)">
                        <td style="padding: 1rem 1.25rem; font-size: 0.85rem; font-weight: 600; color: #94a3b8;">{{ $index + 1 }}</td>
                        <td style="padding: 1rem 1rem;">
                            <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-title);">{{ $s->subject->name }}</div>
                            <div style="font-size: 0.7rem; color: var(--brand-primary); font-weight: 700; display: inline-block; background: #ecfdf5; padding: 1px 6px; border-radius: 4px; margin-top: 2px;">{{ $s->subject->code }}</div>
                        </td>
                        <td style="padding: 1rem 1rem;">
                            <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-title); margin-bottom: 2px;">Lớp {{ $s->classroom->name }}</div>
                            <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">Phòng {{ $s->room }} • Thứ {{ $s->day_of_week+1 }} (Ca {{ $s->start_period }}-{{ $s->end_period }})</div>
                        </td>
                        <td style="padding: 1rem 1rem;">
                            <div style="display: flex; justify-content: space-between; font-size: 0.7rem; font-weight: 700; margin-bottom: 4px;">
                                <span style="color: var(--text-muted);">{{ $completedSessions }}/{{ $totalSessions }} buổi</span>
                                <span style="color: var(--brand-primary);">{{ round($progressPercent) }}%</span>
                            </div>
                            <div style="width: 100%; height: 5px; background: #f1f5f9; border-radius: 10px; overflow: hidden;">
                                <div style="width: {{ $progressPercent }}%; height: 100%; background: var(--brand-primary); border-radius: 10px;"></div>
                            </div>
                        </td>
                        <td style="padding: 1rem 1.25rem; text-align: right;">
                            <div style="display: flex; align-items: center; justify-content: flex-end; gap: 6px;">
                                <span id="label-detail-{{ $index }}" style="font-size: 0.75rem; font-weight: 700; color: var(--brand-primary);">Lịch sử</span>
                                <i data-lucide="chevron-down" id="icon-detail-{{ $index }}" style="width: 14px; color: var(--brand-primary); transition: transform 0.2s;"></i>
                            </div>
                        </td>
                    </tr>

                    <tr id="detail-{{ $index }}" style="display: none; background: #fcfdfe;">
                        <td colspan="5" style="padding: 1.5rem 2rem; border-bottom: 1px solid #f1f5f9;">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                                <h5 style="font-size: 0.75rem; font-weight: 800; color: var(--text-title); text-transform: uppercase; letter-spacing: 0.5px; display: flex; align-items: center; gap: 8px; margin: 0;">
                                    <i data-lucide="history" style="width: 14px; color: var(--brand-primary);"></i> 
                                    Lịch sử giảng dạy
                                </h5>
                                <div style="display: flex; gap: 8px;">
                                    <a href="{{ route('teacher.attendance', $s->id) }}" class="btn-pro btn-green" style="padding: 4px 10px; font-size: 0.7rem; border-radius: 6px;">
                                        <i data-lucide="plus" style="width: 12px;"></i> Điểm danh
                                    </a>
                                </div>
                            </div>

                            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 10px;">
                                @php
                                    $completedDates = \App\Models\Attendance::where('schedule_id', $s->id)
                                        ->select('attendance_date', 'session_number')
                                        ->distinct()
                                        ->orderBy('attendance_date', 'asc')
                                        ->get();
                                @endphp

                                @forelse($completedDates as $date)
                                    <div style="padding: 10px 12px; border-radius: 10px; border: 1px solid #e2e8f0; background: #fff; position: relative; overflow: hidden;">
                                        <div style="position: absolute; left: 0; top: 0; bottom: 0; width: 3px; background: #10b981;"></div>
                                        <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600; margin-bottom: 4px;">{{ \Carbon\Carbon::parse($date->attendance_date)->format('d/m/Y') }}</div>
                                        <div style="display: flex; align-items: center; justify-content: space-between;">
                                            <span style="font-size: 0.8rem; font-weight: 700; color: var(--text-title);">Buổi {{ $date->session_number }}</span>
                                            <div style="width: 18px; height: 18px; background: #ecfdf5; color: #10b981; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                <i data-lucide="check" style="width: 12px;"></i>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div style="grid-column: 1 / -1; padding: 2rem; text-align: center; border: 1.5px dashed #e2e8f0; border-radius: 10px;">
                                        <p style="color: #94a3b8; font-size: 0.75rem; font-weight: 600; margin: 0;">Chưa ghi nhận điểm danh.</p>
                                    </div>
                                @endforelse
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding: 4rem; text-align: center; color: var(--text-muted);">
                            <i data-lucide="calendar-x" style="width: 48px; height: 48px; margin-bottom: 1rem; opacity: 0.2;"></i>
                            <p style="font-weight: 600; font-size: 0.85rem;">Không có lịch dạy nào được tìm thấy.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleDetail(id, row) {
        const detailRow = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        const label = document.getElementById('label-' + id);
        
        if (detailRow.style.display === 'none') {
            detailRow.style.display = 'table-row';
            icon.style.transform = 'rotate(180deg)';
            label.textContent = 'Đóng';
            row.style.background = '#f8fafc';
        } else {
            detailRow.style.display = 'none';
            icon.style.transform = 'rotate(0deg)';
            label.textContent = 'Lịch sử';
            row.style.background = 'transparent';
        }
    }
</script>
@endsection
