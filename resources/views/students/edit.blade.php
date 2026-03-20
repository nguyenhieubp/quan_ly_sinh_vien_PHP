@extends('layouts.app')

@section('title', 'Chỉnh sửa Sinh viên')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 1rem;">
    <!-- Header / Breadcrumbs -->
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <a href="{{ route('students.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #64748b; font-weight: 600; font-size: var(--fs-xs); margin-bottom: 0.75rem; transition: color 0.2s;" onmouseover="this.style.color='var(--brand-primary)'" onmouseout="this.style.color='#64748b'">
                <i data-lucide="arrow-left" style="width: 14px;"></i>
                Quay lại danh sách
            </a>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-title); margin: 0; letter-spacing: -0.02em;">Cập nhật hồ sơ Sinh viên</h2>
        </div>
        <div style="text-align: right;">
            <span style="display: block; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Mã sinh viên</span>
            <span style="font-family: monospace; font-size: 1.25rem; font-weight: 800; color: var(--brand-primary);">{{ $student->student_code }}</span>
        </div>
    </div>

    @if ($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 2rem; display: flex; gap: 0.75rem; align-items: flex-start;">
            <i data-lucide="alert-circle" style="width: 18px; color: #ef4444; flex-shrink: 0; margin-top: 2px;"></i>
            <div style="font-size: var(--fs-sm);">
                <div style="font-weight: 700; margin-bottom: 0.25rem;">Vui lòng kiểm tra lại:</div>
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('students.update', $student->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem; align-items: start;">
            <!-- Main Content Area -->
            <div style="display: grid; gap: 1.5rem;">
                <!-- Section: Basic Info -->
                <div class="card" style="padding: 1.75rem; border-radius: 12px; border: 1px solid var(--border-color); background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.75rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.25rem;">
                        <div style="padding: 10px; background: #f0fdf4; border-radius: 10px; color: #16a34a;">
                            <i data-lucide="user-cog" style="width: 20px;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: var(--fs-base); font-weight: 700; color: var(--text-title); margin: 0;">Thông tin cá nhân</h4>
                            <p style="font-size: var(--fs-xs); color: #64748b; margin: 2px 0 0 0;">Quản lý họ tên và thông tin liên lạc</p>
                        </div>
                    </div>

                    <div style="display: grid; gap: 1.5rem;">
                        <div>
                            <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase; letter-spacing: 0.025em;">Họ và Tên sinh viên</label>
                            <div style="position: relative;">
                                <i data-lucide="user" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                <input type="text" name="name" value="{{ old('name', $student->name) }}" placeholder="VD: Nguyễn Văn A" 
                                    style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; transition: all 0.2s;" required onfocus="this.style.borderColor='var(--brand-primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.08)'; this.style.background='#ffffff'" onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'; this.style.background='#fbfcfe'">
                            </div>
                        </div>

                        <div>
                            <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Địa chỉ Email</label>
                            <div style="position: relative;">
                                <i data-lucide="mail" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                <input type="email" name="email" value="{{ old('email', $student->email) }}" placeholder="sinhvien@university.edu.vn" 
                                    style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; transition: all 0.2s;" required onfocus="this.style.borderColor='var(--brand-primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.08)'; this.style.background='#ffffff'" onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'; this.style.background='#fbfcfe'">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Academic Info -->
                <div class="card" style="padding: 1.75rem; border-radius: 12px; border: 1px solid var(--border-color); background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1.75rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.25rem;">
                        <div style="padding: 10px; background: #eff6ff; border-radius: 10px; color: var(--brand-primary);">
                            <i data-lucide="graduation-cap" style="width: 20px;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: var(--fs-base); font-weight: 700; color: var(--text-title); margin: 0;">Định danh & Lớp học</h4>
                            <p style="font-size: var(--fs-xs); color: #64748b; margin: 2px 0 0 0;">Thông tin quản lý học thuật</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div style="grid-column: span 2;">
                            <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Mã Số Sinh Viên</label>
                            <div style="position: relative;">
                                <i data-lucide="id-card" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                <input type="text" name="student_code" value="{{ old('student_code', $student->student_code) }}" placeholder="VD: SV202401" 
                                    style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; font-family: monospace; font-weight: 600;" required onfocus="this.style.borderColor='var(--brand-primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.08)'; this.style.background='#ffffff'" onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'; this.style.background='#fbfcfe'">
                            </div>
                        </div>

                        <div>
                            <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Khoa</label>
                            <div style="position: relative;">
                                <i data-lucide="building-2" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                @php $currentDeptId = $student->classroom ? $student->classroom->department_id : null; @endphp
                                <select id="department_select" style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; appearance: none; cursor: pointer;">
                                    <option value="">-- Chọn khoa --</option>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ $currentDeptId == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8; pointer-events: none;"></i>
                            </div>
                        </div>

                        <div>
                            <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Lớp học trực thuộc</label>
                            <div style="position: relative;">
                                <i data-lucide="layout" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                <select name="classroom_id" id="classroom_select" style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; appearance: none; cursor: pointer;" required>
                                    <option value="">-- Chọn lớp học --</option>
                                    @foreach($classrooms as $cls)
                                        <option value="{{ $cls->id }}" data-dept="{{ $cls->department_id }}" {{ old('classroom_id', $student->classroom_id) == $cls->id ? 'selected' : '' }}>
                                            {{ $cls->name }} ({{ $cls->code }})
                                        </option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8; pointer-events: none;"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div style="display: grid; gap: 1.5rem;">
                <!-- Card: Profile Status -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem;">
                    <h5 style="font-size: var(--fs-xs); font-weight: 700; color: var(--text-title); margin: 0 0 1.25rem 0; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                        <i data-lucide="info" style="width: 14px; color: var(--brand-primary);"></i>
                        Trạng thái hồ sơ
                    </h5>
                    <div style="display: grid; gap: 1rem;">
                        <div style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9;">
                            <span style="display: block; font-size: 10px; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; font-weight: 700;">Ngày nhập học</span>
                            <span style="font-size: var(--fs-sm); color: var(--text-title); font-weight: 600;">{{ $student->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9;">
                            <span style="display: block; font-size: 10px; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; font-weight: 700;">Cập nhật lần cuối</span>
                            <span style="font-size: var(--fs-sm); color: var(--text-title); font-weight: 600;">{{ $student->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card: Quick Help -->
                <div style="background: var(--brand-primary); border-radius: 12px; padding: 1.5rem; color: #ffffff;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
                        <i data-lucide="shield-check" style="width: 20px; opacity: 0.9;"></i>
                        <h5 style="font-size: var(--fs-sm); font-weight: 700; margin: 0;">Dữ liệu an toàn</h5>
                    </div>
                    <p style="font-size: 12px; line-height: 1.6; color: rgba(255,255,255,0.85); margin: 0;">
                        Mọi thay đổi trên thông tin sinh viên sẽ được ghi nhận vào nhật ký hệ thống để đảm bảo tính minh bạch trong quản lý đào tạo.
                    </p>
                </div>

                <!-- Footer Actions -->
                <div style="display: grid; gap: 0.75rem;">
                    <button type="submit" class="btn btn-primary" style="width: 100%; justify-content: center; padding: 0.85rem; font-weight: 700; border-radius: 10px;">
                        <i data-lucide="refresh-cw" style="width: 18px; margin-right: 8px;"></i>
                        Lưu cập nhật
                    </button>
                    <a href="{{ route('students.index') }}" class="btn btn-outline" style="width: 100%; justify-content: center; padding: 0.85rem; font-weight: 600; color: #64748b; background: #ffffff; border-radius: 10px;">Hủy bỏ</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();

    const deptSelect = document.getElementById('department_select');
    const classSelect = document.getElementById('classroom_select');
    const allOptions = Array.from(classSelect.options).slice(1); // Exclude first placeholder

    deptSelect.addEventListener('change', function() {
        const deptId = this.value;
        const currentVal = classSelect.value;
        
        // Filter options
        classSelect.innerHTML = '<option value="">-- Chọn lớp học --</option>';
        let found = false;
        allOptions.forEach(opt => {
            if (!deptId || opt.dataset.dept === deptId) {
                const newOpt = opt.cloneNode(true);
                classSelect.appendChild(newOpt);
                if (opt.value === currentVal) {
                    newOpt.selected = true;
                    found = true;
                }
            }
        });

        if (!found && deptId) {
            classSelect.value = "";
        }
    });

    // Handle initial state
    if (deptSelect.value) {
        // Just refilter without changing selection if possible
        const deptId = deptSelect.value;
        const currentVal = classSelect.value;
        classSelect.innerHTML = '<option value="">-- Chọn lớp học --</option>';
        allOptions.forEach(opt => {
            if (!deptId || opt.dataset.dept === deptId) {
                const newOpt = opt.cloneNode(true);
                classSelect.appendChild(newOpt);
                if (opt.value === currentVal) {
                    newOpt.selected = true;
                }
            }
        });
    }
</script>
@endsection
