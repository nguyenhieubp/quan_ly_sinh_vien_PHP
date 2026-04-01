@extends('layouts.app')

@section('title', 'Tổng quan hệ thống')

@section('content')
<!-- Header Section -->
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 2rem;">
    <div>
        <h2 style="font-size: 1.5rem; color: var(--text-title); margin-bottom: 0.25rem;">Bảng điều khiển</h2>
        <p style="color: var(--text-body); font-size: var(--fs-base);">Chào mừng bạn trở lại, hệ thống đang hoạt động ổn định.</p>
    </div>
    <div style="text-align: right;">
        <div style="font-weight: 600; color: var(--text-title); font-size: 1rem;">{{ now()->translatedFormat('l, d F Y') }}</div>
        <div style="color: var(--text-body); font-size: var(--fs-sm);">Học kỳ hiện tại: HK2 2023-2024</div>
    </div>
</div>

<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1.5rem; margin-bottom: 2rem;">
    <!-- Students -->
    <div class="card" style="padding: 1.5rem; border-left: 4px solid var(--brand-primary);">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="font-size: var(--fs-sm); color: var(--text-body); margin-bottom: 0.5rem; font-weight: 500;">SINH VIÊN</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: var(--text-title);">{{ \App\Models\Student::count() }}</div>
            </div>
            <div style="padding: 10px; background: #eef2ff; border-radius: 12px; color: var(--brand-primary);">
                <i data-lucide="users" style="width: 24px;"></i>
            </div>
        </div>
        <div style="margin-top: 1rem; font-size: var(--fs-xs); color: #10b981; display: flex; align-items: center; gap: 4px;">
            <i data-lucide="arrow-up" style="width: 14px;"></i>
            <span>12 mới trong tuần này</span>
        </div>
    </div>

    <!-- Teachers -->
    <div class="card" style="padding: 1.5rem; border-left: 4px solid #f59e0b;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="font-size: var(--fs-sm); color: var(--text-body); margin-bottom: 0.5rem; font-weight: 500;">GIẢNG VIÊN</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: var(--text-title);">{{ \App\Models\Teacher::count() }}</div>
            </div>
            <div style="padding: 10px; background: #fff7ed; border-radius: 12px; color: #f59e0b;">
                <i data-lucide="users-2" style="width: 24px;"></i>
            </div>
        </div>
        <div style="margin-top: 1rem; font-size: var(--fs-xs); color: var(--text-body);">Công tác tại 3 khoa</div>
    </div>

    <!-- Subjects -->
    <div class="card" style="padding: 1.5rem; border-left: 4px solid #10b981;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="font-size: var(--fs-sm); color: var(--text-body); margin-bottom: 0.5rem; font-weight: 500;">MÔN HỌC</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: var(--text-title);">{{ \App\Models\Subject::count() }}</div>
            </div>
            <div style="padding: 10px; background: #f0fdf4; border-radius: 12px; color: #10b981;">
                <i data-lucide="book-open" style="width: 24px;"></i>
            </div>
        </div>
        <div style="margin-top: 1rem; font-size: var(--fs-xs); color: var(--text-body);">8 môn đang mở lớp</div>
    </div>

    <!-- Classrooms -->
    <div class="card" style="padding: 1.5rem; border-left: 4px solid #6366f1;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start;">
            <div>
                <div style="font-size: var(--fs-sm); color: var(--text-body); margin-bottom: 0.5rem; font-weight: 500;">LỚP HỌC</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: var(--text-title);">{{ \App\Models\Classroom::count() }}</div>
            </div>
            <div style="padding: 10px; background: #eef2ff; border-radius: 12px; color: #6366f1;">
                <i data-lucide="hotel" style="width: 24px;"></i>
            </div>
        </div>
        <div style="margin-top: 1rem; font-size: var(--fs-xs); color: var(--text-body);">Phủ kín 90% phòng</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
    <!-- Main Content Col -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Recent Admissions -->
        <div class="card" style="padding: 0;">
            <div style="padding: 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                <h3 style="font-size: 1.1rem; color: var(--text-title);">Sinh viên mới đăng ký</h3>
                <a href="/students" style="font-size: var(--fs-sm); color: var(--brand-primary); text-decoration: none; font-weight: 500;">Xem tất cả</a>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc; text-align: left;">
                            <th style="padding: 1rem 1.5rem; font-size: var(--fs-xs); color: #64748b; font-weight: 600;">HỌ TÊN</th>
                            <th style="padding: 1rem 1.5rem; font-size: var(--fs-xs); color: #64748b; font-weight: 600;">MSSV</th>
                            <th style="padding: 1rem 1.5rem; font-size: var(--fs-xs); color: #64748b; font-weight: 600;">LỚP</th>
                            <th style="padding: 1rem 1.5rem; font-size: var(--fs-xs); color: #64748b; font-weight: 600;">NGÀY NHẬP HỌC</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach(\App\Models\Student::latest()->take(5)->get() as $student)
                        <tr style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 1rem 1.5rem; font-size: var(--fs-base); color: var(--text-title); font-weight: 500;">{{ $student->name }}</td>
                            <td style="padding: 1rem 1.5rem; font-size: var(--fs-base); color: var(--text-body);">{{ $student->student_code }}</td>
                            <td style="padding: 1rem 1.5rem;">
                                <span style="padding: 4px 8px; background: #eff6ff; color: #1e40af; border-radius: 6px; font-size: var(--fs-xs); font-weight: 600;">
                                    {{ $student->classroom->name ?? 'N/A' }}
                                </span>
                            </td>
                            <td style="padding: 1rem 1.5rem; font-size: var(--fs-base); color: var(--text-body);">{{ $student->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

 
    </div>

    <!-- Sidebar Col -->
    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <!-- Quick Menu -->
        <div class="card" style="padding: 1.5rem; background: linear-gradient(to bottom, #ffffff, #f8fafc);">
            <h3 style="font-size: 1rem; margin-bottom: 1.25rem; color: var(--text-title);">Thao tác nhanh</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <a href="/students/create" style="display: flex; flex-direction: column; align-items: center; gap: 8px; text-decoration: none; padding: 1rem; background: #f1f5f9; border-radius: 12px; transition: transform 0.2s;">
                    <i data-lucide="user-plus" style="color: var(--brand-primary);"></i>
                    <span style="font-size: var(--fs-xs); color: var(--text-body); font-weight: 600;">Thêm SV</span>
                </a>
                <a href="/grades/create" style="display: flex; flex-direction: column; align-items: center; gap: 8px; text-decoration: none; padding: 1rem; background: #f1f5f9; border-radius: 12px; transition: transform 0.2s;">
                    <i data-lucide="award" style="color: #10b981;"></i>
                    <span style="font-size: var(--fs-xs); color: var(--text-body); font-weight: 600;">Nhập điểm</span>
                </a>
                <a href="/schedules/create" style="display: flex; flex-direction: column; align-items: center; gap: 8px; text-decoration: none; padding: 1rem; background: #f1f5f9; border-radius: 12px; transition: transform 0.2s;">
                    <i data-lucide="calendar-plus" style="color: #6366f1;"></i>
                    <span style="font-size: var(--fs-xs); color: var(--text-body); font-weight: 600;">Lên lịch</span>
                </a>
                <a href="/subjects/create" style="display: flex; flex-direction: column; align-items: center; gap: 8px; text-decoration: none; padding: 1rem; background: #f1f5f9; border-radius: 12px; transition: transform 0.2s;">
                    <i data-lucide="book-plus" style="color: #f59e0b;"></i>
                    <span style="font-size: var(--fs-xs); color: var(--text-body); font-weight: 600;">Thêm môn</span>
                </a>
            </div>
        </div>

        <!-- Today's Schedule -->
        <div class="card" style="padding: 1.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h3 style="font-size: 1rem; color: var(--text-title);">Lịch học hôm nay</h3>
                <span style="padding: 4px 8px; background: #fee2e2; color: #ef4444; border-radius: 20px; font-size: 0.7rem; font-weight: 700;">HÔM NAY</span>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @forelse(\App\Models\Schedule::with(['subject'])->take(4)->get() as $item)
                <div style="display: flex; gap: 1rem; padding-bottom: 1rem; border-bottom: 1px dashed #e2e8f0;">
                    <div style="display: flex; flex-direction: column; align-items: center; min-width: 50px;">
                        <div style="font-size: var(--fs-sm); font-weight: 700; color: var(--brand-primary);">{{ $item->start_time }}</div>
                        <div style="font-size: 0.65rem; color: #94a3b8;">{{ $item->day_of_week }}</div>
                    </div>
                    <div style="flex: 1;">
                        <div style="font-size: var(--fs-base); font-weight: 600; color: var(--text-title); line-height: 1.2; margin-bottom: 4px;">{{ $item->subject->name }}</div>
                        <div style="font-size: var(--fs-xs); color: var(--text-body); display: flex; align-items: center; gap: 4px;">
                            <i data-lucide="map-pin" style="width: 12px;"></i> Room: {{ $item->classroom_id }}
                        </div>
                    </div>
                </div>
                @empty
                <div style="text-align: center; color: #94a3b8; padding: 2rem 0;">
                    <i data-lucide="calendar-x" style="width: 32px; margin-bottom: 10px;"></i>
                    <p style="font-size: var(--fs-sm);">Không có lịch học</p>
                </div>
                @endforelse
            </div>
            <a href="/schedules" class="btn btn-outline" style="width: 100%; margin-top: 1rem; font-size: var(--fs-xs);">Xem toàn bộ lịch</a>
        </div>

        <!-- Pending Registrations Notification -->
        @php $pendingCount = \App\Models\CourseRegistration::where('status', 'Pending')->count(); @endphp
        @if($pendingCount > 0)
        <div class="card" style="padding: 1.25rem; background: #fffbeb; border: 1px solid #fde68a; display: flex; align-items: center; gap: 1rem;">
            <div style="width: 40px; height: 40px; border-radius: 50%; background: #fbbf24; color: white; display:flex; align-items:center; justify-content:center;">
                <i data-lucide="bell-ring" style="width: 20px;"></i>
            </div>
            <div style="flex: 1;">
                <div style="font-size: var(--fs-sm); font-weight: 700; color: #92400e;">Thông báo mới</div>
                <div style="font-size: var(--fs-xs); color: #b45309;">Bạn có {{ $pendingCount }} yêu cầu đăng ký môn học cần duyệt.</div>
            </div>
        </div>
        @endif
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
