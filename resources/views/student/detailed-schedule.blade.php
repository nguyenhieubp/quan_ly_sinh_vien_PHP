@extends('layouts.student')

@section('title', 'Lịch học chi tiết')

@section('content')
<div style="margin-bottom: 2rem;">
    <h3 style="font-size: 1.5rem; font-weight: 800; color: var(--text-title); margin-bottom: 0.5rem; letter-spacing: -0.5px;">Cấu hình & Lịch học chi tiết</h3>
    <p style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;">Chi tiết lộ trình, ngày bắt đầu và kết thúc của các học phần trong <strong>{{ $activeSemester->name }}</strong></p>
</div>

<div class="pro-card" style="padding: 0; overflow: hidden;">
    <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; background: #f9fafb; border-bottom: 1.5px solid #f1f5f9;">
                    <th style="padding: 0.85rem 1.25rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 60px;">#</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px;">Môn học & Mã HP</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: center; width: 80px;">Tín chỉ</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px;">Giảng viên</th>
                    <th style="padding: 0.85rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: center; width: 160px;">Thời gian học</th>
                    <th style="padding: 0.85rem 1.25rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: right; width: 180px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjectSchedules as $index => $data)
                <!-- Main Row -->
                <tr style="border-bottom: 1px solid #f1f5f9; cursor: pointer; transition: all 0.2s;" 
                    onclick="toggleDetail('detail-{{ $index }}', this)">
                    <td style="padding: 1rem 1.25rem; color: #94a3b8; font-weight: 600; font-size: 0.8rem;">{{ sprintf('%02d', $index + 1) }}</td>
                    <td style="padding: 1rem 1rem;">
                        <div style="font-weight: 700; color: var(--text-title); font-size: 0.85rem;">{{ $data['subject']->name }}</div>
                        <div style="font-size: 0.7rem; color: var(--brand-primary); font-weight: 700;">{{ $data['subject']->code }}</div>
                    </td>
                    <td style="padding: 1rem 1rem; text-align: center;">
                        <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-title);">{{ $data['credits'] }}</div>
                    </td>
                    <td style="padding: 1rem 1rem;">
                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-title);">{{ $data['teacher']->name }}</div>
                        <div style="font-size: 0.7rem; color: var(--text-muted); font-weight: 600;">Giảng viên</div>
                    </td>
                    <td style="padding: 1rem 1rem; text-align: center;">
                        <div style="font-size: 0.75rem; font-weight: 800; color: var(--brand-primary);">{{ $data['start_date'] }}</div>
                        <div style="font-size: 0.65rem; color: var(--text-muted); font-weight: 600;">đến {{ $data['end_date'] }}</div>
                    </td>
                    <td style="padding: 1rem 1.25rem; text-align: right;">
                        <button class="btn-pro" style="padding: 4px 10px; font-size: 0.7rem; background: #f8fafc; color: var(--brand-primary); border: 1px solid var(--border-light); border-radius: 6px;">
                            <span id="label-{{ $index }}">Xem chi tiết</span>
                            <i data-lucide="chevron-down" id="icon-{{ $index }}" style="width: 14px; stroke-width: 3px; transition: transform 0.3s;"></i>
                        </button>
                    </td>
                </tr>
                <!-- Detail Row -->
                <tr id="detail-{{ $index }}" style="display: none; background: #fafbfc;">
                    <td colspan="6" style="padding: 1.5rem 2.5rem; border-bottom: 1px solid #f1f5f9;">
                        <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1.25rem;">
                            <h5 style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px;">Kế hoạch giảng dạy: {{ $data['total_sessions'] }} buổi học</h5>
                            <div style="flex: 1; height: 1px; background: #f1f5f9;"></div>
                        </div>

                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 0.75rem;">
                            @foreach($data['sessions'] as $session)
                                <div style="background: white; border: 1px solid #f1f5f9; border-radius: 10px; padding: 0.75rem; display: flex; align-items: center; gap: 0.75rem; transition: transform 0.2s;">
                                    <div style="width: 24px; height: 24px; background: var(--brand-primary-light); border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 800; color: var(--brand-primary);">
                                        {{ $session['index'] }}
                                    </div>
                                    <div style="flex-grow: 1;">
                                        <div style="font-size: 0.8rem; font-weight: 700; color: var(--text-title);">{{ $session['date'] }}</div>
                                        <div style="display: flex; justify-content: space-between; font-size: 0.65rem; font-weight: 600; color: var(--text-muted);">
                                            <span>{{ $session['day'] }}</span>
                                            <span>P. {{ $session['room'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding: 4rem; text-align: center; color: var(--text-muted);">
                        <i data-lucide="calendar-off" style="width: 40px; height: 40px; margin-bottom: 1rem; opacity: 0.2;"></i>
                        <p style="font-weight: 600; font-size: 0.85rem;">Hiện chưa có lịch học chi tiết cho học kỳ này.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function toggleDetail(id, row) {
        const detail = document.getElementById(id);
        const index = id.split('-')[1];
        const icon = document.getElementById('icon-' + index);
        const label = document.getElementById('label-' + index);

        if (detail.style.display === 'none') {
            detail.style.display = 'table-row';
            row.style.background = '#f9fafb';
            icon.style.transform = 'rotate(180deg)';
            label.innerText = 'Thu gọn';
        } else {
            detail.style.display = 'none';
            row.style.background = 'white';
            icon.style.transform = 'rotate(0)';
            label.innerText = 'Xem chi tiết';
        }
    }
</script>
@endsection
