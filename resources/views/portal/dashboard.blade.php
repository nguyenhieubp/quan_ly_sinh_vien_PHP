@extends('layouts.portal')

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="card" style="display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 56px; height: 56px; background: #fef3c7; color: #d97706; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="star" style="width: 28px;"></i>
        </div>
        <div>
            <span style="font-size: 0.8rem; color: #64748b; font-weight: 500;">GPA Hiện tại (hệ 4)</span>
            <h2 style="font-size: 1.75rem;">{{ number_format($gpa, 2) }}</h2>
        </div>
    </div>
    
    <div class="card" style="display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 56px; height: 56px; background: #e0f2fe; color: #0284c7; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="book-check" style="width: 28px;"></i>
        </div>
        <div>
            <span style="font-size: 0.8rem; color: #64748b; font-weight: 500;">Tín chỉ tích lũy</span>
            <h2 style="font-size: 1.75rem;">{{ $credits }}</h2>
        </div>
    </div>

    <div class="card" style="display: flex; align-items: center; gap: 1.5rem;">
        <div style="width: 56px; height: 56px; background: #ecfdf5; color: #059669; border-radius: 12px; display: flex; align-items: center; justify-content: center;">
            <i data-lucide="calendar" style="width: 28px;"></i>
        </div>
        <div>
            <span style="font-size: 0.8rem; color: #64748b; font-weight: 500;">Môn học học kỳ này</span>
            <h2 style="font-size: 1.75rem;">{{ $schedules->groupBy('subject_id')->count() }}</h2>
        </div>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 1.25rem; font-size: 1.1rem;">Lịch học tuần này</h3>
    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid var(--border-color);">
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">Thứ</th>
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">Thời gian</th>
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">Môn học</th>
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">Giảng viên</th>
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">Phòng</th>
                </tr>
            </thead>
            <tbody>
                @php $days = ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7']; @endphp
                @forelse($schedules as $sche)
                <tr style="border-bottom: 1px solid #f8fafc;">
                    <td style="padding: 1rem; font-weight: 600;">{{ $days[$sche->day_of_week] }}</td>
                    <td style="padding: 1rem;">{{ \Carbon\Carbon::parse($sche->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sche->end_time)->format('H:i') }}</td>
                    <td style="padding: 1rem; font-weight: 500;">{{ $sche->subject->name }}</td>
                    <td style="padding: 1rem; color: #64748b;">{{ $sche->teacher->name }}</td>
                    <td style="padding: 1rem;"><span class="badge badge-info">{{ $sche->room }}</span></td>
                </tr>
                @empty
                <tr><td colspan="5" style="padding: 2rem; text-align: center; color: #94a3b8;">Chưa có lịch học được sắp xếp.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
