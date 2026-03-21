@extends('layouts.teacher')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <div class="pro-card" style="padding: 2rem;">
        <div style="display: flex; align-items: center; gap: 1.5rem; margin-bottom: 2rem; padding-bottom: 1.5rem; border-bottom: 1px solid #f1f5f9;">
            <div style="width: 80px; height: 80px; background: var(--brand-gradient); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 2rem; font-weight: 800; box-shadow: 0 10px 15px -3px rgba(5, 150, 105, 0.2);">
                {{ strtoupper(substr($teacher->name, 0, 2)) }}
            </div>
            <div>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-title); margin: 0;">{{ $teacher->name }}</h2>
                <p style="color: var(--text-muted); font-weight: 600; font-size: 0.9rem; margin-top: 4px;">Giảng viên hệ thống</p>
            </div>
        </div>

        <form action="{{ route('teacher.profile.update') }}" method="POST">
            @csrf
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Họ và tên</label>
                    <input type="text" name="name" value="{{ old('name', $teacher->name) }}" required
                           style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.95rem; font-weight: 600; outline: none; focus: border-color: var(--brand-primary);">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Địa chỉ Email</label>
                    <input type="email" name="email" value="{{ old('email', $teacher->email) }}" required
                           style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.95rem; font-weight: 600; outline: none;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $teacher->phone) }}"
                           style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.95rem; font-weight: 600; outline: none;">
                </div>
                <div>
                    <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Mã giảng viên</label>
                    <input type="text" value="GV-{{ str_pad($teacher->id, 4, '0', STR_PAD_LEFT) }}" readonly
                           style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid #f1f5f9; background: #f8fafc; font-size: 0.95rem; font-weight: 700; color: var(--text-muted);">
                </div>
            </div>

            <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #f1f5f9;">
                <h3 style="font-size: 1rem; font-weight: 800; color: var(--text-title); margin-bottom: 1rem;">Đổi mật khẩu (Để trống nếu không muốn đổi)</h3>
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Mật khẩu mới</label>
                        <input type="password" name="password"
                               style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.95rem; font-weight: 600; outline: none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 0.75rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Xác nhận mật khẩu</label>
                        <input type="password" name="password_confirmation"
                               style="width: 100%; padding: 10px 14px; border-radius: 8px; border: 1px solid #e2e8f0; font-size: 0.95rem; font-weight: 600; outline: none;">
                    </div>
                </div>
            </div>

            <div style="margin-top: 2.5rem; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn-pro btn-green" style="padding: 12px 40px; font-size: 1rem;">
                    <i data-lucide="save" style="width: 20px;"></i>
                    Cập nhật hồ sơ
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
吐
吐
吐
吐
吐
