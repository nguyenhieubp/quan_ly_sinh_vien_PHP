@extends('layouts.app')

@section('title', 'Cấu hình Lớp học - ' . $classroom->name)

@section('content')
<div style="max-width: 700px; margin: 0 auto; padding-top: 2rem;">
    <!-- Refined Header -->
    <div style="margin-bottom: 2rem; display: flex; align-items: flex-end; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <a href="{{ route('classrooms.index') }}" style="background: white; border: 1px solid var(--border-color); width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--text-muted); text-decoration: none; transition: all 0.2s;">
                <i data-lucide="arrow-left" style="width: 18px;"></i>
            </a>
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0;">Chỉnh sửa Lớp học</h3>
                <p style="margin: 2px 0 0 0; font-size: 13px; color: var(--text-muted);">Cập nhật thông tin định danh và quản lý của lớp.</p>
            </div>
        </div>
        <div style="text-align: right;">
            <span style="display: block; font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 2px;">Mã định danh</span>
            <span style="font-family: monospace; font-size: 14px; font-weight: 700; color: var(--brand-accent); background: #f5f3ff; padding: 4px 10px; border-radius: 8px; border: 1px solid #ddd6fe;">{{ $classroom->code }}</span>
        </div>
    </div>

    <div class="card-glass" style="border-radius: 20px; overflow: hidden; animation: slideUp 0.4s ease-out;">
        <form action="{{ route('classrooms.update', $classroom->id) }}" method="POST" style="padding: 2.5rem;">
            @csrf
            @method('PUT')
            
            <div style="display: grid; gap: 1.75rem;">
                <!-- Group: Basic Info -->
                <div style="display: grid; gap: 1.25rem;">
                    <div>
                        <label style="display: block; font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.025em;">Tên lớp học</label>
                        <input type="text" name="name" value="{{ old('name', $classroom->name) }}" class="refined-input" placeholder="Nhập tên lớp..." required autofocus>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div>
                            <label style="display: block; font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.025em;">Mã lớp</label>
                            <input type="text" name="code" value="{{ old('code', $classroom->code) }}" class="refined-input" style="font-family: monospace; font-weight: 600;" required>
                        </div>
                        <div>
                            <label style="display: block; font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px; letter-spacing: 0.025em;">Khoa quản lý</label>
                            <div style="position: relative;">
                                <select name="department_id" class="refined-select" required>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id', $classroom->department_id) == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 14px; color: var(--text-muted); pointer-events: none;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Subtle Info Box -->
                <div style="background: var(--bg-main); border-radius: 14px; padding: 1.25rem; border: 1px solid var(--border-color); display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 0.75rem;">
                        <div style="width: 40px; height: 40px; background: white; border-radius: 10px; display: flex; align-items: center; justify-content: center; border: 1px solid var(--border-color);">
                            <i data-lucide="users" style="width: 18px; color: var(--brand-accent);"></i>
                        </div>
                        <div>
                            <h5 style="margin: 0; font-size: 13px; font-weight: 700; color: var(--text-main);">Sĩ số hiện tại</h5>
                            <p style="margin: 2px 0 0 0; font-size: 11px; color: var(--text-muted);">{{ $classroom->students()->count() }} sinh viên đã ghi danh</p>
                        </div>
                    </div>
                    <a href="{{ route('students.index', ['classroom_id' => $classroom->id]) }}" style="font-size: 11px; font-weight: 600; color: var(--brand-accent); text-decoration: none; display: flex; align-items: center; gap: 4px; padding: 6px 12px; background: white; border: 1px solid var(--border-color); border-radius: 8px; transition: all 0.2s;">
                        Xem danh sách <i data-lucide="external-link" style="width: 12px;"></i>
                    </a>
                </div>

                <!-- Warning/Note -->
                <div style="padding: 1rem; background: #fffbeb; border: 1px solid #fde68a; border-radius: 14px; display: flex; gap: 10px;">
                    <i data-lucide="info" style="width: 16px; color: #d97706; flex-shrink: 0; margin-top: 2px;"></i>
                    <p style="margin: 0; font-size: 11px; line-height: 1.5; color: #92400e; font-weight: 500;">
                        Lưu ý: Việc thay đổi tên hoặc mã lớp có thể ảnh hưởng đến các báo cáo và chứng chỉ liên quan. Hãy đảm bảo thông tin chính xác trước khi lưu.
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div style="margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('classrooms.index') }}" style="background: white; border: 1px solid var(--border-color); padding: 0.75rem 2rem; border-radius: 12px; font-weight: 600; font-size: 14px; color: var(--text-muted); text-decoration: none; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">Hủy bỏ</a>
                <button type="submit" class="save-btn" style="padding: 0.75rem 2.5rem;">
                    <i data-lucide="check" style="width: 16px;"></i>
                    Lưu thông tin
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    :root {
        --brand-primary: #334155;
        --brand-accent: #6366f1;
        --bg-main: #f8fafc;
        --text-main: #334155;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    .refined-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: white;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-main);
        transition: all 0.2s ease;
    }
    .refined-input:focus {
        border-color: var(--brand-accent);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.08);
        outline: none;
    }
    .refined-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: white;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-main);
        appearance: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .refined-select:focus {
        border-color: var(--brand-accent);
        outline: none;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.08);
    }
    .save-btn {
        background: var(--brand-primary);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .save-btn:hover {
        background: #1e293b;
        transform: translateY(-1px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    .card-glass {
        background: white;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    @keyframes slideUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    lucide.createIcons();
</script>
@endsection
