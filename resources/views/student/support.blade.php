@extends('layouts.student')

@section('title', 'Trung tâm Hỗ trợ')

@section('content')
<div style="max-width: 900px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
    <!-- Contact Card -->
    <div class="card">
        <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-title); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
            <i data-lucide="phone-call" style="width: 20px; color: var(--brand-primary);"></i>
            Thông tin liên hệ
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <div style="display: flex; gap: 12px;">
                <div style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i data-lucide="mail" style="width: 18px; color: var(--brand-primary);"></i>
                </div>
                <div>
                    <div style="font-size: 0.8rem; font-weight: 700; color: #64748b;">Phòng đào tạo</div>
                    <div style="font-size: 0.95rem; font-weight: 600; color: var(--brand-primary);">daotao@edusystem.vn</div>
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <div style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i data-lucide="phone" style="width: 18px; color: var(--brand-primary);"></i>
                </div>
                <div>
                    <div style="font-size: 0.8rem; font-weight: 700; color: #64748b;">Hotline hỗ trợ</div>
                    <div style="font-size: 0.95rem; font-weight: 600; color: var(--text-title);">(024) 3333 8888</div>
                </div>
            </div>

            <div style="display: flex; gap: 12px;">
                <div style="width: 40px; height: 40px; background: #f1f5f9; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                    <i data-lucide="map-pin" style="width: 18px; color: var(--brand-primary);"></i>
                </div>
                <div>
                    <div style="font-size: 0.8rem; font-weight: 700; color: #64748b;">Địa chỉ văn phòng</div>
                    <div style="font-size: 0.95rem; font-weight: 600; color: var(--text-title);">Tầng 2, Tòa nhà A1, Khu đô thị Đại học</div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ / Quick Help -->
    <div class="card">
        <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-title); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 10px;">
            <i data-lucide="help-circle" style="width: 20px; color: var(--brand-primary);"></i>
            Câu hỏi thường gặp
        </h3>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div style="padding: 1rem; background: #f8fafc; border-radius: 12px; border: 1px solid var(--border-color);">
                <div style="font-size: 0.9rem; font-weight: 800; color: var(--text-title); margin-bottom: 4px;">Làm sao để đổi mật khẩu?</div>
                <div style="font-size: 0.85rem; color: #64748b;">Bạn có thể vào mục "Thông tin cá nhân" để thực hiện thay đổi mật khẩu truy cập.</div>
            </div>

            <div style="padding: 1rem; background: #f8fafc; border-radius: 12px; border: 1px solid var(--border-color);">
                <div style="font-size: 0.9rem; font-weight: 800; color: var(--text-title); margin-bottom: 4px;">Xem lịch thi ở đâu?</div>
                <div style="font-size: 0.85rem; color: #64748b;">Lịch thi sẽ được cập nhật tại mục "Thông báo" hoặc "Lịch học" khi gần đến kỳ thi.</div>
            </div>

            <div style="padding: 1rem; background: #f8fafc; border-radius: 12px; border: 1px solid var(--border-color);">
                <div style="font-size: 0.9rem; font-weight: 800; color: var(--text-title); margin-bottom: 4px;">Quên mật khẩu thì phải làm gì?</div>
                <div style="font-size: 0.85rem; color: #64748b;">Vui lòng liên hệ trực tiếp với Phòng Đào tạo (Hotline hoặc Email) để được cấp lại mật khẩu.</div>
            </div>
        </div>
    </div>
</div>
@endsection
