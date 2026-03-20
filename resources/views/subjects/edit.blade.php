@extends('layouts.app')

@section('title', 'Chỉnh sửa Môn học')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 1rem;">
    <!-- Header / Breadcrumbs -->
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <a href="{{ route('subjects.index', ['department_id' => $subject->department_id]) }}" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #64748b; font-weight: 600; font-size: var(--fs-xs); margin-bottom: 0.75rem; transition: color 0.2s;" onmouseover="this.style.color='var(--brand-primary)'" onmouseout="this.style.color='#64748b'">
                <i data-lucide="arrow-left" style="width: 14px;"></i>
                Quay lại danh sách môn học
            </a>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-title); margin: 0; letter-spacing: -0.02em;">Cập nhật thông tin Môn học</h2>
        </div>
        <div style="text-align: right;">
            <span style="display: block; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Mã Môn học</span>
            <span style="font-family: monospace; font-size: 1.25rem; font-weight: 800; color: var(--brand-primary);">{{ $subject->code }}</span>
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

    <form action="{{ route('subjects.update', $subject->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem; align-items: start;">
            <!-- Main Content Area -->
            <div style="display: grid; gap: 1.5rem;">
                <!-- Section: Subject Details -->
                <div class="card" style="padding: 2rem; border-radius: 12px; border: 1px solid var(--border-color); background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.25rem;">
                        <div style="padding: 10px; background: #fdf4ff; border-radius: 10px; color: #a855f7;">
                            <i data-lucide="book-open" style="width: 20px;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: var(--fs-base); font-weight: 700; color: var(--text-title); margin: 0;">Thông tin môn học</h4>
                            <p style="font-size: var(--fs-xs); color: #64748b; margin: 2px 0 0 0;">Cập nhật định danh và khối lượng học tập</p>
                        </div>
                    </div>

                    <div style="display: grid; gap: 1.75rem;">
                        <div>
                            <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Khoa phụ trách</label>
                            <div style="position: relative;">
                                <i data-lucide="landmark" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                <select name="department_id" style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; appearance: none;" required>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id', $subject->department_id) == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase; letter-spacing: 0.025em;">Tên Môn học</label>
                            <div style="position: relative;">
                                <i data-lucide="type" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                <input type="text" name="name" value="{{ old('name', $subject->name) }}" placeholder="VD: Lập trình PHP nâng cao" 
                                    style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; transition: all 0.2s;" required onfocus="this.style.borderColor='var(--brand-primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.08)'; this.style.background='#ffffff'" onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'; this.style.background='#fbfcfe'">
                            </div>
                        </div>

                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <div>
                                <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Mã Môn học</label>
                                <div style="position: relative;">
                                    <i data-lucide="hash" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                    <input type="text" name="code" value="{{ old('code', $subject->code) }}" placeholder="VD: PHP02" 
                                        style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; font-family: monospace; font-weight: 700;" required onfocus="this.style.borderColor='var(--brand-primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.08)'; this.style.background='#ffffff'" onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'; this.style.background='#fbfcfe'">
                                </div>
                            </div>

                            <div>
                                <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Số tín chỉ</label>
                                <div style="position: relative;">
                                    <i data-lucide="layers" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                    <input type="number" name="credits" value="{{ old('credits', $subject->credits) }}" placeholder="VD: 3" 
                                        style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe;" required onfocus="this.style.borderColor='var(--brand-primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.08)'; this.style.background='#ffffff'" onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'; this.style.background='#fbfcfe'">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center; padding: 1rem; font-weight: 700; border-radius: 10px; font-size: var(--fs-sm);">
                        <i data-lucide="refresh-cw" style="width: 18px; margin-right: 8px;"></i>
                        Lưu cập nhật học phần
                    </button>
                    <a href="{{ route('subjects.index') }}" class="btn btn-outline" style="flex: 0.4; justify-content: center; padding: 1rem; font-weight: 600; color: #64748b; background: transparent; border-radius: 10px; font-size: var(--fs-sm);">Hủy bỏ</a>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div style="display: grid; gap: 1.5rem;">
                <!-- Card: Metadata -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem;">
                    <h5 style="font-size: var(--fs-xs); font-weight: 700; color: var(--text-title); margin: 0 0 1.25rem 0; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                        <i data-lucide="info" style="width: 14px; color: var(--brand-primary);"></i>
                        Trạng thái dữ liệu
                    </h5>
                    <div style="display: grid; gap: 1rem;">
                        <div style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9;">
                            <span style="display: block; font-size: 10px; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; font-weight: 700;">Ngày khởi tạo</span>
                            <span style="font-size: var(--fs-sm); color: var(--text-title); font-weight: 600;">{{ $subject->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9;">
                            <span style="display: block; font-size: 10px; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; font-weight: 700;">Cập nhật cuối</span>
                            <span style="font-size: var(--fs-sm); color: var(--text-title); font-weight: 600;">{{ $subject->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card: Academic Info -->
                <div style="background: #faf5ff; border: 1px solid #f3e8ff; border-radius: 12px; padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; color: #a855f7;">
                        <i data-lucide="graduation-cap" style="width: 18px;"></i>
                        <h5 style="font-size: var(--fs-sm); font-weight: 700; margin: 0;">Thống kê học thuật</h5>
                    </div>
                    @php
                        $registrationCount = \App\Models\Grade::where('subject_id', $subject->id)->count();
                    @endphp
                    <div style="font-size: 1.5rem; font-weight: 800; color: #a855f7; margin-bottom: 0.5rem;">
                        {{ $registrationCount }} <span style="font-size: 0.9rem; font-weight: 600; color: #c084fc;">bản ghi điểm</span>
                    </div>
                    <p style="font-size: 11px; line-height: 1.5; color: #c084fc; margin: 0;">
                        Đã có {{ $registrationCount }} dữ liệu điểm số liên quan đến môn học này trong hệ thống.
                    </p>
                </div>

                <!-- Card: Warning Box -->
                <div style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 12px; padding: 1.25rem; display: flex; gap: 0.75rem; align-items: flex-start;">
                    <i data-lucide="alert-triangle" style="width: 18px; color: #e11d48; flex-shrink: 0; margin-top: 2px;"></i>
                    <span style="font-size: 11px; line-height: 1.5; color: #be123c; font-weight: 500;">Việc thay đổi <strong>Số tín chỉ</strong> sẽ ảnh hưởng trực tiếp đến việc tính toán điểm trung bình tích lũy (GPA) của sinh viên.</span>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
