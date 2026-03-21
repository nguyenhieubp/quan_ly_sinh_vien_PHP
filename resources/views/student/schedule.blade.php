@extends('layouts.student')

@section('title', 'TKB & Tiến độ học tập')

@section('content')
<div style="margin-bottom: 2rem;">
    <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.5rem; letter-spacing: -0.5px;">Thời khóa biểu & Tiến độ</h3>
    <p style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">Theo dõi lịch học cố định và lịch sử điểm danh của các học phần đang tham gia.</p>
</div>

<div class="pro-card" style="padding: 0; overflow: hidden;">
    <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; background: #f9fafb; border-bottom: 1.5px solid #f1f5f9;">
                    <th style="padding: 0.85rem 1.25rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 60px;">#</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px;">Môn học & Mã HP</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px;">Lớp & Phòng</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: center; width: 220px;">Tiến độ hoàn thành</th>
                    <th style="padding: 0.85rem 1.25rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: right; width: 180px;">Hành động</th>
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
                <tr style="border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: all 0.2s;" 
                    onclick="toggleAttendance('att-{{ $index }}', this)">
                    <td style="padding: 1.25rem 1.25rem; color: #94a3b8; font-weight: 600; font-size: 0.8rem;">{{ sprintf('%02d', $index + 1) }}</td>
                    <td style="padding: 1.25rem 1rem;">
                        <div style="font-weight: 700; color: var(--text-title); font-size: 0.85rem;">{{ $schedule->subject->name }}</div>
                        <div style="font-size: 0.7rem; color: var(--brand-primary); font-weight: 700;">{{ $schedule->subject->code }}</div>
                    </td>
                    <td style="padding: 1.25rem 1rem;">
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-title);">{{ $schedule->classroom->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600;">Phòng: {{ $schedule->room }} • Tiết: {{ $schedule->start_period }}-{{ $schedule->end_period }}</div>
                    </td>
                    <td style="padding: 1.25rem 1rem;">
                        <div style="display: flex; justify-content: space-between; font-size: 0.7rem; font-weight: 800; margin-bottom: 6px;">
                            <span style="color: #64748b;">Đã học: {{ $completedSessions }}/{{ $totalSessions }}</span>
                            <span style="color: var(--brand-primary);">{{ round($progressPercent) }}%</span>
                        </div>
                        <div style="width: 100%; height: 5px; background: #f1f5f9; border-radius: 10px; overflow: hidden; border: 1px solid #f1f5f9;">
                            <div style="width: {{ $progressPercent }}%; height: 100%; background: var(--brand-primary); border-radius: 10px;"></div>
                        </div>
                    </td>
                    <td style="padding: 1.25rem 1.25rem; text-align: right;">
                        <button class="btn-pro" style="padding: 4px 10px; font-size: 0.7rem; background: #f8fafc; color: var(--brand-primary); border: 1px solid var(--border-light); border-radius: 6px;">
                            <span id="label-att-{{ $index }}">Chi tiết điểm danh</span>
                            <i data-lucide="chevron-down" id="icon-att-{{ $index }}" style="width: 14px; stroke-width: 3px; transition: transform 0.3s;"></i>
                        </button>
                    </td>
                </tr>

                <!-- Attendance Detail Row -->
                <tr id="att-{{ $index }}" style="display: none; background: #fafbfc;">
                    <td colspan="5" style="padding: 1.5rem 2.5rem; border-bottom: 1px solid #f1f5f9;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem;">
                            <h5 style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">
                                <i data-lucide="history" style="width: 12px; vertical-align: middle;"></i> Lịch sử hiện diện
                            </h5>
                            <div style="flex: 1; height: 1px; background: #f1f5f9;"></div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(150px, 1fr)); gap: 0.75rem;">
                            @php
                                $attendances = \App\Models\Attendance::where('schedule_id', $schedule->id)
                                    ->where('student_id', Auth::guard('student')->user()->id)
                                    ->orderBy('attendance_date', 'asc')
                                    ->get();
                            @endphp

                            @forelse($attendances as $att)
                                <div style="padding: 0.75rem; border-radius: 10px; border: 1px solid #f1f5f9; background: white; transition: all 0.2s;">
                                    <div style="font-size: 0.65rem; color: #94a3b8; font-weight: 700; margin-bottom: 6px;">{{ \Carbon\Carbon::parse($att->attendance_date)->format('d/m/Y') }}</div>
                                    <div style="display: flex; align-items: center; justify-content: space-between;">
                                        <span style="font-size: 0.8rem; font-weight: 800; color: var(--text-title);">Buổi {{ $att->session_number }}</span>
                                        @if($att->status == 'present')
                                            <span title="Có mặt" style="width: 6px; height: 6px; background: #10b981; border-radius: 50%; box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);"></span>
                                        @elseif($att->status == 'late')
                                            <span title="Muộn" style="width: 6px; height: 6px; background: #f59e0b; border-radius: 50%; box-shadow: 0 0 0 4px rgba(245, 158, 11, 0.1);"></span>
                                        @else
                                            <span title="Vắng" style="width: 6px; height: 6px; background: #ef4444; border-radius: 50%; box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);"></span>
                                        @endif
                                    </div>
                                    <div style="margin-top: 4px; font-size: 0.65rem; font-weight: 700; color: {{ $att->status == 'present' ? '#059669' : ($att->status == 'late' ? '#d97706' : '#dc2626') }}">
                                        {{ $att->status == 'present' ? 'CÓ MẶT' : ($att->status == 'late' ? 'ĐI MUỘN' : 'VẮNG MẶT') }}
                                    </div>
                                </div>
                            @empty
                                <div style="grid-column: 1 / -1; padding: 1.5rem; text-align: center; background: #f9fafb; border-radius: 10px; border: 1px dashed #e2e8f0;">
                                    <p style="color: #94a3b8; font-size: 0.75rem; font-weight: 600; margin: 0;">Chưa có dữ liệu điểm danh.</p>
                                </div>
                            @endforelse
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="padding: 4rem; text-align: center; color: var(--text-muted);">
                        <i data-lucide="calendar-x" style="width: 40px; height: 40px; margin-bottom: 1rem; opacity: 0.2;"></i>
                        <h4 style="font-size: 1rem; font-weight: 800; color: var(--text-title); margin-bottom: 4px;">Không tìm thấy lịch học</h4>
                        <p style="color: var(--text-muted); font-size: 0.85rem; font-weight: 500;">Bạn hiện đang không có lịch học nào được ghi nhận.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleAttendance(id, row) {
        const detail = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        const label = document.getElementById('label-' + id);

        if (detail.style.display === 'none') {
            detail.style.display = 'table-row';
            row.style.background = '#f9fafb';
            icon.style.transform = 'rotate(180deg)';
            label.innerText = 'Thu gọn';
        } else {
            detail.style.display = 'none';
            row.style.background = 'white';
            icon.style.transform = 'rotate(0)';
            label.innerText = 'Chi tiết điểm danh';
        }
    }
</script>
@endsection
