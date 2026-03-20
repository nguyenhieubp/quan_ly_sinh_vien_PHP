@extends('layouts.app')

@section('title', 'Cấu hình Chuyên cần')

@section('content')
<div class="card" style="padding: 0; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
    <!-- Header -->
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); background: #ffffff;">
        <h3 style="font-size: 1.15rem; color: var(--text-title); margin: 0; font-weight: 700;">Cấu hình Chuyên cần theo Số tín chỉ</h3>
        <p style="color: var(--text-body); font-size: var(--fs-xs); margin-top: 4px;">Thiết lập quy tắc tính điểm và ngưỡng vắng tối đa dựa trên số tín chỉ của môn học.</p>
    </div>

    <!-- Quick Form -->
    <div style="padding: 1.5rem; background: #fbfcfe; border-bottom: 1px solid var(--border-color);">
        <form action="{{ route('attendance-rules.store') }}" method="POST" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
            @csrf
            <div style="flex: 1; min-width: 120px;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Số tín chỉ</label>
                <input type="number" name="credits" required min="1" placeholder="Vd: 3" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px;">
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Vắng tối đa (buổi)</label>
                <input type="number" name="max_absent" required min="0" placeholder="Vd: 3" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px;">
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Muộn tối đa (buổi)</label>
                <input type="number" name="max_late" required min="0" placeholder="Vd: 1" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px;">
            </div>
            <div style="flex: 1; min-width: 180px;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Điểm trừ / Vắng</label>
                <input type="number" step="0.1" name="absent_deduction" required value="1.0" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px;">
            </div>
            <div style="flex: 1; min-width: 180px;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Điểm trừ / Muộn</label>
                <input type="number" step="0.1" name="late_deduction" required value="0.5" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px;">
            </div>
            <button type="submit" class="btn btn-primary" style="height: 38px; padding: 0 20px; font-size: 12px;">Cập nhật / Thêm mới</button>
        </form>
    </div>

    <!-- Data Table -->
    <div class="table-responsive">
        <table class="data-table" style="width: 100%;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 1rem 1.5rem;">Số tín chỉ</th>
                    <th style="padding: 1rem 1.5rem;">Cấu hình ngưỡng</th>
                    <th style="padding: 1rem 1.5rem;">Quy tắc trừ điểm</th>
                    <th style="padding: 1rem 1.5rem; text-align: right;">Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rules as $rule)
                <tr>
                    <td style="padding: 1rem 1.5rem;">
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div style="width: 32px; height: 32px; background: #eff6ff; border-radius: 8px; display: flex; align-items: center; justify-content: center; color: var(--brand-primary); font-weight: 800;">
                                {{ $rule->credits }}
                            </div>
                            <span style="font-weight: 700; color: var(--text-title);">{{ $rule->credits }} Tín chỉ</span>
                        </div>
                    </td>
                    <td style="padding: 1rem 1.5rem;">
                        <div style="display: flex; gap: 12px;">
                            <span style="font-size: 12px; color: #475569;">
                                <i data-lucide="user-x" style="width: 14px; vertical-align: middle; color: #ef4444;"></i>
                                Vắng tối đa: <strong>{{ $rule->max_absent }}</strong> buổi
                            </span>
                            <span style="font-size: 12px; color: #475569;">
                                <i data-lucide="clock" style="width: 14px; vertical-align: middle; color: #eab308;"></i>
                                Muộn tối đa: <strong>{{ $rule->max_late }}</strong> buổi
                            </span>
                        </div>
                    </td>
                    <td style="padding: 1rem 1.5rem;">
                        <div style="display: flex; flex-direction: column; gap: 2px;">
                            <span style="font-size: 12px; color: #64748b;">Vắng 1 buổi: <strong>-{{ $rule->absent_deduction }}</strong> điểm</span>
                            <span style="font-size: 12px; color: #64748b;">Muộn 1 buổi: <strong>-{{ $rule->late_deduction }}</strong> điểm</span>
                        </div>
                    </td>
                    <td style="padding: 1rem 1.5rem; text-align: right;">
                        <span class="badge badge-success">Đang áp dụng</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="padding: 3rem; text-align: center; color: #94a3b8;">Chưa có cấu hình nào. Hãy thêm mới cấu hình ở trên.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div style="margin-top: 1.5rem; padding: 1rem; border-radius: 8px; background: #fffbeb; border: 1px solid #fef3c7; display: flex; gap: 0.75rem;">
    <i data-lucide="info" style="width: 20px; color: #d97706; flex-shrink: 0;"></i>
    <div style="font-size: 12px; color: #92400e; line-height: 1.5;">
        <strong>Ghi chú:</strong> Hệ thống sẽ tự động đối chiếu số tín chỉ của môn học để áp dụng quy tắc trừ điểm tương ứng trong trang điểm danh. Nếu một môn học không có cấu hình cụ thể, hệ thống sẽ sử dụng giá trị mặc định (Vắng: -1.0, Muộn: -0.5, Ngưỡng 3 buổi).
    </div>
</div>
@endsection
