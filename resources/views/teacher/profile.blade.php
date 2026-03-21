@extends('layouts.teacher')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div class="pro-card" style="padding: 1.5rem;">
        <div style="display: flex; align-items: center; gap: 1.25rem; margin-bottom: 1.5rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9;">
            <div style="width: 64px; height: 64px; background: var(--brand-gradient); color: white; border-radius: 16px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; font-weight: 800; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.15);">
                {{ strtoupper(substr($teacher->name, 0, 2)) }}
            </div>
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 800; color: var(--text-title); margin: 0; letter-spacing: -0.5px;">{{ $teacher->name }}</h2>
                <p style="color: var(--text-muted); font-weight: 700; font-size: 0.75rem; margin-top: 2px;">GIẢNG VIÊN EDUCORE</p>
            </div>
            <div style="margin-left: auto;">
                 <span style="font-size: 0.7rem; font-weight: 800; color: var(--brand-primary); background: #ecfdf5; padding: 4px 12px; border-radius: 20px; border: 1px solid #d1fae5;">{{ $teacher->email }}</span>
            </div>
        </div>

        <form action="{{ route('teacher.profile.update') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Họ tên đầy đủ</label>
                    <input type="text" name="name" value="{{ old('name', $teacher->name) }}" required
                           style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.85rem; font-weight: 700; color: var(--text-title); outline: none; background: #f9fafb;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Email liên hệ</label>
                    <input type="email" name="email" value="{{ old('email', $teacher->email) }}" required
                           style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.85rem; font-weight: 700; color: var(--text-title); outline: none; background: #f9fafb;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}"
                           style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.85rem; font-weight: 700; color: var(--text-title); outline: none; background: #f9fafb;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Mã số định danh</label>
                    <input type="text" value="GV-{{ str_pad($teacher->id, 4, '0', STR_PAD_LEFT) }}" readonly
                           style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-light); background: #f1f5f9; font-size: 0.85rem; font-weight: 800; color: #94a3b8; cursor: not-allowed;">
                </div>
            </div>

            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
                <h3 style="font-size: 0.95rem; font-weight: 800; color: var(--text-title); margin-bottom: 1rem; letter-spacing: -0.2px;">Thay đổi mật khẩu</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Mật khẩu mới</label>
                        <input type="password" name="password" placeholder="Nhập mật khẩu mới"
                               style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.85rem; font-weight: 600; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation" placeholder="Xác nhận lại"
                               style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.85rem; font-weight: 600; outline: none;">
                    </div>
                </div>
                <p style="margin-top: 8px; font-size: 0.65rem; color: var(--text-muted); font-style: italic;">* Để trống mật khẩu nếu không muốn thay đổi dữ liệu đăng nhập.</p>
            </div>

            <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-pro btn-green" style="padding: 8px 30px; font-size: 0.85rem; border-radius: 8px;">
                    <i data-lucide="save" style="width: 16px;"></i>
                    Lưu thông tin hồ sơ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
