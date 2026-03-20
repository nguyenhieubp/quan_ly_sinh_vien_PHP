@extends('layouts.app')

@section('title', 'Cập nhật Lịch học')

@section('content')
<div style="max-width: 1000px; margin: 0 auto; padding: 1rem;">
    <!-- Header / Breadcrumbs -->
    <div style="margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: flex-end;">
        <div>
            <a href="{{ route('schedules.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #64748b; font-weight: 600; font-size: var(--fs-xs); margin-bottom: 0.75rem; transition: color 0.2s;" onmouseover="this.style.color='var(--brand-primary)'" onmouseout="this.style.color='#64748b'">
                <i data-lucide="arrow-left" style="width: 14px;"></i>
                Quay lại danh sách
            </a>
            <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-title); margin: 0; letter-spacing: -0.02em;">Cập nhật chi tiết Lịch học</h2>
        </div>
        <div style="text-align: right;">
            <span style="display: block; font-size: 11px; color: #94a3b8; text-transform: uppercase; font-weight: 700; letter-spacing: 0.05em;">Phiên hiệu lịch</span>
            <span style="font-family: monospace; font-size: 1.25rem; font-weight: 800; color: var(--brand-primary);">#SCH-{{ str_pad($schedule->id, 4, '0', STR_PAD_LEFT) }}</span>
        </div>
    </div>

    @if ($errors->any())
        <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem 1.25rem; border-radius: 12px; margin-bottom: 2rem; display: flex; gap: 0.75rem; align-items: flex-start;">
            <i data-lucide="alert-circle" style="width: 18px; color: #ef4444; flex-shrink: 0; margin-top: 2px;"></i>
            <div style="font-size: var(--fs-sm);">
                <div style="font-weight: 700; margin-bottom: 0.25rem;">Xung đột dữ liệu:</div>
                <ul style="margin: 0; padding-left: 1.25rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <form action="{{ route('schedules.update', $schedule->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div style="display: grid; grid-template-columns: 1fr 300px; gap: 2rem; align-items: start;">
            <!-- Main Content Area -->
            <div style="display: grid; gap: 1.5rem;">
                <!-- Section: Academic Assignment -->
                <div class="card" style="padding: 2rem; border-radius: 12px; border: 1px solid var(--border-color); background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.25rem;">
                        <div style="padding: 10px; background: #fff7ed; border-radius: 10px; color: #f97316;">
                            <i data-lucide="graduation-cap" style="width: 20px;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: var(--fs-base); font-weight: 700; color: var(--text-title); margin: 0;">Phân bổ đào tạo</h4>
                            <p style="font-size: var(--fs-xs); color: #64748b; margin: 2px 0 0 0;">Thiết lập Môn học, Lớp học và Giảng viên</p>
                        </div>
                    </div>

                    <div style="display: grid; gap: 1.75rem;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
                            <div>
                                <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Môn học</label>
                                <div style="position: relative;">
                                    <i data-lucide="book-open" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                    <select name="subject_id" style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; appearance: none; cursor: pointer;" required>
                                        @foreach($subjects as $sub)
                                            <option value="{{ $sub->id }}" {{ old('subject_id', $schedule->subject_id) == $sub->id ? 'selected' : '' }}>
                                                {{ $sub->name }} ({{ $sub->code }})
                                            </option>
                                        @endforeach
                                    </select>
                                    <i data-lucide="chevron-down" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8; pointer-events: none;"></i>
                                </div>
                            </div>

                            <div>
                                <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Lớp học</label>
                                <div style="position: relative;">
                                    <i data-lucide="users" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                    <select name="classroom_id" style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; appearance: none; cursor: pointer;" required>
                                        @foreach($classrooms as $cls)
                                            <option value="{{ $cls->id }}" {{ old('classroom_id', $schedule->classroom_id) == $cls->id ? 'selected' : '' }}>
                                                {{ $cls->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <i data-lucide="chevron-down" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8; pointer-events: none;"></i>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Giảng viên phụ trách</label>
                            <div style="position: relative;">
                                <i data-lucide="user-check" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                <select name="teacher_id" style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; appearance: none; cursor: pointer;" required>
                                    @foreach($teachers as $teacher)
                                        <option value="{{ $teacher->id }}" {{ old('teacher_id', $schedule->teacher_id) == $teacher->id ? 'selected' : '' }}>
                                            {{ $teacher->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <i data-lucide="chevron-down" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8; pointer-events: none;"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section: Time & Location -->
                <div class="card" style="padding: 2rem; border-radius: 12px; border: 1px solid var(--border-color); background: #ffffff; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 2rem; border-bottom: 1px solid #f1f5f9; padding-bottom: 1.25rem;">
                        <div style="padding: 10px; background: #f0fdf4; border-radius: 10px; color: #16a34a;">
                            <i data-lucide="calendar-clock" style="width: 20px;"></i>
                        </div>
                        <div>
                            <h4 style="font-size: var(--fs-base); font-weight: 700; color: var(--text-title); margin: 0;">Thời gian & Địa điểm</h4>
                            <p style="font-size: var(--fs-xs); color: #64748b; margin: 2px 0 0 0;">Thiết lập trục thời gian và phòng học</p>
                        </div>
                    </div>

                    <div style="display: grid; gap: 1.75rem;">
                        <div style="display: grid; grid-template-columns: 1.2fr 1fr 1fr; gap: 1.25rem;">
                            <div>
                                <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Thứ trong tuần</label>
                                <div style="position: relative;">
                                    <i data-lucide="calendar-days" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                    <select name="day_of_week" style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; appearance: none; cursor: pointer;" required>
                                        <option value="1" {{ $schedule->day_of_week == 1 ? 'selected' : '' }}>Thứ Hai</option>
                                        <option value="2" {{ $schedule->day_of_week == 2 ? 'selected' : '' }}>Thứ Ba</option>
                                        <option value="3" {{ $schedule->day_of_week == 3 ? 'selected' : '' }}>Thứ Tư</option>
                                        <option value="4" {{ $schedule->day_of_week == 4 ? 'selected' : '' }}>Thứ Năm</option>
                                        <option value="5" {{ $schedule->day_of_week == 5 ? 'selected' : '' }}>Thứ Sáu</option>
                                        <option value="6" {{ $schedule->day_of_week == 6 ? 'selected' : '' }}>Thứ Bảy</option>
                                        <option value="0" {{ $schedule->day_of_week == 0 ? 'selected' : '' }}>Chủ Nhật</option>
                                    </select>
                                    <i data-lucide="chevron-down" style="position: absolute; right: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8; pointer-events: none;"></i>
                                </div>
                            </div>
                            <div>
                                <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Giờ bắt đầu</label>
                                <div style="position: relative;">
                                    <i data-lucide="clock-3" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                    <input type="time" name="start_time" value="{{ old('start_time', \Carbon\Carbon::parse($schedule->start_time)->format('H:i')) }}" 
                                        style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe;" required>
                                </div>
                            </div>
                            <div>
                                <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Giờ kết thúc</label>
                                <div style="position: relative;">
                                    <i data-lucide="clock-9" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                    <input type="time" name="end_time" value="{{ old('end_time', \Carbon\Carbon::parse($schedule->end_time)->format('H:i')) }}" 
                                        style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe;" required>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="form-label" style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.65rem; text-transform: uppercase;">Phòng học / Địa điểm</label>
                            <div style="position: relative;">
                                <i data-lucide="map-pin" style="position: absolute; left: 14px; top: 50%; transform: translateY(-50%); width: 16px; color: #94a3b8;"></i>
                                <input type="text" name="room" value="{{ old('room', $schedule->room) }}" placeholder="VD: Phòng A.201 hoặc Zoom Online" 
                                    style="width: 100%; padding: 0.85rem 1rem 0.85rem 2.75rem; border-radius: 10px; border: 1px solid var(--border-color); font-size: var(--fs-sm); background: #fbfcfe; transition: all 0.2s;" required onfocus="this.style.borderColor='var(--brand-primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.08)'; this.style.background='#ffffff'" onblur="this.style.borderColor='var(--border-color)'; this.style.boxShadow='none'; this.style.background='#fbfcfe'">
                            </div>
                        </div>
                    </div>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center; padding: 1rem; font-weight: 700; border-radius: 10px; font-size: var(--fs-sm);">
                        <i data-lucide="check-circle" style="width: 18px; margin-right: 8px;"></i>
                        Lưu cấu hình lịch học
                    </button>
                    <a href="{{ route('schedules.index') }}" class="btn btn-outline" style="flex: 0.4; justify-content: center; padding: 1rem; font-weight: 600; color: #64748b; background: transparent; border-radius: 10px; font-size: var(--fs-sm);">Hủy bỏ</a>
                </div>
            </div>

            <!-- Sidebar Column -->
            <div style="display: grid; gap: 1.5rem;">
                <!-- Card: Schedule Metadata -->
                <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 1.5rem;">
                    <h5 style="font-size: var(--fs-xs); font-weight: 700; color: var(--text-title); margin: 0 0 1.25rem 0; text-transform: uppercase; letter-spacing: 0.05em; display: flex; align-items: center; gap: 8px;">
                        <i data-lucide="info" style="width: 14px; color: var(--brand-primary);"></i>
                        Hệ thống ghi nhận
                    </h5>
                    <div style="display: grid; gap: 1rem;">
                        <div style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9;">
                            <span style="display: block; font-size: 10px; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; font-weight: 700;">Thời điểm lập lịch</span>
                            <span style="font-size: var(--fs-sm); color: var(--text-title); font-weight: 600;">{{ $schedule->created_at->format('d/m/Y') }}</span>
                        </div>
                        <div style="background: #f8fafc; padding: 12px; border-radius: 10px; border: 1px solid #f1f5f9;">
                            <span style="display: block; font-size: 10px; color: #94a3b8; text-transform: uppercase; margin-bottom: 4px; font-weight: 700;">Cập nhật cuối</span>
                            <span style="font-size: var(--fs-sm); color: var(--text-title); font-weight: 600;">{{ $schedule->updated_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

                <!-- Card: Conflict Warning -->
                <div style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 12px; padding: 1.5rem;">
                    <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; color: #be123c;">
                        <i data-lucide="shield-alert" style="width: 20px;"></i>
                        <h5 style="font-size: var(--fs-sm); font-weight: 700; margin: 0;">Kiểm tra xung đột</h5>
                    </div>
                    <p style="font-size: 12px; line-height: 1.6; color: #be123c; margin: 0;">
                        Vui lòng đảm bảo rằng <strong>Giảng viên</strong> và <strong>Phòng học</strong> đã chọn không trùng lịch với các học phần khác trong cùng khung giờ.
                    </p>
                </div>

                <!-- Card: Help Context -->
                <div style="background: #fdfaf6; border: 1px solid #fae8d0; border-radius: 12px; padding: 1.25rem; display: flex; gap: 0.75rem; align-items: flex-start;">
                    <i data-lucide="lightbulb" style="width: 18px; color: #f97316; flex-shrink: 0; margin-top: 2px;"></i>
                    <span style="font-size: 11px; line-height: 1.5; color: #c2410c; font-weight: 500;">Tip: Bạn có thể nhập ghi chú chi tiết hoặc link phòng học trực tuyến (Zoom/Teams) vào ô phòng học nếu cần thiết.</span>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
