@extends('layouts.app')

@section('title', 'Quản lý Lịch học')

@section('content')
<div class="card" style="padding: 0; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
        <div>
            @if(isset($classroom))
                <a href="{{ route('schedules.index', ['department_id' => $classroom->department->id]) }}" style="display: inline-flex; align-items: center; gap: 0.4rem; text-decoration: none; color: #94a3b8; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                    <i data-lucide="arrow-left" style="width: 12px;"></i>
                    Quay lại danh sách Lớp ({{ $classroom->department->name }})
                </a>
                <h3 style="font-size: 1.15rem; color: var(--text-title); margin-bottom: 0.25rem; font-weight: 700;">Lịch học Lớp {{ $classroom->name }}</h3>
            @elseif(isset($department))
                <a href="{{ route('schedules.index') }}" style="display: inline-flex; align-items: center; gap: 0.4rem; text-decoration: none; color: #94a3b8; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                    <i data-lucide="arrow-left" style="width: 12px;"></i>
                    Quay lại danh sách Khoa
                </a>
                <h3 style="font-size: 1.15rem; color: var(--text-title); margin-bottom: 0.25rem; font-weight: 700;">Danh sách Lớp học - Khoa {{ $department->name }}</h3>
            @else
                <h3 style="font-size: 1.15rem; color: var(--text-title); margin-bottom: 0.25rem; font-weight: 700;">Phân loại Lịch học theo Khoa</h3>
            @endif
            <p style="color: var(--text-body); font-size: var(--fs-xs);">Hệ thống quản lý lịch học tập trung theo đơn vị Khoa và Lớp học.</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            @if(isset($classroom))
                <a href="{{ route('schedules.create', ['classroom_id' => $classroom->id]) }}" class="btn btn-primary" style="font-size: var(--fs-xs); padding: 0.5rem 1rem;">
                    <i data-lucide="calendar-plus" style="width: 14px; margin-right: 4px;"></i>
                    Thêm lịch mới
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div style="margin: 1rem 1.5rem; background: #ecfdf5; color: #065f46; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid #a7f3d0; display: flex; align-items: center; gap: 0.75rem; font-size: var(--fs-sm);">
            <i data-lucide="check-circle" style="width: 16px;"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        @if(isset($classroom))
            <!-- LEVEL 3: Schedules for a specific classroom, grouped by subject -->
            <div style="padding: 1.5rem; display: flex; flex-direction: column; gap: 1.5rem;">
                @forelse($schedules as $subjectId => $items)
                    @php $subject = $items->first()->subject; @endphp
                    <div style="border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; background: #fdfdfd;">
                        <div style="padding: 0.75rem 1rem; background: #f8fafc; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <i data-lucide="book" style="width: 16px; color: var(--brand-primary);"></i>
                                <span style="font-weight: 800; color: var(--text-title); font-size: 13px;">{{ $subject->name }} ({{ $subject->code }})</span>
                            </div>
                            <span style="font-size: 10px; font-weight: 700; color: #94a3b8; text-transform: uppercase;">{{ $items->count() }} Buổi/tuần</span>
                        </div>
                        <table style="width: 100%; border-collapse: collapse;">
                            <thead>
                                <tr style="background: #ffffff; border-bottom: 1px solid #f1f5f9;">
                                    <th style="padding: 0.5rem 1rem; text-align: left; font-size: 10px; color: #94a3b8; text-transform: uppercase;">Thứ</th>
                                    <th style="padding: 0.5rem 1rem; text-align: left; font-size: 10px; color: #94a3b8; text-transform: uppercase;">Thời gian</th>
                                    <th style="padding: 0.5rem 1rem; text-align: left; font-size: 10px; color: #94a3b8; text-transform: uppercase;">Phòng</th>
                                    <th style="padding: 0.5rem 1rem; text-align: left; font-size: 10px; color: #94a3b8; text-transform: uppercase;">Giảng viên</th>
                                    <th style="padding: 0.5rem 1rem; text-align: right; font-size: 10px; color: #94a3b8; text-transform: uppercase;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $days = ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7']; @endphp
                                @foreach($items->sortBy('day_of_week') as $sche)
                                    <tr style="border-bottom: 1px solid #f1f5f9; transition: background 0.2s;">
                                        <td style="padding: 0.75rem 1rem; font-weight: 700; color: var(--brand-primary); font-size: 12px;">{{ $days[$sche->day_of_week] }}</td>
                                        <td style="padding: 0.75rem 1rem; font-size: 12px; color: #475569;">
                                            <div style="display: flex; align-items: center; gap: 4px;">
                                                <i data-lucide="clock" style="width: 12px;"></i>
                                                {{ \Carbon\Carbon::parse($sche->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($sche->end_time)->format('H:i') }}
                                            </div>
                                        </td>
                                        <td style="padding: 0.75rem 1rem;">
                                            <span style="font-weight: 800; color: #f59e0b; background: #fffbeb; padding: 2px 6px; border-radius: 4px; font-size: 11px;">{{ $sche->room }}</span>
                                        </td>
                                        <td style="padding: 0.75rem 1rem; font-size: 12px; color: #64748b;">{{ $sche->teacher->name }}</td>
                                        <td style="padding: 0.75rem 1rem; text-align: right;">
                                            <div style="display: flex; gap: 0.3rem; justify-content: flex-end;">
                                                <a href="{{ route('schedules.edit', $sche->id) }}" style="color: #94a3b8; padding: 2px;" title="Sửa">
                                                    <i data-lucide="edit-3" style="width: 14px;"></i>
                                                </a>
                                                <form action="{{ route('schedules.destroy', $sche->id) }}" method="POST" onsubmit="return confirm('Xóa lịch học này?')" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" style="background:none; border:none; color: #fca5a5; padding: 2px; cursor: pointer;" title="Xóa">
                                                        <i data-lucide="trash-2" style="width: 14px;"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @empty
                    <div style="padding: 4rem; text-align: center; color: #94a3b8; background: #f8fafc; border-radius: 12px; border: 2px dashed #e2e8f0;">
                        <i data-lucide="calendar-x" style="width: 48px; height: 48px; margin-bottom: 1rem; opacity: 0.3;"></i>
                        <p style="font-size: 14px; font-weight: 600; margin: 0;">Lớp học này chưa được xếp lịch đào tạo.</p>
                        <a href="{{ route('schedules.create', ['classroom_id' => $classroom->id]) }}" style="display: inline-block; margin-top: 1rem; color: var(--brand-primary); font-weight: 800; font-size: 12px; text-decoration: underline;">Xếp lịch ngay</a>
                    </div>
                @endforelse
            </div>
        @elseif(isset($department))
            <!-- LEVEL 2: Classrooms for a specific department -->
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 15%;">MÃ LỚP</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0;">TÊN LỚP HỌC</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 150px;">SỐ MÔN CÓ LỊCH</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 180px;">THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($department->classrooms as $cls)
                    <tr class="table-row" style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 0.85rem 1rem; font-weight: 700; color: var(--brand-primary); font-family: monospace; font-size: var(--fs-sm);">{{ $cls->code }}</td>
                        <td style="padding: 0.85rem 1rem; font-weight: 600; color: var(--text-title); font-size: var(--fs-sm);">{{ $cls->name }}</td>
                        <td style="padding: 0.85rem 1rem; text-align: center;">
                            <span style="padding: 4px 10px; background: #eff6ff; color: #1e40af; border-radius: 6px; font-size: var(--fs-xs); font-weight: 700; border: 1px solid #dbeafe;">
                                {{ $cls->schedules_count }} môn
                            </span>
                        </td>
                        <td style="padding: 0.85rem 1rem; text-align: right;">
                            <a href="{{ route('schedules.index', ['department_id' => $department->id, 'classroom_id' => $cls->id]) }}" class="btn btn-primary" style="font-size: 11px; padding: 0.4rem 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                Xem lịch học
                                <i data-lucide="calendar" style="width: 12px;"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 3rem; text-align: center; color: #94a3b8; font-size: var(--fs-sm); font-weight: 600;">Khoa chưa có lớp học nào được tạo.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <!-- LEVEL 1: Departments list -->
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 15%;">MÃ KHOA</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0;">TÊN KHOA ĐÀO TẠ</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 150px;">SỐ LỚP HỌC</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 180px;">THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $dept)
                    <tr class="table-row" style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 0.85rem 1rem; font-weight: 700; color: var(--brand-primary); font-family: monospace; font-size: var(--fs-sm);">{{ $dept->code }}</td>
                        <td style="padding: 0.85rem 1rem; font-weight: 600; color: var(--text-title); font-size: var(--fs-sm);">{{ $dept->name }}</td>
                        <td style="padding: 0.85rem 1rem; text-align: center;">
                            <span style="padding: 4px 10px; background: #f8fafc; border: 1px solid #e2e8f0; color: #64748b; border-radius: 6px; font-size: var(--fs-xs); font-weight: 700;">
                                {{ $dept->classrooms_count }} lớp
                            </span>
                        </td>
                        <td style="padding: 0.85rem 1rem; text-align: right;">
                            <a href="{{ route('schedules.index', ['department_id' => $dept->id]) }}" class="btn btn-primary" style="font-size: 11px; padding: 0.4rem 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 4px;">
                                Xem danh sách lớp
                                <i data-lucide="chevron-right" style="width: 12px;"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<style>
    .table-row:hover { background: #f8fafc; }
    .btn-icon { background: #ffffff; border: 1px solid #e2e8f0; padding: 6px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; text-decoration: none; border-style: solid; box-sizing: border-box; }
    .btn-icon:hover { background: #f1f5f9; border-color: #cbd5e1; transform: translateY(-1px); }
</style>

<script>
    lucide.createIcons();
</script>
@endsection
