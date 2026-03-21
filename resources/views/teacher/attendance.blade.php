@php 
    $hasAttendance = $existingAttendance->isNotEmpty();
    $initialMode = $hasAttendance ? 'view' : 'edit';
@endphp

@extends('layouts.teacher')

@section('title', 'Ghi nhận điểm danh')

@section('context-navbar')
    <a href="{{ route('teacher.students', $schedule->id) }}" class="context-tab">Danh sách sinh viên</a>
    <a href="{{ route('teacher.attendance', $schedule->id) }}" class="context-tab active">Ghi nhận điểm danh</a>
    <a href="{{ route('teacher.grades', $schedule->id) }}" class="context-tab">Nhập điểm Giữa kỳ & Cuối kỳ</a>
@endsection

@section('content')
<div class="pro-card" style="margin-bottom: 1rem; padding: 0; overflow: hidden;">
    <!-- Header Section -->
    <div style="padding: 1rem 1.25rem; border-bottom: 1px solid #f1f5f9; background: white; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 1.1rem; font-weight: 800; color: var(--text-title); margin: 0;">{{ $subject->name }}</h2>
            <div style="font-size: 0.8rem; color: var(--text-muted); font-weight: 600; margin-top: 2px;">
                Lớp {{ $classroom->name }} <span style="margin: 0 4px; color: #cbd5e1;">|</span> {{ $subject->code }} <span style="margin: 0 4px; color: #cbd5e1;">|</span> NY {{ $schedule->academicYear->name ?? $classroom->academicYear->name }}
            </div>
        </div>
        <div>
            @if($hasAttendance)
                <div style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 16px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; display: flex; align-items: center; gap: 6px; text-transform: uppercase;">
                    <i data-lucide="lock" style="width: 14px;"></i>
                    Dữ liệu đã khóa
                </div>
            @else
                <div style="background: #f0fdf4; color: #166534; border: 1px solid #dcfce7; padding: 6px 16px; border-radius: 6px; font-size: 0.7rem; font-weight: 800; display: flex; align-items: center; gap: 6px; text-transform: uppercase;">
                    <i data-lucide="edit-3" style="width: 14px;"></i>
                    Chế độ ghi nhận
                </div>
            @endif
        </div>
    </div>

    <!-- Selection Bar -->
    <div style="padding: 1rem 1.25rem; background: #fbfcfe; border-bottom: 1px solid #f1f5f9;">
        <form action="{{ route('teacher.attendance', $schedule->id) }}" method="GET" id="date-form" style="display: flex; gap: 1rem; align-items: flex-end; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Ngày điểm danh</label>
                <input type="date" name="date" value="{{ $date->toDateString() }}" onchange="this.form.submit()" 
                       style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px; font-weight: 700; color: var(--text-title); outline: none; background: white;">
            </div>

            <div style="flex: 1; min-width: 200px;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Chọn ngày cũ</label>
                <select onchange="document.getElementsByName('date')[0].value = this.value; document.getElementById('date-form').submit();" 
                        style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px; font-weight: 600; color: #64748b; background: white;">
                    <option value="">-- Lịch sử điểm danh --</option>
                    @foreach($recordedDates as $rd)
                        <option value="{{ $rd }}" {{ $date->toDateString() == $rd ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($rd)->format('d/m/Y') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="padding-bottom: 2px;">
                @if(!$hasAttendance)
                    <button type="button" onclick="markAll('present')" class="btn-pro" style="padding: 8px 16px; font-size: 0.75rem; background: #f1f5f9; color: var(--text-title); border-color: #e2e8f0;">
                        Chọn tất cả có mặt
                    </button>
                @else
                    <button type="button" onclick="location.reload()" class="btn-pro" style="padding: 8px 16px; font-size: 0.75rem; background: #fff; color: var(--brand-primary);">
                        <i data-lucide="refresh-cw" style="width: 14px;"></i> Làm mới
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
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; font-size: 0.85rem;">
                <thead>
                    <tr style="text-align: left; background: #f8fafc; border-bottom: 1.5px solid #f1f5f9;">
                        <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; width: 50px;">STT</th>
                        <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; width: 120px;">Mã SV</th>
                        <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem;">Họ tên sinh viên</th>
                        <th style="padding: 10px 1rem; color: var(--text-muted); font-weight: 800; text-transform: uppercase; font-size: 0.7rem; text-align: center; width: 300px;">Trạng thái</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                    @php 
                        $status = $existingAttendance[$student->id]->status ?? 'present';
                    @endphp
                    <tr style="border-bottom: 1px solid #f8fafc; transition: background 0.1s;">
                        <td style="padding: 8px 1rem; color: var(--text-muted); font-weight: 700;">{{ sprintf('%02d', $index + 1) }}</td>
                        <td style="padding: 8px 1rem; font-weight: 800; color: var(--text-title); font-family: monospace;">{{ $student->student_code }}</td>
                        <td style="padding: 8px 1rem; font-weight: 700; color: var(--text-title);">{{ $student->name }}</td>
                        <td style="padding: 8px 1rem;">
                            @if($hasAttendance)
                                <!-- View Mode -->
                                <div style="display: flex; justify-content: center;">
                                    @if($status == 'present')
                                        <span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; border: 1px solid #bbf7d0;">CÓ MẶT</span>
                                    @elseif($status == 'late')
                                        <span style="background: #fef9c3; color: #854d0e; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; border: 1px solid #fef08a;">MUỘN</span>
                                    @else
                                        <span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 20px; font-size: 0.7rem; font-weight: 800; border: 1px solid #fecaca;">VẮNG</span>
                                    @endif
                                </div>
                            @else
                                <!-- Edit Mode -->
                                <div style="display: flex; justify-content: center; gap: 6px;">
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
            <button type="submit" class="btn-pro btn-green" style="padding: 12px 60px; font-size: 0.95rem; border-radius: 8px; box-shadow: 0 10px 20px -5px rgba(16, 185, 129, 0.3);">
                <i data-lucide="check-circle" style="width: 20px;"></i>
                <span>Xác nhận & Đồng bộ điểm chuyên cần</span>
            </button>
        </div>
    @endif
</form>

<style>
    .status-option input { display: none; }
    .radio-card {
        padding: 4px 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        cursor: pointer;
        font-size: 0.7rem;
        font-weight: 800;
        color: #64748b;
        transition: all 0.2s;
        text-transform: uppercase;
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
吐
吐
吐
吐
吐
