@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('students.index') }}" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #64748b; font-weight: 500; font-size: 0.9rem;">
            <i data-lucide="arrow-left" style="width: 16px;"></i>
            Quay lại danh sách
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 style="font-size: 1.5rem;">Thêm Sinh viên mới</h2>
        </div>

        @if ($errors->any())
            <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
                <ul style="list-style: none; font-size: 0.9rem;">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('students.store') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-title);">Họ và Tên</label>
                    <input type="text" name="name" value="{{ old('name') }}" placeholder="VD: Nguyễn Văn A" 
                           style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color); outline: none;" required>
                </div>
                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-title);">Mã Sinh viên</label>
                    <input type="text" name="student_code" value="{{ old('student_code') }}" placeholder="VD: SV001" 
                           style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color); outline: none;" required>
                </div>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-title);">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="example@email.com" 
                       style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color); outline: none;" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-title);">Khoa</label>
                    <select id="department_select" style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color); outline: none; background: white;">
                        <option value="">-- Chọn khoa --</option>
                        @foreach($departments as $dept)
                            <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-title);">Lớp học</label>
                    <select name="classroom_id" id="classroom_select" style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color); outline: none; background: white;" required>
                        <option value="">-- Chọn lớp học --</option>
                        @foreach($classrooms as $cls)
                            <option value="{{ $cls->id }}" data-dept="{{ $cls->department_id }}" {{ old('classroom_id') == $cls->id ? 'selected' : '' }}>
                                {{ $cls->name }} ({{ $cls->code }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center;">Lưu thông tin</button>
                <button type="reset" class="btn btn-outline" style="flex: 1; justify-content: center;">Nhập lại</button>
            </div>
        </form>
    </div>
</div>

<script>
    lucide.createIcons();

    const deptSelect = document.getElementById('department_select');
    const classSelect = document.getElementById('classroom_select');
    const allOptions = Array.from(classSelect.options).slice(1); // Exclude first placeholder

    deptSelect.addEventListener('change', function() {
        const deptId = this.value;
        
        // Reset class selection
        classSelect.value = "";
        
        // Filter options
        classSelect.innerHTML = '<option value="">-- Chọn lớp học --</option>';
        allOptions.forEach(opt => {
            if (!deptId || opt.dataset.dept === deptId) {
                classSelect.appendChild(opt.cloneNode(true));
            }
        });
    });

    // Handle initial state (e.g. on validation error)
    if (deptSelect.value) {
        deptSelect.dispatchEvent(new Event('change'));
    }
</script>
@endsection
