@extends('layouts.student')

@section('title', 'Trung tâm Hỗ trợ')

@section('content')
<div style="max-width: 900px; margin: 0 auto; display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
    <!-- Contact Card -->
    <div class="pro-card" style="padding: 1.5rem;">
        <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-title); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px;" class="brand-font">
            <i data-lucide="phone-call" style="width: 18px; color: var(--brand-primary); stroke-width: 2.5px;"></i>
            Thông tin liên hệ
        </h3>
        
        <div style="display: flex; flex-direction: column; gap: 1.25rem;">
            <div style="display: flex; gap: 12px; align-items: center;">
                <div style="width: 36px; height: 36px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--border-light);">
                    <i data-lucide="mail" style="width: 16px; color: var(--brand-primary);"></i>
                </div>
                <div>
                    <div style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Phòng đào tạo</div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: var(--brand-primary);">daotao@edusystem.vn</div>
                </div>
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <div style="width: 36px; height: 36px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--border-light);">
                    <i data-lucide="phone" style="width: 16px; color: var(--brand-primary);"></i>
                </div>
                <div>
                    <div style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Hotline hỗ trợ</div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-title);">(024) 3333 8888</div>
                </div>
            </div>

            <div style="display: flex; gap: 12px; align-items: center;">
                <div style="width: 36px; height: 36px; background: #f8fafc; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; border: 1px solid var(--border-light);">
                    <i data-lucide="map-pin" style="width: 16px; color: var(--brand-primary);"></i>
                </div>
                <div>
                    <div style="font-size: 0.65rem; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px;">Địa chỉ văn phòng</div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: var(--text-title);">Tòa nhà A1, Khu đô thị Đại học</div>
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ / Quick Help -->
    <div class="pro-card" style="padding: 1.5rem;">
        <h3 style="font-size: 1.1rem; font-weight: 800; color: var(--text-title); margin-bottom: 1.5rem; display: flex; align-items: center; gap: 8px;" class="brand-font">
            <i data-lucide="help-circle" style="width: 18px; color: var(--brand-primary); stroke-width: 2.5px;"></i>
            Câu hỏi thường gặp
        </h3>

        <div style="display: flex; flex-direction: column; gap: 0.75rem;">
            <div style="padding: 0.75rem 1rem; background: #f9fafb; border-radius: 10px; border: 1px solid var(--border-light);">
                <div style="font-size: 0.8rem; font-weight: 800; color: var(--text-title); margin-bottom: 4px;">Làm sao để đổi mật khẩu?</div>
                <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">Bạn có thể vào mục "Thông tin cá nhân" để thực hiện thay đổi mật khẩu truy cập.</div>
            </div>

            <div style="padding: 0.75rem 1rem; background: #f9fafb; border-radius: 10px; border: 1px solid var(--border-light);">
                <div style="font-size: 0.8rem; font-weight: 800; color: var(--text-title); margin-bottom: 4px;">Xem lịch thi ở đâu?</div>
                <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">Lịch thi sẽ được cập nhật tại mục "Thông báo" hoặc "Lịch học" khi gần đến kỳ thi.</div>
            </div>

            <div style="padding: 0.75rem 1rem; background: #f9fafb; border-radius: 10px; border: 1px solid var(--border-light);">
                <div style="font-size: 0.8rem; font-weight: 800; color: var(--text-title); margin-bottom: 4px;">Quên mật khẩu?</div>
                <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 500;">Vui lòng liên hệ trực tiếp với Phòng Đào tạo để được cấp lại mật khẩu.</div>
            </div>
        </div>
    </div>
</div>
@endsection
