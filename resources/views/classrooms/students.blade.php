@extends('layouts.app')

@section('title', 'Điểm danh: ' . $subject->name)

@section('content')
<div style="max-width: 1100px; margin: 0 auto; padding-bottom: 3rem;">
    <!-- Breadcrumbs & Header -->
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
            <a href="{{ route('classrooms.index') }}" style="color: var(--text-muted); text-decoration: none;">Danh sách Lớp</a>
            <i data-lucide="chevron-right" style="width: 12px; color: var(--text-muted);"></i>
            <a href="{{ route('classrooms.index', ['classroom_id' => $classroom->id]) }}" style="color: var(--text-muted); text-decoration: none;">{{ $classroom->name }}</a>
            <i data-lucide="chevron-right" style="width: 12px; color: var(--text-muted);"></i>
            <span style="color: var(--brand-accent);">{{ $subject->name }}</span>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin: 0;">Quản lý Điểm danh</h2>
                <p style="margin: 4px 0 0 0; color: var(--text-muted); font-size: 14px;">Môn: <b>{{ $subject->name }}</b> ({{ $subject->code }}) | Lớp: <b>{{ $classroom->name }}</b></p>
            </div>
            <div style="display: flex; gap: 1rem;">
                <!-- Date Picker Form -->
                <form action="{{ route('classrooms.index') }}" method="GET" id="dateFilterForm" style="display: flex; align-items: center; gap: 0.5rem; background: white; padding: 0.5rem 1rem; border-radius: 12px; border: 1px solid var(--border-color); box-shadow: 0 1px 2px rgba(0,0,0,0.05);">
                    <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
                    <input type="hidden" name="subject_id" value="{{ $subject->id }}">
                    <label style="font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Ngày học:</label>
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()" class="refined-input" style="padding: 4px 8px; font-size: 13px; border: none; background: transparent; width: auto;">
                </form>
                
                @if($schedule)
                    <button onclick="document.getElementById('enrollModal').style.display='flex'" class="btn-icon" title="Ghi danh thêm">
                        <i data-lucide="user-plus" style="width: 16px;"></i>
                    </button>
                @endif
            </div>
        </div>
    </div>

    @if(session('success'))
        <div style="margin-bottom: 1.5rem; background: #ecfdf5; color: #065f46; padding: 1rem; border-radius: 12px; border: 1px solid #a7f3d0; display: flex; align-items: center; gap: 0.75rem; font-size: 14px; font-weight: 500;">
            <i data-lucide="check-circle" style="width: 18px;"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="card-glass" style="border-radius: 20px; overflow: hidden;">
        @if($schedule)
            <form action="{{ route('classrooms.save_attendance', $schedule->id) }}" method="POST">
                @csrf
                <input type="hidden" name="date" value="{{ $date }}">
                
                <div class="table-responsive">
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: var(--bg-main); border-bottom: 1px solid var(--border-color);">
                                <th style="padding: 1.25rem 1.5rem; text-align: left; font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; width: 140px;">Mã SV</th>
                                <th style="padding: 1.25rem 1.5rem; text-align: left; font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Sinh viên</th>
                                <th style="padding: 1.25rem 1.5rem; text-align: center; font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Điểm danh ({{ \Carbon\Carbon::parse($date)->format('d/m') }})</th>
                                <th style="padding: 1.25rem 1.5rem; text-align: left; font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; width: 250px;">Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($registrations as $reg)
                                @php 
                                    $student = $reg->student;
                                    $prevStatus = $attendanceData[$student->id] ?? null;
                                @endphp
                                <tr class="table-row {{ $prevStatus === 'absent' ? 'row-absent' : '' }}" style="border-bottom: 1px solid var(--border-color);">
                                    <td style="padding: 1.25rem 1.5rem; font-weight: 700; color: var(--brand-accent); font-family: monospace; font-size: 14px;">{{ $student->student_code }}</td>
                                    <td style="padding: 1.25rem 1.5rem;">
                                        <div style="font-weight: 600; color: var(--text-main); font-size: 14px;">{{ $student->name }}</div>
                                        <div style="font-size: 12px; color: var(--text-muted);">{{ $student->email }}</div>
                                    </td>
                                    <td style="padding: 1.25rem 1.5rem;">
                                        <div style="display: flex; justify-content: center; gap: 1.5rem;">
                                            <label class="attendance-label" title="Có mặt">
                                                <input type="radio" name="attendance[{{ $student->id }}]" value="present" class="hidden-radio" {{ ($prevStatus === 'present' || !$prevStatus) ? 'checked' : '' }}>
                                                <div class="radio-custom present">
                                                    <i data-lucide="check" style="width: 14px;"></i>
                                                    <span>Có mặt</span>
                                                </div>
                                            </label>
                                            <label class="attendance-label" title="Vắng mặt">
                                                <input type="radio" name="attendance[{{ $student->id }}]" value="absent" class="hidden-radio" {{ $prevStatus === 'absent' ? 'checked' : '' }}>
                                                <div class="radio-custom absent">
                                                    <i data-lucide="x" style="width: 14px;"></i>
                                                    <span>Vắng</span>
                                                </div>
                                            </label>
                                            <label class="attendance-label" title="Đi muộn">
                                                <input type="radio" name="attendance[{{ $student->id }}]" value="late" class="hidden-radio" {{ $prevStatus === 'late' ? 'checked' : '' }}>
                                                <div class="radio-custom late">
                                                    <i data-lucide="clock" style="width: 14px;"></i>
                                                    <span>Muộn</span>
                                                </div>
                                            </label>
                                        </div>
                                    </td>
                                    <td style="padding: 1.25rem 1.5rem;">
                                        <input type="text" name="notes[{{ $student->id }}]" placeholder="..." class="refined-input" style="font-size: 12px; height: 32px;">
                                    </td>
                                </tr>
                            @endforeach
                            @if($registrations->isEmpty())
                                <tr>
                                    <td colspan="4" style="padding: 5rem; text-align: center; color: var(--text-muted); font-style: italic; font-size: 14px;">Chưa có sinh viên nào được ghi danh vào môn học này.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>

                @if(!$registrations->isEmpty())
                    <div style="padding: 1.5rem; background: var(--bg-main); border-top: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                        <span style="font-size: 12px; color: var(--text-muted);">Mẹo: Điểm danh mặc định là <b>Có mặt</b>. Nhấn Lưu sau khi thay đổi.</span>
                        <button type="submit" class="save-btn" style="padding: 0.75rem 2.5rem; font-size: 14px; box-shadow: 0 4px 6px -1px rgba(99, 102, 241, 0.4);">
                            <i data-lucide="save" style="width: 16px;"></i>
                            Lưu Điểm Danh
                        </button>
                    </div>
                @endif
            </form>
        @else
            <div style="padding: 5rem; text-align: center;">
                <i data-lucide="alert-triangle" style="width: 48px; height: 48px; color: #f59e0b; margin-bottom: 1rem;"></i>
                <h4 style="color: var(--text-main); font-weight: 700;">Chưa cấu hình lịch học</h4>
                <p style="color: var(--text-muted); font-size: 14px;">Bạn cần cấu hình lịch học cho môn này trước khi có thể điểm danh.</p>
                <a href="{{ route('classrooms.show_assign_subject', $classroom->id) }}" class="save-btn" style="display: inline-flex; margin-top: 1rem;">Cấu hình ngay</a>
            </div>
        @endif
    </div>
