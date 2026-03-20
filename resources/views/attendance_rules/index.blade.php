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
        <form action="{{ route('attendance-rules.store') }}" method="POST" id="rule-form" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
            @csrf
            <div style="flex: 1; min-width: 120px;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Số tín chỉ</label>
                <input type="number" name="credits" id="input-credits" required min="1" placeholder="Vd: 3" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px;">
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Vắng tối đa (buổi)</label>
                <input type="number" name="max_absent" id="input-absent" required min="0" placeholder="Vd: 3" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px;">
            </div>
            <div style="flex: 1; min-width: 150px;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Muộn tối đa (buổi)</label>
                <input type="number" name="max_late" id="input-late" required min="0" placeholder="Vd: 1" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px;">
            </div>
            <div style="flex: 1; min-width: 180px;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Điểm trừ / Vắng</label>
                <input type="number" step="0.1" name="absent_deduction" id="input-absent-deduction" required value="1.0" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px;">
            </div>
            <div style="flex: 1; min-width: 180px;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Điểm trừ / Muộn</label>
                <input type="number" step="0.1" name="late_deduction" id="input-late-deduction" required value="0.5" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px;">
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
                    <th style="padding: 1rem 1.5rem; text-align: right;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @forelse($rules as $rule)
                <tr id="rule-row-{{ $rule->id }}">
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
                        <div style="display: flex; justify-content: flex-end; gap: 4px;">
                            <button onclick="editRule({{ json_encode($rule) }})" class="btn" style="padding: 6px; background: #f1f5f9; border: 1px solid #e2e8f0; border-radius: 6px; cursor: pointer; color: #475569;" title="Chỉnh sửa">
                                <i data-lucide="edit-2" style="width: 14px;"></i>
                            </button>
                            <form action="{{ route('attendance-rules.destroy', $rule->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa quy tắc này?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn" style="padding: 6px; background: #fef2f2; border: 1px solid #fee2e2; border-radius: 6px; cursor: pointer; color: #ef4444;" title="Xóa">
                                    <i data-lucide="trash-2" style="width: 14px;"></i>
                                </button>
                            </form>
                        </div>
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

<script>
    function editRule(rule) {
        document.getElementById('input-credits').value = rule.credits;
        document.getElementById('input-absent').value = rule.max_absent;
        document.getElementById('input-late').value = rule.max_late;
        document.getElementById('input-absent-deduction').value = rule.absent_deduction;
        document.getElementById('input-late-deduction').value = rule.late_deduction;
        
        // Visual feedback
        const form = document.getElementById('rule-form');
        form.style.background = '#f0f9ff';
        form.style.borderColor = '#bae6fd';
        setTimeout(() => {
            form.style.background = '#fbfcfe';
            form.style.borderColor = '#e2e8f0';
        }, 1000);

        // Scroll to form if needed
        form.scrollIntoView({ behavior: 'smooth', block: 'center' });
    }
</script>
@endsection
