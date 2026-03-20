@extends('layouts.student')

@section('title', 'Lịch học chi tiết')

@section('content')
<div style="margin-bottom: 2rem;">
    <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.5rem;">Cấu hình & Lịch học chi tiết</h3>
    <p style="font-size: 0.95rem; color: var(--text-body);">Chi tiết lộ trình, ngày bắt đầu và kết thúc của các học phần trong <strong>{{ $activeSemester->name }}</strong></p>
</div>

<div class="card" style="padding: 0; overflow: hidden; border-radius: 16px;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 1px solid var(--border-color);">
                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; width: 60px;">STT</th>
                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Môn học</th>
                <th style="padding: 1rem 1.5rem; text-align: center; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Số tín chỉ</th>
                <th style="padding: 1rem 1.5rem; text-align: left; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Giảng viên</th>
                <th style="padding: 1rem 1.5rem; text-align: center; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Thời gian học</th>
                <th style="padding: 1rem 1.5rem; text-align: right; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @forelse($subjectSchedules as $index => $data)
                <!-- Main Row -->
                <tr style="border-bottom: 1px solid var(--border-color); cursor: pointer; transition: background 0.2s;" 
                    onclick="toggleDetail('detail-{{ $index }}', this)">
                    <td style="padding: 1.25rem 1.5rem; font-size: 0.9rem; font-weight: 700; color: #94a3b8;">{{ $index + 1 }}</td>
                    <td style="padding: 1.25rem 1.5rem;">
                        <div style="font-size: 0.95rem; font-weight: 700; color: var(--text-title);">{{ $data['subject']->name }}</div>
                        <div style="font-size: 0.75rem; color: var(--brand-primary); font-weight: 700;">{{ $data['subject']->code }}</div>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: center; font-size: 0.95rem; font-weight: 700; color: var(--text-title);">{{ $data['credits'] }}</td>
                    <td style="padding: 1.25rem 1.5rem; font-size: 0.9rem; font-weight: 600; color: #64748b;">
                        {{ $data['teacher']->name }}
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: center;">
                        <span style="font-size: 0.85rem; font-weight: 700; color: var(--brand-primary); display: block;">{{ $data['start_date'] }}</span>
                        <span style="font-size: 0.75rem; color: #94a3b8;">đến {{ $data['end_date'] }}</span>
                    </td>
                    <td style="padding: 1.25rem 1.5rem; text-align: right;">
                        <div style="display: flex; align-items: center; justify-content: flex-end; gap: 8px;">
                            <span id="label-{{ $index }}" style="font-size: 0.8rem; font-weight: 700; color: var(--brand-primary);">Xem chi tiết tiết</span>
                            <i data-lucide="chevron-down" id="icon-{{ $index }}" style="width: 18px; color: var(--brand-primary); transition: transform 0.3s;"></i>
                        </div>
                    </td>
                </tr>
                <!-- Detail Row -->
                <tr id="detail-{{ $index }}" style="display: none; background: #fcfdfe;">
                    <td colspan="6" style="padding: 2rem 3rem; border-bottom: 1px solid var(--border-color);">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem;">
                            <h5 style="font-size: 0.85rem; font-weight: 800; color: var(--text-title); text-transform: uppercase; letter-spacing: 0.5px;">Tiến độ: {{ $data['total_sessions'] }} buổi học</h5>
                            <div style="height: 1px; flex-grow: 1; background: var(--border-color); margin: 0 1.5rem;"></div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 1rem;">
                            @foreach($data['sessions'] as $session)
                                <div style="background: white; border: 1.5px solid var(--border-color); border-radius: 12px; padding: 0.85rem; display: flex; align-items: center; gap: 0.75rem; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
                                    <div style="width: 28px; height: 28px; background: var(--brand-primary-light); border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 800; color: var(--brand-primary);">
                                        {{ $session['index'] }}
                                    </div>
                                    <div style="flex-grow: 1;">
                                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-title);">{{ $session['date'] }}</div>
                                        <div style="display: flex; justify-content: space-between; font-size: 0.7rem; font-weight: 600; color: #94a3b8;">
                                            <span>{{ $session['day'] }}</span>
                                            <span>Phòng: {{ $session['room'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" style="padding: 4rem; text-align: center;">
                        <i data-lucide="inbox" style="width: 48px; height: 48px; color: #cbd5e1; margin-bottom: 1rem;"></i>
                        <p style="color: #94a3b8; font-weight: 600;">Hiện chưa có môn học nào được cấu hình lịch chi tiết.</p>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<script>
    function toggleDetail(id, row) {
        const detail = document.getElementById(id);
        const index = id.split('-')[1];
        const icon = document.getElementById('icon-' + index);
        const label = document.getElementById('label-' + index);

        if (detail.style.display === 'none') {
            detail.style.display = 'table-row';
            row.style.background = '#f8fafc';
            icon.style.transform = 'rotate(180deg)';
            label.innerText = 'Thu gọn';
        } else {
            detail.style.display = 'none';
            row.style.background = 'white';
            icon.style.transform = 'rotate(0)';
            label.innerText = 'Xem chi tiết tiết';
        }
    }
</script>

<style>
    tr:hover td {
        background: #f8fafc;
    }
</style>
@endsection