</div>

<!-- Enrollment Modal (Existing, but styled to Slate) -->
@if($schedule)
<div id="enrollModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 1.5rem;">
    <div style="background: white; width: 100%; max-width: 450px; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
        <div style="padding: 1.5rem; background: var(--bg-main); border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
            <h3 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0;">Ghi danh Sinh viên</h3>
            <button onclick="document.getElementById('enrollModal').style.display='none'" style="background: none; border: none; padding: 4px; cursor: pointer; color: #94a3b8;">
                <i data-lucide="x" style="width: 20px;"></i>
            </button>
        </div>
        <form action="{{ route('classrooms.enroll_student', $schedule->id) }}" method="POST" style="padding: 1.5rem;">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Chọn sinh viên từ lớp {{ $classroom->name }}</label>
                <select name="student_id" class="refined-input" required>
                    <option value="">-- Chọn sinh viên --</option>
                    @foreach($availableStudents as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->student_code }})</option>
                    @endforeach
                </select>
                @if($availableStudents->isEmpty())
                    <p style="margin-top: 10px; font-size: 11px; color: #ef4444; font-weight: 600;">Tất cả sinh viên trong lớp đã được ghi danh môn này.</p>
                @endif
            </div>
            <div style="display: flex; gap: 0.75rem;">
                <button type="button" onclick="document.getElementById('enrollModal').style.display='none'" class="btn-icon" style="flex: 1; justify-content: center;">Hủy</button>
                <button type="submit" class="save-btn" style="flex: 1; justify-content: center;" {{ $availableStudents->isEmpty() ? 'disabled' : '' }}>Xác nhận Ghi danh</button>
            </div>
        </form>
    </div>
</div>
@endif

<style>
    :root {
        --brand-primary: #334155;
        --brand-accent: #6366f1;
        --bg-main: #f8fafc;
        --text-main: #334155;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    .card-glass {
        background: white;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .table-row:hover { background: #fbfcfe; }
    .row-absent { background: #fff1f2 !important; }

    .refined-input {
        width: 100%;
        padding: 0.65rem 1rem;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: white;
        transition: all 0.2s;
    }
    .refined-input:focus { border-color: var(--brand-accent); outline: none; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }

    .save-btn {
        background: var(--brand-accent);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 700;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .save-btn:hover { background: #4f46e5; transform: translateY(-1px); }
    .save-btn:disabled { background: #94a3b8; cursor: not-allowed; transform: none; box-shadow: none; }

    .btn-icon {
        background: white;
        border: 1px solid var(--border-color);
        padding: 8px;
        border-radius: 12px;
        color: var(--text-muted);
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
        display: flex;
        align-items: center;
    }
    .btn-icon:hover { border-color: var(--brand-accent); color: var(--brand-accent); background: #f5f3ff; }

    /* Custom Radio Styling */
    .attendance-label {
        cursor: pointer;
    }
    .hidden-radio {
        display: none;
    }
    .radio-custom {
        display: flex;
        align-items: center;
        gap: 6px;
        padding: 6px 14px;
        border-radius: 8px;
        border: 1px solid var(--border-color);
        font-size: 11px;
        font-weight: 700;
        color: var(--text-muted);
        transition: all 0.2s;
    }
    
    .hidden-radio:checked + .radio-custom.present { background: #f0fdf4; border-color: #22c55e; color: #15803d; }
    .hidden-radio:checked + .radio-custom.absent { background: #fef2f2; border-color: #ef4444; color: #b91c1c; }
    .hidden-radio:checked + .radio-custom.late { background: #fffbeb; border-color: #f59e0b; color: #b45309; }
</style>

<script>
    lucide.createIcons();
    
    // Auto-save visual feedback (optional)
    document.querySelectorAll('.hidden-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            const row = this.closest('.table-row');
            if (this.value === 'absent') {
                row.classList.add('row-absent');
            } else {
                row.classList.remove('row-absent');
            }
        });
    });
</script>
@endsection
