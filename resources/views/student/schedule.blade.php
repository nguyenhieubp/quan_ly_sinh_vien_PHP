@extends('layouts.student')

@section('title', 'Lịch học & Tiến độ')

@section('content')
<div style="margin-bottom: 2rem;">
    <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.5rem;">Thời khóa biểu & Tiến độ</h3>
    <p style="font-size: 0.95rem; color: var(--text-body);">Theo dõi lịch học cố định và lịch sử điểm danh của các học phần đang tham gia.</p>
</div>

<div class="card" style="padding: 0; overflow: hidden; border-radius: 16px;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 1px solid var(--border-color);">
                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; width: 60px;">STT</th>
                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Môn học</th>
                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Lớp & Phòng</th>
                <th style="padding: 1rem 1.5rem; text-align: center; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Tiến độ</th>
                <th style="padding: 1rem 1.5rem; text-align: right; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($schedules as $index => $schedule)
                @php
                    $periodsPerSession = (int)$schedule->end_period - (int)$schedule->start_period + 1;
                    $totalSessions = $periodsPerSession > 0 ? ceil((int)$schedule->total_periods / $periodsPerSession) : 0;
                    $completedSessions = \App\Models\Attendance::where('schedule_id', $schedule->id)
                        ->where('student_id', Auth::guard('student')->user()->id)
                        ->distinct('attendance_date')
                        ->count();
                    $progressPercent = $totalSessions > 0 ? min(100, ($completedSessions / $totalSessions) * 100) : 0;
                @endphp
                
                <!-- Main Row -->
                <tr style="border-bottom: 1px solid var(--border-color); cursor: pointer; transition: background 0.2s;" 
                    onclick="toggleAttendance('att-{{ $index }}', this)">
                    <td style="padding: 1.25rem 1.5rem; font-size: 0.9rem; font-weight: 700; color: #94a3b8;">{{ $index + 1 }}</td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="font-size: 0.95rem; font-weight: 700; color: var(--text-title);">{{ $schedule->subject->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--brand-primary); font-weight: 700;">{{ $schedule->subject->code }}</div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="font-size: 0.9rem; font-weight: 600; color: #64748b;">{{ $schedule->classroom->name }}</div>
                        <div style="font-size: 0.8rem; color: #94a3b8;">Phòng: {{ $schedule->room }} | Tiết: {{ $schedule->start_period }}-{{ $schedule->end_period }}</div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; width: 220px;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 700; margin-bottom: 6px;">
                            <span style="color: #64748b;">Đã học: {{ $completedSessions }}/{{ $totalSessions }}</span>
                            <span style="color: var(--brand-primary);">{{ round($progressPercent) }}%</span>
                        </div>
                        <div style="width: 100%; height: 6px; background: #e2e8f0; border-radius: 10px; overflow: hidden;">
                            <div style="width: {{ $progressPercent }}%; height: 100%; background: var(--brand-primary); border-radius: 10px;"></div>
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: right;">
                        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                            <span id="label-att-{{ $index }}" style="font-size: 0.8rem; font-weight: 700; color: var(--brand-primary);">Lịch sử điểm danh</span>
                            <i data-lucide="chevron-down" id="icon-att-{{ $index }}" style="width: 18px; color: var(--brand-primary); transition: transform 0.3s;"></i>
                        </div>
                    </td>
                </tr>

                <!-- Attendance Detail Row -->
                <tr id="att-{{ $index }}" style="display: none; background: #fcfdfe;">
                    <td colspan="5" style="padding: 2rem 3rem; border-bottom: 1px solid var(--border-color);">
                        <h5 style="font-size: 0.85rem; font-weight: 800; color: var(--text-title); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px;">
                            <i data-lucide="history" style="width: 16px;"></i> Chi tiết điểm danh & Buổi học
                        </h5>

                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 10px;">
                            @php
                                $attendances = \App\Models\Attendance::where('schedule_id', $schedule->id)
                                    ->where('student_id', Auth::guard('student')->user()->id)
                                    ->orderBy('attendance_date', 'asc')
                                    ->get();
                            @endphp

                            @forelse($attendances as $att)
                                <div style="padding: 12px; border-radius: 12px; border: 1.5px solid var(--border-color); background: #fff; transition: all 0.2s;">
                                    <div style="font-size: 0.75rem; color: #94a3b8; font-weight: 700; margin-bottom: 6px;">{{ \Carbon\Carbon::parse($att->attendance_date)->format('d/m/Y') }}</div>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span style="font-size: 0.85rem; font-weight: 800; color: var(--text-title);">Buổi {{ $att->session_number }}</span>
                                        @if($att->status == 'present')
                                            <span title="Có mặt" style="width: 8px; height: 8px; background: #22c55e; border-radius: 50%; box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.1);"></span>
                                        @elseif($att->status == 'late')
                                            <span title="Muộn" style="width: 8px; height: 8px; background: #eab308; border-radius: 50%; box-shadow: 0 0 0 4px rgba(234, 179, 8, 0.1);"></span>
                                        @else
                                            <span title="Vắng" style="width: 8px; height: 8px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);"></span>
                                        @endif
                                    </div>
                                    <div style="margin-top: 4px; font-size: 0.7rem; font-weight: 600; color: {{ $att->status == 'present' ? '#16a34a' : ($att->status == 'late' ? '#ca8a04' : '#dc2626') }}">
                                        {{ $att->status == 'present' ? 'Hiện diện' : ($att->status == 'late' ? 'Đi muộn' : 'Vắng mặt') }}
                                    </div>
                                </div>
                            @empty
                                <div style="grid-column: 1 / -1; padding: 2rem; text-align: center; background: #f8fafc; border-radius: 12px; border: 1.5px dashed var(--border-color);">
                                    <p style="color: #94a3b8; font-size: 0.85rem; font-weight: 600; margin: 0;">Chưa có dữ liệu điểm danh được ghi nhận cho môn học này.</p>
                                </div>
                            @endforelse
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" style="padding: 5rem; text-align: center;">
                        <i data-lucide="calendar-x" style="width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 1.5rem;"></i>
                        <h4 style="font-size: 1.1rem; font-weight: 800; color: #64748b;">Không tìm thấy lịch học</h4>
                        <p style="color: #94a3b8; font-size: 0.9rem;">Bạn hiện đang không có lịch học nào được ghi nhận trong hệ thống.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function toggleAttendance(id, row) {
        const detail = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        const label = document.getElementById('label-' + id);

        if (detail.style.display === 'none') {
            detail.style.display = 'table-row';
            row.style.background = '#f8fafc';
            icon.style.transform = 'rotate(180deg)';
            label.innerText = 'Thu gọn';
        } else {
            detail.style.display = 'none';
            row.style.background = 'white';
            icon.style.transform = 'rotate(0)';
            label.innerText = 'Lịch sử điểm danh';
        }
    }
</script>

<style>
    tr:hover td {
        background: #f8fafc;
    }
</style>
@endsection
