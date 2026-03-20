@extends('layouts.student')

@section('title', 'Thông tin cá nhân')

@section('content')
<div style="max-width: 800px; margin: 0 auto;">
    <form action="{{ route('student.profile.update') }}" method="POST">
        @csrf
        
        <!-- Basic Info -->
        <div class="card">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-title); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
                <i data-lucide="user" style="width: 20px; color: var(--brand-primary);"></i>
                Thông tin cơ bản
            </h3>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 8px;">Họ và Tên</label>
                    <input type="text" name="name" value="{{ old('name', $student->name) }}" required
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid var(--border-color); font-size: 0.95rem; font-weight: 600; color: var(--text-title); outline: none; transition: all 0.2s;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 8px;">Mã sinh viên (Không thể sửa)</label>
                    <input type="text" value="{{ $student->student_code }}" disabled
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid var(--border-color); font-size: 0.95rem; font-weight: 600; color: #94a3b8; background: #f8fafc; outline: none;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 8px;">Lớp học (Không thể sửa)</label>
                    <input type="text" value="{{ $student->classroom->name }}" disabled
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid var(--border-color); font-size: 0.95rem; font-weight: 600; color: #94a3b8; background: #f8fafc; outline: none;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 8px;">Email</label>
                    <input type="email" name="email" value="{{ old('email', $student->email) }}"
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid var(--border-color); font-size: 0.95rem; font-weight: 600; color: var(--text-title); outline: none;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 8px;">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone', $student->phone) }}"
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid var(--border-color); font-size: 0.95rem; font-weight: 600; color: var(--text-title); outline: none;">
                </div>

                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 8px;">Địa chỉ liên hệ</label>
                    <textarea name="address" rows="3"
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid var(--border-color); font-size: 0.95rem; font-weight: 600; color: var(--text-title); outline: none; resize: vertical;">{{ old('address', $student->address) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Security -->
        <div class="card">
            <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-title); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
                <i data-lucide="shield-check" style="width: 20px; color: var(--brand-primary);"></i>
                Đổi mật khẩu
            </h3>
            <p style="font-size: 0.85rem; color: var(--text-body); margin-bottom: 1.5rem;">Để trống nếu bạn không muốn thay đổi mật khẩu hiện tại.</p>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 8px;">Mật khẩu mới</label>
                    <input type="password" name="password" placeholder="••••••••"
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid var(--border-color); font-size: 0.95rem; outline: none;">
                </div>

                <div>
                    <label style="display: block; font-size: 0.8rem; font-weight: 700; color: #64748b; margin-bottom: 8px;">Xác nhận mật khẩu</label>
                    <input type="password" name="password_confirmation" placeholder="••••••••"
                        style="width: 100%; padding: 12px; border-radius: 12px; border: 1.5px solid var(--border-color); font-size: 0.95rem; outline: none;">
                </div>
            </div>
        </div>

        <div style="display: flex; justify-content: flex-end; gap: 1rem;">
            <button type="reset" class="btn" style="background: white; border: 1.5px solid var(--border-color); color: #64748b;">Hủy bỏ</button>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
        </div>
    </form>
</div>

<style>
    input:focus, textarea:focus {
        border-color: var(--brand-primary) !important;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
    }
</style>
@endsection
