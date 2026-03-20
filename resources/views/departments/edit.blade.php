@extends('layouts.app')

@section('title', 'Chỉnh sửa Khoa')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 1rem;">
    <!-- Header / Breadcrumbs -->
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <a href="{{ route('departments.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #64748b; font-weight: 600; font-size: var(--fs-xs); margin-bottom: 0.75rem; transition: color 0.2s;" onmouseover="this.style.color='var(--brand-primary)'" onmouseout="this.style.color='#64748b'">
                <i data-lucide="arrow-left" style="width: 14px;"></i>
                Quay lại danh sách
            </a>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-title); margin: 0; letter-spacing: -0.02em;">Cập nhật thông tin Khoa</h2>
        </div>
        <div style="text-align: right;">
            <span style="display: block; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Mã Khoa</span>
            <span style="font-family: monospace; font-size: 1.25rem; font-weight: 800; color: var(--brand-primary);">{{ $department->code }}</span>
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

    <form action="{{ route('departments.update', $department->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem; align-items: start;">
            <!-- Main Content Area -->
            <div style="display: grid; gap: 1.5rem;">
                <!-- Section: Department Info -->
                <div class="card" style="padding: 2rem; border-radius: 12px; border: 1px solid var(--border-color); background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.25rem;">
                        <div style="padding: 10px; background: #fff7ed; border-radius: 10px; color: #f97316;">
                            <i data-lucide="building-2" style="width: 20px;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: var(--fs-base); font-weight: 700; color: var(--text-title); margin: 0;">Thông tin cơ bản</h4>
                            <p style="font-size: var(--fs-xs); color: #64748b; margin: 2px 0 0 0;">Cập nhật định danh và tên gọi của Khoa</p>
                        </div>
                    </div>

                    <div style="display: grid; gap: 1.75rem;">
                        <div>
                            <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase; letter-spacing: 0.025em;">Tên Khoa đầy đủ</label>
                            <div style="position: relative;">
                                <i data-lucide="type" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                <input type="text" name="name" value="{{ old('name', $department->name) }}" placeholder="VD: Khoa Công nghệ thông tin" 
                                    style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; transition: all 0.2s;" required onfocus="this.style.borderColor='var(--brand-primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.08)'; this.style.background='#ffffff'" onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'; this.style.background='#fbfcfe'">
                            </div>
                        </div>

                        <div>
                            <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Mã Khoa (Viết tắt)</label>
                            <div style="position: relative;">
                                <i data-lucide="hash" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                <input type="text" name="code" value="{{ old('code', $department->code) }}" placeholder="VD: CNTT" 
                                    style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; font-family: monospace; font-weight: 700;" required onfocus="this.style.borderColor='var(--brand-primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.08)'; this.style.background='#ffffff'" onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'; this.style.background='#fbfcfe'">
                            </div>
                            <p style="font-size: 11px; color: #94a3b8; margin-top: 6px;">Mã khoa nên được đặt ngắn gọn, dùng để phân biệt các học phần và lớp học.</p>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center; padding: 1rem; font-weight: 700; border-radius: 10px; font-size: var(--fs-sm);">
                        <i data-lucide="save" style="width: 18px; margin-right: 8px;"></i>
                        Lưu thay đổi
                    </button>
                    <a href="{{ route('departments.index') }}" class="btn btn-outline" style="flex: 0.4; justify-content: center; padding: 1rem; font-weight: 600; color: #64748b; background: transparent; border-radius: 10px; font-size: var(--fs-sm);">Hủy bỏ</a>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div style="display: grid; gap: 1.5rem;">
                <!-- Card: Status Box -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem;">
                    <h5 style="font-size: var(--fs-xs); font-weight: 700; color: var(--text-title); margin: 0 0 1.25rem 0; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                        <i data-lucide="info" style="width: 14px; color: var(--brand-primary);"></i>
                        Trạng thái dữ liệu
                    </h5>
                    <div style="display: grid; gap: 1rem;">
                        <div style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9;">
                            <span style="display: block; font-size: 10px; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; font-weight: 700;">Ngày khởi tạo</span>
                            <span style="font-size: var(--fs-sm); color: var(--text-title); font-weight: 600;">{{ $department->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9;">
                            <span style="display: block; font-size: 10px; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; font-weight: 700;">Cập nhật cuối</span>
                            <span style="font-size: var(--fs-sm); color: var(--text-title); font-weight: 600;">{{ $department->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card: Quick Guide -->
                <div style="background: #fdfaf6; border: 1px solid #fae8d0; border-radius: 12px; padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; color: #c2410c;">
                        <i data-lucide="lightbulb" style="width: 18px;"></i>
                        <h5 style="font-size: var(--fs-sm); font-weight: 700; margin: 0;">Lưu ý</h5>
                    </div>
                    <p style="font-size: 12px; line-height: 1.6; color: #7c2d12; margin: 0;">
                        Việc đổi <strong>Mã Khoa</strong> có thể ảnh hưởng đến cách hiển thị mã lớp và mã học phần liên quan. Hãy cân nhắc trước khi thay đổi.
                    </p>
                </div>

                <!-- Card: Security Check -->
                <div style="background: #f0fdf4; border: 1px solid #dcfce7; border-radius: 12px; padding: 1.25rem; display: flex; gap: 0.75rem; align-items: center;">
                    <i data-lucide="shield-check" style="width: 20px; color: #16a34a;"></i>
                    <span style="font-size: 11px; font-weight: 600; color: #166534;">Dữ liệu đã được mã hóa và bảo vệ bởi hệ thống.</span>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
