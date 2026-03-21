@extends('layouts.student')

@section('title', 'Thông tin cá nhân')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('student.profile.update') }}" method="POST">
        @csrf
        
        <!-- Basic Info -->
        <div class="pro-card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-title); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px;" class="brand-font">
                <i data-lucide="user" style="width: 18px; color: var(--brand-primary); stroke-width: 2.5px;"></i>
                Thông tin cơ bản
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Họ và Tên</label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}" required
                        style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.85rem; font-weight: 600; color: var(--text-title); outline: none; transition: all 0.2s;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Mã sinh viên</label>
                    <input type="text" value="{{ $student->student_code }}" disabled
                        style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.85rem; font-weight: 600; color: #94a3b8; background: #f9fafb; cursor: not-allowed; outline: none;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Lớp học</label>
                    <input type="text" value="{{ $student->classroom->name }}" disabled
                        style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.85rem; font-weight: 600; color: #94a3b8; background: #f9fafb; cursor: not-allowed; outline: none;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Email</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}"
                        style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.85rem; font-weight: 600; color: var(--text-title); outline: none;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                        style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.85rem; font-weight: 600; color: var(--text-title); outline: none;">
                </div>

                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Địa chỉ liên hệ</label>
                    <textarea name="address" rows="2"
                        style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.85rem; font-weight: 600; color: var(--text-title); outline: none; resize: vertical;">{{ old('address', $student->address) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Security -->
        <div class="pro-card" style="padding: 1.5rem;">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-title); margin-bottom: 1rem; display: flex; align-items: center; gap: 8px;" class="brand-font">
                <i data-lucide="shield-check" style="width: 18px; color: var(--brand-primary); stroke-width: 2.5px;"></i>
                Đổi mật khẩu
            </h3>
            <p style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500; margin-bottom: 1.25rem;">Để trống nếu bạn không muốn thay đổi mật khẩu hiện tại.</p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem;">
                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Mật khẩu mới</label>
                    <input type="password" name="password" placeholder="••••••••"
                        style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.85rem; outline: none;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••"
                        style="width: 100%; padding: 8px 12px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.85rem; outline: none;">
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 0.75rem; margin-top: 1.5rem;">
            <button type="reset" class="btn-pro" style="background: white; border: 1px solid var(--border-light); color: var(--text-muted); font-size: 0.75rem;">Hủy bỏ</button>
            <button type="submit" class="btn-pro btn-primary" style="padding: 8px 24px;">Lưu thay đổi</button>
        </div>
    </form>
</div>

<style>
    input:focus, textarea:focus {
        border-color: var(--brand-primary) !important;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.05) !important;
    }
</style>
@endsection
