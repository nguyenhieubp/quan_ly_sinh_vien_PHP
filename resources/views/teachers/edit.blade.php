@extends('layouts.app')

@section('title', 'Chỉnh sửa Giảng viên')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 1rem;">
    <!-- Header / Breadcrumbs -->
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <a href="{{ route('teachers.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #64748b; font-weight: 600; font-size: var(--fs-xs); margin-bottom: 0.75rem; transition: color 0.2s;" onmouseover="this.style.color='var(--brand-primary)'" onmouseout="this.style.color='#64748b'">
                <i data-lucide="arrow-left" style="width: 14px;"></i>
                Quay lại danh sách
            </a>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-title); margin: 0; letter-spacing: -0.02em;">Cập nhật hồ sơ Giảng viên</h2>
        </div>
        <div style="text-align: right;">
            <span style="display: block; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">ID Giảng viên</span>
            <span style="font-family: monospace; font-size: 1.25rem; font-weight: 800; color: var(--brand-primary);">#{{ str_pad($teacher->id, 4, '0', STR_PAD_LEFT) }}</span>
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

    <form action="{{ route('teachers.update', $teacher->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem; align-items: start;">
            <!-- Main Content Area -->
            <div style="display: grid; gap: 1.5rem;">
                <!-- Section: Personal Info -->
                <div class="card" style="padding: 2rem; border-radius: 12px; border: 1px solid var(--border-color); background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.25rem;">
                        <div style="padding: 10px; background: #eff6ff; border-radius: 10px; color: var(--brand-primary);">
                            <i data-lucide="user-check" style="width: 20px;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: var(--fs-base); font-weight: 700; color: var(--text-title); margin: 0;">Thông tin cá nhân</h4>
                            <p style="font-size: var(--fs-xs); color: #64748b; margin: 2px 0 0 0;">Quản lý họ tên và thông tin cơ bản</p>
                        </div>
                    </div>

                    <div style="display: grid; gap: 1.75rem;">
                        <div>
                            <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase; letter-spacing: 0.025em;">Họ và Tên giảng viên</label>
                            <div style="position: relative;">
                                <i data-lucide="user" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                <input type="text" name="name" value="{{ old('name', $teacher->name) }}" placeholder="VD: TS. Nguyễn Văn A" 
                                    style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; transition: all 0.2s;" required onfocus="this.style.borderColor='var(--brand-primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.08)'; this.style.background='#ffffff'" onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'; this.style.background='#fbfcfe'">
                            </div>
                        </div>

                        <div>
                            <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Khoa công tác</label>
                            <div style="position: relative;">
                                <i data-lucide="building" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                <select name="department_id" style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; appearance: none; cursor: pointer;" required>
                                    @foreach($departments as $dept)
                                        <option value="{{ $dept->id }}" {{ old('department_id', $teacher->department_id) == $dept->id ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8; pointer-events: none;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Contact Info -->
                <div class="card" style="padding: 2rem; border-radius: 12px; border: 1px solid var(--border-color); background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.25rem;">
                        <div style="padding: 10px; background: #fff1f2; border-radius: 10px; color: #e11d48;">
                            <i data-lucide="contact-2" style="width: 20px;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: var(--fs-base); font-weight: 700; color: var(--text-title); margin: 0;">Thông tin liên hệ</h4>
                            <p style="font-size: var(--fs-xs); color: #64748b; margin: 2px 0 0 0;">Phương thức kết nối và trao đổi công việc</p>
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                        <div>
                            <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Địa chỉ Email</label>
                            <div style="position: relative;">
                                <i data-lucide="mail" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                <input type="email" name="email" value="{{ old('email', $teacher->email) }}" placeholder="giangvien@university.edu.vn" 
                                    style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe;" required onfocus="this.style.borderColor='var(--brand-primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.08)'; this.style.background='#ffffff'" onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'; this.style.background='#fbfcfe'">
                            </div>
                        </div>

                        <div>
                            <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Số điện thoại</label>
                            <div style="position: relative;">
                                <i data-lucide="phone" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}" placeholder="09xx xxx xxx" 
                                    style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe;" required onfocus="this.style.borderColor='var(--brand-primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.08)'; this.style.background='#ffffff'" onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'; this.style.background='#fbfcfe'">
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center; padding: 1rem; font-weight: 700; border-radius: 10px; font-size: var(--fs-sm);">
                        <i data-lucide="refresh-cw" style="width: 18px; margin-right: 8px;"></i>
                        Lưu cập nhật hồ sơ
                    </button>
                    <a href="{{ route('teachers.index') }}" class="btn btn-outline" style="flex: 0.4; justify-content: center; padding: 1rem; font-weight: 600; color: #64748b; background: transparent; border-radius: 10px; font-size: var(--fs-sm);">Hủy bỏ</a>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div style="display: grid; gap: 1.5rem;">
                <!-- Card: Account Status -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem;">
                    <h5 style="font-size: var(--fs-xs); font-weight: 700; color: var(--text-title); margin: 0 0 1.25rem 0; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                        <i data-lucide="info" style="width: 14px; color: var(--brand-primary);"></i>
                        Trạng thái hồ sơ
                    </h5>
                    <div style="display: grid; gap: 1rem;">
                        <div style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9;">
                            <span style="display: block; font-size: 10px; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; font-weight: 700;">Ngày gia nhập</span>
                            <span style="font-size: var(--fs-sm); color: var(--text-title); font-weight: 600;">{{ $teacher->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9;">
                            <span style="display: block; font-size: 10px; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; font-weight: 700;">Lần cuối cập nhật</span>
                            <span style="font-size: var(--fs-sm); color: var(--text-title); font-weight: 600;">{{ $teacher->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card: Safety Box -->
                <div style="background: #f0fdf4; border: 1px solid #dcfce7; border-radius: 12px; padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; color: #166534;">
                        <i data-lucide="shield-check" style="width: 20px;"></i>
                        <h5 style="font-size: var(--fs-sm); font-weight: 700; margin: 0;">Bảo mật dữ liệu</h5>
                    </div>
                    <p style="font-size: 12px; line-height: 1.6; color: #166534; margin: 0;">
                        Thông tin liên hệ của giảng viên được mã hóa và chỉ hiển thị cho ban quản lý có thẩm quyền.
                    </p>
                </div>

                <!-- Card: Help Context -->
                <div style="background: #fefce8; border: 1px solid #fef08a; border-radius: 12px; padding: 1.25rem; display: flex; gap: 0.75rem; align-items: flex-start;">
                    <i data-lucide="help-circle" style="width: 20px; color: #a16207; flex-shrink: 0; margin-top: 2px;"></i>
                    <span style="font-size: 11px; line-height: 1.5; color: #854d0e; font-weight: 500;">Cần hỗ trợ? Vui lòng liên hệ bộ phận kỹ thuật qua hotline 1900 xxxx để được giải đáp.</span>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
