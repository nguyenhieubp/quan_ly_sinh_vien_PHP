@php 
    $hasAttendance = $existingAttendance->isNotEmpty();
    $initialMode = $hasAttendance ? 'view' : 'edit';
@endphp

@extends('layouts.teacher')

@section('title', 'Ghi nhận điểm danh')

@section('context-navbar')
    <a href="{{ route('teacher.students', $schedule->id) }}" class="context-tab">Danh sách sinh viên</a>
    <a href="{{ route('teacher.attendance', $schedule->id) }}" class="context-tab active">Ghi nhận điểm danh</a>
    <a href="{{ route('teacher.grades', $schedule->id) }}" class="context-tab">Nhập điểm</a>
@endsection

@section('content')
<div class="pro-card" style="margin-bottom: 1.25rem; padding: 0; overflow: hidden;">
    <!-- Header Section -->
    <div style="padding: 1.25rem; border-bottom: 1px solid #f1f5f9; background: white; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 1.15rem; font-weight: 800; color: var(--text-title); margin: 0; letter-spacing: -0.5px;">{{ $subject->name }}</h2>
            <div style="font-size: 0.75rem; color: var(--text-muted); font-weight: 600; margin-top: 4px; display: flex; align-items: center; gap: 8px;">
                <span style="color: var(--text-title); font-weight: 800; background: #f1f5f9; padding: 2px 8px; border-radius: 4px;">{{ $classroom->name }}</span>
                <span style="width: 3px; height: 3px; background: #e2e8f0; border-radius: 50%;"></span>
                <span style="font-weight: 700;">{{ $subject->code }}</span>
                <span style="width: 3px; height: 3px; background: #e2e8f0; border-radius: 50%;"></span>
                <span style="color: var(--brand-primary); font-weight: 800;">NH {{ $schedule->academicYear->name ?? $classroom->academicYear->name }}</span>
            </div>
        </div>
        <div>
            @if($hasAttendance)
                <div style="background: #fff1f2; color: #be123c; border: 1px solid #ffe4e6; padding: 5px 12px; border-radius: 6px; font-size: 0.65rem; font-weight: 800; display: flex; align-items: center; gap: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i data-lucide="lock" style="width: 13px;"></i>
                    Dữ liệu đã khóa
                </div>
            @else
                <div style="background: #f0fdf4; color: #166534; border: 1px solid #dcfce7; padding: 5px 12px; border-radius: 6px; font-size: 0.65rem; font-weight: 800; display: flex; align-items: center; gap: 6px; text-transform: uppercase; letter-spacing: 0.5px;">
                    <i data-lucide="edit-3" style="width: 13px;"></i>
                    Chế độ ghi nhận
                </div>
            @endif
        </div>
    </div>

    <!-- Selection Bar -->
    <div style="padding: 1rem 1.25rem; background: #f9fafb; border-bottom: 1px solid #f1f5f9;">
        <form action="{{ route('teacher.attendance', $schedule->id) }}" method="GET" id="date-form" style="display: flex; gap: 12px; align-items: flex-end; flex-wrap: wrap;">
            <div style="width: 180px;">
                <label style="display: block; font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Ngày điểm danh</label>
                <input type="date" name="date" value="{{ $date->toDateString() }}" onchange="this.form.submit()" 
                       style="width: 100%; padding: 7px 10px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.8rem; font-weight: 700; color: var(--text-title); outline: none; background: white;">
            </div>

            <div style="width: 220px;">
                <label style="display: block; font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px; letter-spacing: 0.5px;">Lịch sử điểm danh</label>
                <select onchange="document.getElementsByName('date')[0].value = this.value; document.getElementById('date-form').submit();" 
                        style="width: 100%; padding: 7px 10px; border-radius: 8px; border: 1px solid var(--border-light); font-size: 0.8rem; font-weight: 600; color: #64748b; background: white; outline: none; cursor: pointer;">
                    <option value="">-- Chọn ngày đã điểm danh --</option>
                    @foreach($recordedDates as $rd)
                        <option value="{{ $rd }}" {{ $date->toDateString() == $rd ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($rd)->format('d/m/Y') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-left: auto;">
                @if(!$hasAttendance)
                    <button type="button" onclick="markAll('present')" class="btn-pro" style="padding: 7px 16px; font-size: 0.75rem; background: white; color: var(--text-title); border: 1px solid var(--border-light);">
                        <i data-lucide="check-square" style="width: 14px;"></i> Tất cả có mặt
                    </button>
                @else
                    <button type="button" onclick="location.reload()" class="btn-pro" style="padding: 7px 16px; font-size: 0.75rem; background: white; color: var(--brand-primary); border: 1px solid var(--border-light);">
                        <i data-lucide="refresh-cw" style="width: 14px;"></i> Làm mới dữ liệu
                    </button>
                @endif
            </div>
        </form>
    </div>
</div>

<form action="{{ route('teacher.attendance.save', $schedule->id) }}" method="POST" id="attendance-form">
    @csrf
    <input type="hidden" name="attendance_date" value="{{ $date->toDateString() }}">
    
    <div class="pro-card" style="padding: 0; overflow: hidden;">
        <div class="table-responsive">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="text-align: left; background: #f9fafb; border-bottom: 1.5px solid #f1f5f9;">
                        <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 60px;">#</th>
                        <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; width: 130px;">Mã SV</th>
                        <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px;">Họ tên sinh viên</th>
                        <th style="padding: 0.8rem 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.65rem; letter-spacing: 0.5px; text-align: center; width: 280px;">Trạng thái điểm danh</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                    @php 
                        $status = $existingAttendance[$student->id]->status ?? 'present';
                    @endphp
                    <tr style="border-bottom: 1px solid #f1f5f9; transition: all 0.2s;">
                        <td style="padding: 0.85rem 1rem; color: #94a3b8; font-weight: 600; font-size: 0.8rem;">{{ sprintf('%02d', $index + 1) }}</td>
                        <td style="padding: 0.85rem 1rem;">
                            <span style="font-weight: 700; color: var(--text-title); font-size: 0.8rem; font-family: monospace; letter-spacing: 0.5px;">{{ $student->student_code }}</span>
                        </td>
                        <td style="padding: 0.85rem 1rem;">
                            <div style="font-weight: 700; color: var(--text-title); font-size: 0.85rem;">{{ $student->name }}</div>
                        </td>
                        <td style="padding: 0.85rem 1rem;">
                            @if($hasAttendance)
                                <!-- View Mode -->
                                <div style="display: flex; justify-content: center;">
                                    @if($status == 'present')
                                        <span style="background: #ecfdf5; color: #059669; padding: 4px 12px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; border: 1px solid #d1fae5;">CÓ MẶT</span>
                                    @elseif($status == 'late')
                                        <span style="background: #fffbeb; color: #d97706; padding: 4px 12px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; border: 1px solid #fef3c7;">ĐI MUỘN</span>
                                    @else
                                        <span style="background: #fff1f2; color: #e11d48; padding: 4px 12px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; border: 1px solid #ffe4e6;">VẮNG MẶT</span>
                                    @endif
                                </div>
                            @else
                                <!-- Edit Mode -->
                                <div style="display: flex; justify-content: center; gap: 8px;">
                                    <label class="status-option">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="present" checked>
                                        <div class="radio-card present">Có mặt</div>
                                    </label>
                                    <label class="status-option">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="late">
                                        <div class="radio-card late">Muộn</div>
                                    </label>
                                    <label class="status-option">
                                        <input type="radio" name="attendance[{{ $student->id }}]" value="absent">
                                        <div class="radio-card absent">Vắng</div>
                                    </label>
                                </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @if(!$hasAttendance)
        <div style="display: flex; justify-content: center; padding-top: 1.5rem;">
            <button type="submit" class="btn-pro btn-green" style="padding: 10px 40px; font-size: 0.85rem; border-radius: 10px; font-weight: 700;">
                <i data-lucide="save" style="width: 18px;"></i>
                <span>Lưu & Đồng bộ điểm danh</span>
            </button>
        </div>
    @endif
</form>

<style>
    .status-option input { display: none; }
    .radio-card {
        padding: 5px 14px;
        background: white;
        border: 1px solid var(--border-light);
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.65rem;
        font-weight: 800;
        color: #64748b;
        transition: all 0.2s;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    .status-option input:checked + .radio-card.present { background: #10b981; border-color: transparent; color: white; }
    .status-option input:checked + .radio-card.late { background: #f59e0b; border-color: transparent; color: white; }
    .status-option input:checked + .radio-card.absent { background: #ef4444; border-color: transparent; color: white; }
</style>

<script>
    function markAll(status) {
        document.querySelectorAll(`input[type="radio"][value="${status}"]`).forEach(radio => {
            radio.checked = true;
        });
    }
</script>
@endsection
