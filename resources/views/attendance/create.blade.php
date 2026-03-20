@php 
    $hasAttendance = $existingAttendance->isNotEmpty();
    $initialMode = $hasAttendance ? 'view' : 'edit';
@endphp

@extends('layouts.app')

@section('title', 'Ghi nhận Điểm danh')

@section('content')
<div class="card" style="padding: 0; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
    <!-- Header -->
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                <a href="{{ route('attendance.index', ['department_id' => $classroom->department_id, 'classroom_id' => $classroom->id]) }}" style="text-decoration: none; color: #94a3b8; display: flex; align-items: center;" title="Quay lại">
                    <i data-lucide="arrow-left" style="width: 16px;"></i>
                </a>
                <h3 style="font-size: 1.15rem; color: var(--text-title); margin: 0; font-weight: 700;">
                    Ghi nhận Điểm danh - Lớp {{ $classroom->name }} 
                    @if(isset($currentSessionNumber))
                        <span style="color: var(--brand-primary); margin-left: 8px;">(Buổi {{ $currentSessionNumber }}{{ isset($totalSessions) && $totalSessions > 0 ? ' / ' . $totalSessions : '' }})</span>
                    @endif
                </h3>
            </div>
            <p style="color: var(--text-body); font-size: var(--fs-xs);">Vui lòng chọn Niên khóa và Ngày điểm danh để nhập thông tin chuyên cần. Hệ thống sẽ tự động xác định học kỳ tương ứng.</p>
        </div>
        <div style="background: #eff6ff; border: 1px solid #dbeafe; padding: 0.4rem 0.75rem; border-radius: 6px; display: flex; align-items: center; gap: 6px;">
            <i data-lucide="book" style="width: 14px; color: var(--brand-primary);"></i>
            <span style="font-size: 11px; font-weight: 700; color: #1e40af;">Môn: {{ $subject->name }} ({{ $subject->code }})</span>
        </div>
    </div>

    <!-- Context Selection Bar -->
    <div style="padding: 1rem 1.5rem; border-bottom: 1px solid var(--border-color); background: #fbfcfe;">
        <form action="{{ route('attendance.create') }}" method="GET" id="context-form" style="display: flex; gap: 1.5rem; align-items: flex-end;">
            <input type="hidden" name="classroom_id" value="{{ $classroom->id }}">
            <input type="hidden" name="subject_id" value="{{ $subject->id }}">
            
            <div style="flex: 1;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Niên khóa</label>
                <select name="academic_year_id" onchange="this.form.submit()" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px; font-weight: 600; color: var(--text-title); background: #f5f7ff; border-color: var(--brand-primary);">
                    @foreach($academicYears as $year)
                        <option value="{{ $year->id }}" {{ $selectedYearId == $year->id ? 'selected' : '' }}>
                            Niên khóa {{ $year->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="flex: 1;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Ngày điểm danh</label>
                <div style="position: relative;">
                    <input type="date" name="date" id="date-input" value="{{ $date->toDateString() }}" onchange="this.form.submit()" 
                        style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px; font-weight: 600; color: var(--text-title); outline: none;">
                </div>
            </div>

            <div style="flex: 1;">
                <label style="display: block; font-size: 10px; font-weight: 800; color: #94a3b8; text-transform: uppercase; margin-bottom: 6px;">Dữ liệu đã có</label>
                <select onchange="document.getElementById('date-input').value = this.value; this.form.submit();" style="width: 100%; padding: 8px 12px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 13px; font-weight: 600; color: #64748b; background: #fff;">
                    <option value="">-- Chọn ngày cũ --</option>
                    @foreach($recordedDates as $rd)
                        <option value="{{ $rd }}" {{ $date->toDateString() == $rd ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::parse($rd)->format('d/m/Y') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('attendance.export', ['schedule_id' => $schedule_id]) }}" class="btn" title="Xuất báo cáo Excel"
                   style="display: inline-flex; align-items: center; justify-content: center; background: #f0fdf4; border: 1px solid #dcfce7; border-radius: 6px; padding: 8px 12px; font-size: 11px; font-weight: 700; color: #166534; text-decoration: none; gap: 6px;">
                    <i data-lucide="download" style="width: 14px;"></i>
                    Xuất Excel
                </a>
                <a href="{{ route('attendance.index', ['department_id' => $classroom->department_id, 'classroom_id' => $classroom->id]) }}" 
                   style="display: inline-flex; align-items: center; justify-content: center; background: none; border: 1px solid #e2e8f0; border-radius: 6px; padding: 8px 12px; font-size: 11px; font-weight: 600; color: #64748b; text-decoration: none;">
                    Đổi môn
                </a>
            </div>
        </form>
    </div>

    @if(!$selectedYearId)
        <!-- Empty State Guidance -->
        <div style="padding: 5rem 2rem; text-align: center;">
            <div style="width: 64px; height: 64px; background: #f8fafc; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto; border: 1px solid #e2e8f0;">
                <i data-lucide="layers" style="width: 32px; color: #cbd5e1;"></i>
            </div>
            <h4 style="font-size: 1.1rem; font-weight: 800; color: #64748b; margin-bottom: 0.5rem;">Cần bổ sung Niên khóa</h4>
            <p style="color: #94a3b8; font-size: 0.9rem; max-width: 400px; margin: 0 auto;">Vui lòng chọn **Niên khóa** ở thanh phía trên để lấy danh sách sinh viên tương ứng với môn học này.</p>
        </div>
    @elseif($students->isEmpty())
        <!-- No Students State -->
        <div style="padding: 5rem 2rem; text-align: center;">
            <div style="width: 64px; height: 64px; background: #fef2f2; border-radius: 20px; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem auto; border: 1px solid #fecaca;">
                <i data-lucide="user-x" style="width: 32px; color: #ef4444;"></i>
            </div>
            <h4 style="font-size: 1.1rem; font-weight: 800; color: #991b1b; margin-bottom: 0.5rem;">Chưa có sinh viên đăng ký</h4>
            <p style="color: #94a3b8; font-size: 0.9rem; max-width: 400px; margin: 0 auto;">Không tìm thấy sinh viên nào đăng ký môn **{{ $subject->name }}** trong học kỳ này tại lớp **{{ $classroom->name }}**.</p>
        </div>
    @else
        <!-- Attendance Table -->
        <form action="{{ route('attendance.store') }}" method="POST" id="attendance-form">
            @csrf
            <input type="hidden" name="schedule_id" value="{{ $schedule_id }}">
            <input type="hidden" name="attendance_date" value="{{ $date->toDateString() }}">
            <input type="hidden" name="session_number" value="{{ $currentSessionNumber ?? 1 }}">

            <div style="padding: 1rem 1.5rem; background: #fff; display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid #f1f5f9;">
                <div style="font-size: 12px; font-weight: 700; color: #64748b;">
                    DANH SÁCH SINH VIÊN ({{ $students->count() }})
                </div>
                <div style="display: flex; gap: 0.5rem; align-items: center;">
                    @php 
                        // Moved to top for global accessibility
                    @endphp
                    
                    @if($hasAttendance)
                        <div style="background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; padding: 6px 16px; border-radius: 6px; font-size: 11px; font-weight: 800; display: flex; align-items: center; gap: 6px;">
                            <i data-lucide="lock" style="width: 14px;"></i>
                            DỮ LIỆU ĐÃ KHÓA
                        </div>
                    @else
                        <div id="edit-controls" style="display: flex; gap: 8px;">
                            <button type="button" onclick="markAll('present')" style="padding: 6px 12px; border-radius: 6px; border: 1px solid #dcfce7; background: #f0fdf4; color: #166534; font-size: 11px; font-weight: 700; cursor: pointer;">Tất cả Có mặt</button>
                            <button type="submit" id="submit-btn" style="padding: 6px 16px; border-radius: 6px; border: none; background: var(--brand-primary); color: white; font-size: 11px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 10px rgba(79, 70, 229, 0.2); display: flex; align-items: center; gap: 8px;">
                                <i data-lucide="save" style="width: 14px;"></i>
                                <span id="btn-text">Lưu điểm danh</span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>

            <div class="table-responsive">
                <table class="data-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f8fafc;">
                            <th style="padding: 0.75rem 1rem; text-align: left; font-size: 10px; color: #94a3b8; text-transform: uppercase; width: 50px;">STT</th>
                            <th style="padding: 0.75rem 1rem; text-align: left; font-size: 10px; color: #94a3b8; text-transform: uppercase; width: 120px;">Mã SV</th>
                            <th style="padding: 0.75rem 1rem; text-align: left; font-size: 10px; color: #94a3b8; text-transform: uppercase;">Họ và Tên</th>
                            <th style="padding: 0.75rem 1rem; text-align: center; font-size: 10px; color: #94a3b8; text-transform: uppercase; width: 280px;">Trạng thái</th>
                            <th style="padding: 0.75rem 1rem; text-align: center; font-size: 10px; color: #94a3b8; text-transform: uppercase; width: 100px;">Điểm (10)</th>
                            <th style="padding: 0.75rem 1rem; text-align: left; font-size: 10px; color: #94a3b8; text-transform: uppercase;">Ghi chú</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($students as $index => $student)
                        @php
                            $att = $existingAttendance[$student->id] ?? null;
                            $status = $att ? $att->status : 'present';
                        @endphp
                        <tr class="table-row" style="border-bottom: 1px solid #f1f5f9;">
                            <td style="padding: 0.85rem 1rem; font-family: monospace; font-size: 12px; color: #94a3b8;">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</td>
                            <td style="padding: 0.85rem 1rem; font-weight: 700; color: var(--brand-primary); font-family: monospace; font-size: 13px;">{{ $student->student_code }}</td>
                            <td style="padding: 0.85rem 1rem; font-weight: 600; color: var(--text-title); font-size: 13px;">{{ $student->name }}</td>
                            <td style="padding: 0.85rem 1rem; text-align: center;">
                                <input type="hidden" name="attendance[{{ $index }}][student_id]" value="{{ $student->id }}">
                                
                                <!-- View Mode Status -->
                                <div class="mode-view" style="display: {{ $initialMode == 'view' ? 'block' : 'none' }};">
                                    @if($status == 'present')
                                        <span style="background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; border: 1px solid #bbf7d0;">Có mặt</span>
                                    @elseif($status == 'late')
                                        <span style="background: #fef9c3; color: #854d0e; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; border: 1px solid #fef08a;">Muộn</span>
                                    @else
                                        <span style="background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 700; border: 1px solid #fecaca;">Vắng</span>
                                    @endif
                                </div>

                                <!-- Edit Mode Status -->
                                <div class="mode-edit" style="display: {{ $initialMode == 'edit' ? 'inline-flex' : 'none' }}; background: #f1f5f9; padding: 4px; border-radius: 8px; gap: 4px; border: 1px solid #e2e8f0;">
                                    <label style="cursor: pointer; margin: 0;">
                                        <input type="radio" name="attendance[{{ $index }}][status]" value="present" {{ $status == 'present' ? 'checked' : '' }} style="display: none;" class="att-radio">
                                        <div class="att-btn present" data-status="present">Có mặt</div>
                                    </label>
                                    <label style="cursor: pointer; margin: 0;">
                                        <input type="radio" name="attendance[{{ $index }}][status]" value="late" {{ $status == 'late' ? 'checked' : '' }} style="display: none;" class="att-radio">
                                        <div class="att-btn late" data-status="late">Muộn</div>
                                    </label>
                                    <label style="cursor: pointer; margin: 0;">
                                        <input type="radio" name="attendance[{{ $index }}][status]" value="absent" {{ $status == 'absent' ? 'checked' : '' }} style="display: none;" class="att-radio">
                                        <div class="att-btn absent" data-status="absent">Vắng</div>
                                    </label>
                                </div>
                            </td>
                            <td style="padding: 0.85rem 1rem; text-align: center;">
                                @php
                                    $hist = $historicalStats[$student->id] ?? (object)['total_absent' => 0, 'total_late' => 0];
                                    $histAbs = (int)($hist->total_absent ?? 0);
                                    $histLate = (int)($hist->total_late ?? 0);
                                @endphp
                                <div class="student-score-container" 
                                     data-student-id="{{ $student->id }}"
                                     data-hist-abs="{{ $histAbs }}"
                                     data-hist-late="{{ $histLate }}">
                                    <span class="score-value" style="font-weight: 800; font-size: 14px; color: var(--brand-primary);">10.0</span>
                                    <div style="font-size: 9px; color: #94a3b8; margin-top: 2px;">
                                        V:<span class="abs-count">{{ $histAbs }}</span> | M:<span class="late-count">{{ $histLate }}</span>
                                    </div>
                                </div>
                            </td>
                            <td style="padding: 0.85rem 1rem;">
                                <div class="mode-view" style="display: {{ $initialMode == 'view' ? 'block' : 'none' }}; color: #64748b; font-size: 12px; font-style: italic;">
                                    {{ $att && $att->notes ? $att->notes : '...' }}
                                </div>
                                <div class="mode-edit" style="display: {{ $initialMode == 'edit' ? 'block' : 'none' }};">
                                    <input type="text" name="attendance[{{ $index }}][notes]" value="{{ $att ? $att->notes : '' }}" placeholder="..." 
                                        style="width: 100%; padding: 6px 10px; border: 1px solid #e2e8f0; border-radius: 6px; font-size: 12px; background: #f8fafc; outline: none; transition: all 0.2s;">
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    @endif
</div>

<style>
    .att-btn {
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 11px;
        font-weight: 700;
        color: #64748b;
        transition: all 0.2s;
    }
    .att-radio:checked + .present { background: #22c55e; color: white; box-shadow: 0 4px 10px rgba(34, 197, 94, 0.2); }
    .att-radio:checked + .late { background: #eab308; color: white; box-shadow: 0 4px 10px rgba(234, 179, 8, 0.2); }
    .att-radio:checked + .absent { background: #ef4444; color: white; box-shadow: 0 4px 10px rgba(239, 68, 68, 0.2); }
    
    .table-row:hover { background: #fbfcfe; }
    .table-row input:focus { border-color: var(--brand-primary) !important; background: white !important; box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1); }
</style>

<script>
    lucide.createIcons();
    
    // Attendance Rules from server
    const attendanceRule = {
        max_absent: {{ $attendanceRule->max_absent ?? 3 }},
        max_late: {{ $attendanceRule->max_late ?? 1 }},
        absent_deduction: {{ $attendanceRule->absent_deduction ?? 1.0 }},
        late_deduction: {{ $attendanceRule->late_deduction ?? 0.5 }}
    };

    let currentMode = '{{ $initialMode }}';

    function setMode(mode) {
        currentMode = mode;
        const viewEls = document.querySelectorAll('.mode-view');
        const editEls = document.querySelectorAll('.mode-edit');
        const viewControls = document.getElementById('view-controls');
        const editControls = document.getElementById('edit-controls');

        if (mode === 'view') {
            viewEls.forEach(el => el.style.display = 'block');
            editEls.forEach(el => el.style.display = 'none');
            viewControls.style.display = 'flex';
            editControls.style.display = 'none';
        } else {
            viewEls.forEach(el => el.style.display = 'none');
            editEls.forEach(el => {
                if (el.tagName === 'DIV' && el.classList.contains('mode-edit')) {
                    el.style.display = el.querySelector('.att-radio') ? 'inline-flex' : 'block';
                } else {
                    el.style.display = 'block';
                }
            });
            viewControls.style.display = 'none';
            editControls.style.display = 'flex';
        }
    }

    function markAll(status) {
        document.querySelectorAll(`.att-radio[value="${status}"]`).forEach(radio => {
            radio.checked = true;
        });
        updateAllScores();
    }

    function calculateScore(histAbs, histLate, currentStatus) {
        let score = 10;
        let abs = parseInt(histAbs);
        let late = parseInt(histLate);

        if (currentStatus === 'absent') abs += 1;
        if (currentStatus === 'late') late += 1;

        score = score - (abs * attendanceRule.absent_deduction) - (late * attendanceRule.late_deduction);
        return {
            value: Math.max(0, score).toFixed(1),
            totalAbs: abs,
            totalLate: late
        };
    }

    function updateScore(row) {
        const container = row.querySelector('.student-score-container');
        if (!container) return;

        const histAbs = container.dataset.histAbs;
        const histLate = container.dataset.histLate;
        const currentStatus = row.querySelector('.att-radio:checked').value;

        const scoreObj = calculateScore(histAbs, histLate, currentStatus);
        const scoreValue = container.querySelector('.score-value');
        
        scoreValue.textContent = scoreObj.value;
        
        // Threshold check & Color coding
        if (scoreObj.totalAbs > attendanceRule.max_absent) {
            scoreValue.style.color = '#7f1d1d'; // Dark red for failure
            scoreValue.title = `Vượt quá số buổi vắng cho phép (${attendanceRule.max_absent})`;
        } else if (scoreObj.value <= 5) {
            scoreValue.style.color = '#ef4444';
        } else if (scoreObj.value <= 7) {
            scoreValue.style.color = '#eab308';
        } else {
            scoreValue.style.color = 'var(--brand-primary)';
        }
    }

    function updateAllScores() {
        document.querySelectorAll('.table-row').forEach(updateScore);
    }

    document.querySelectorAll('.att-radio').forEach(radio => {
        radio.addEventListener('change', function() {
            updateScore(this.closest('.table-row'));
        });
    });

    // Initial calculation
    window.addEventListener('DOMContentLoaded', updateAllScores);

    // AJAX Form Submission
    document.getElementById('attendance-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = this;
        const btn = document.getElementById('submit-btn');
        const btnText = document.getElementById('btn-text');
        const originalText = btnText.textContent;
        
        // Loading state
        btn.disabled = true;
        btn.style.opacity = '0.7';
        btnText.textContent = 'Đang lưu...';
        
        const formData = new FormData(form);
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast(data.message, 'success');
                // Refresh badges and go to view mode
                setTimeout(() => location.reload(), 1000); // Simple reload for now to refresh badges and state
            } else {
                showToast(data.error || 'Có lỗi xảy ra khi lưu điểm danh.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Không thể kết nối đến máy chủ.', 'error');
        })
        .finally(() => {
            btn.disabled = false;
            btn.style.opacity = '1';
            btnText.textContent = originalText;
        });
    });

    function showToast(message, type) {
        const toast = document.createElement('div');
        toast.style.position = 'fixed';
        toast.style.bottom = '2rem';
        toast.style.right = '2rem';
        toast.style.background = type === 'success' ? '#22c55e' : '#ef4444';
        toast.style.color = 'white';
        toast.style.padding = '0.75rem 1.5rem';
        toast.style.borderRadius = '8px';
        toast.style.boxShadow = '0 10px 15px -3px rgba(0,0,0,0.1)';
        toast.style.fontWeight = '700';
        toast.style.fontSize = '14px';
        toast.style.zIndex = '9999';
        toast.style.display = 'flex';
        toast.style.alignItems = 'center';
        toast.style.gap = '8px';
        toast.style.animation = 'slideUp 0.3s ease-out';
        
        const iconName = type === 'success' ? 'check-circle' : 'alert-circle';
        toast.innerHTML = `<i data-lucide="${iconName}" style="width: 18px;"></i> ${message}`;
        
        document.body.appendChild(toast);
        lucide.createIcons();
        
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.5s ease-out';
            setTimeout(() => toast.remove(), 500);
        }, 3000);
    }
</script>

<style>
@keyframes slideUp {
    from { transform: translateY(100%); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}
</style>
@endsection
