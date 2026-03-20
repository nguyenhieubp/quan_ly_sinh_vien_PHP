@extends('layouts.portal')

@section('content')
<div class="card">
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Lịch học cá nhân</h3>
            <p style="font-size: 0.85rem; color: #64748b;">Thời khóa biểu chi tiết cho các học phần đã đăng ký.</p>
        </div>
        <button onclick="window.print()" class="btn btn-outline" style="font-size: 0.8rem;">
            <i data-lucide="printer" style="width: 16px;"></i> In lịch học
        </button>
    </div>

    <div style="display: grid; gap: 1rem;">
        @php $days = ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7']; @endphp
        @for($i = 1; $i <= 6; $i++)
            <div style="padding: 1rem; background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0;">
                <h4 style="font-size: 0.9rem; margin-bottom: 1rem; color: var(--brand-primary); display: flex; align-items: center; gap: 0.5rem;">
                    <i data-lucide="calendar-days" style="width: 16px;"></i> {{ $days[$i] }}
                </h4>
                <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                    @php $daySchedules = $schedules->where('day_of_week', $i); @endphp
                    @forelse($daySchedules as $sche)
                        <div style="background: white; padding: 1rem; border-radius: 10px; border: 1px solid #eef2ff; border-left: 4px solid var(--brand-primary); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <div style="font-weight: 600; font-size: 0.95rem;">{{ $sche->subject->name }}</div>
                                <div style="font-size: 0.8rem; color: #64748b;">GV: {{ $sche->teacher->name }} | {{ $sche->subject->code }}</div>
                            </div>
                            <div style="text-align: right;">
                                <div style="font-weight: 600; color: var(--text-title);">{{ \Carbon\Carbon::parse($sche->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sche->end_time)->format('H:i') }}</div>
                                <div style="font-size: 0.75rem; color: #94a3b8;">Phòng: {{ $sche->room }}</div>
                            </div>
                        </div>
                    @empty
                        <div style="font-size: 0.8rem; color: #94a3b8; font-style: italic;">Không có lịch học.</div>
                    @endforelse
                </div>
            </div>
        @endfor
    </div>
</div>
@endsection
