@extends('layouts.app')

@section('title', 'Chỉnh sửa Cấu hình - ' . $subject->name)

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <a href="{{ route('classrooms.index', ['classroom_id' => $classroom->id]) }}" style="background: white; border: 1px solid var(--border-color); width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--text-muted); text-decoration: none; transition: all 0.2s;">
                <i data-lucide="arrow-left" style="width: 18px;"></i>
            </a>
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0;">Chỉnh sửa Cấu hình: {{ $subject->name }}</h3>
                <p style="margin: 2px 0 0 0; font-size: 13px; color: var(--text-muted);">Cập nhật ca học, giảng viên và lịch trình cho lớp <b>{{ $classroom->name }}</b></p>
            </div>
        </div>
    </div>

    <div class="card-glass" style="border-radius: 16px; overflow: hidden;">
        <form action="{{ route('classrooms.update_subject_configuration', [$classroom->id, $subject->id]) }}" method="POST" id="assign-form" style="padding: 2rem;">
            @csrf
            @method('PUT')
            <div style="display: grid; grid-template-columns: 320px 1fr; gap: 3rem;">
                <!-- Left Column: General Info -->
                <div style="display: grid; gap: 1.25rem;">
                    <h5 style="font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin: 0; padding-bottom: 8px; border-bottom: 1px solid var(--bg-main); letter-spacing: 0.05em;">Thông tin cốt lõi</h5>
                    
                    <div>
                        <label style="display: block; font-size: 10px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Môn học</label>
                        <div class="refined-input" style="background: var(--bg-main); color: var(--text-muted); cursor: not-allowed;">{{ $subject->name }} ({{ $subject->code }})</div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 10px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Giảng viên</label>
                        <select name="teacher_id" class="refined-select" required>
                            @foreach($allTeachers as $t)
                                <option value="{{ $t->id }}" {{ $t->id == $firstSchedule->teacher_id ? 'selected' : '' }}>{{ $t->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div>
                            <label style="display: block; font-size: 10px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Học kỳ</label>
                            <select name="semester_id" class="refined-select" required>
                                @foreach($semesters as $sem)
                                    <option value="{{ $sem->id }}" {{ $sem->id == $firstSchedule->semester_id ? 'selected' : '' }}>{{ $sem->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 10px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Năm học</label>
                            <select name="academic_year_id" class="refined-select" required>
                                @foreach($academicYears as $ay)
                                    <option value="{{ $ay->id }}" {{ $ay->id == $firstSchedule->academic_year_id ? 'selected' : '' }}>{{ $ay->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 10px; font-weight: 600; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Tổng số tiết học</label>
                        <input type="number" name="total_periods" id="total_periods" class="refined-input" value="{{ $firstSchedule->total_periods }}" required>
                    </div>

                    <!-- Row: Preview -->
                    <div style="padding: 1.25rem; background: var(--bg-main); border-radius: 12px; border: 1px solid var(--border-color);">
                        <div style="display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem;">
                            <span style="font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Lịch học dự kiến mơi</span>
                            <button type="button" onclick="updatePreview()" id="preview-btn" style="background: white; border: 1px solid var(--border-color); padding: 4px 12px; border-radius: 6px; font-size: 10px; font-weight: 600; cursor: pointer; color: var(--text-main); transition: all 0.2s;">
                                Cập nhật
                            </button>
                        </div>
                        <div id="preview-content" style="max-height: 200px; overflow-y: auto; font-size: 11px; color: var(--text-muted);">
                            <p style="margin: 0; font-style: italic; font-size: 11px; text-align: center; padding: 1rem 0;">Nhấn Cập nhật để xem lịch mới...</p>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Session Slots -->
                <div style="display: flex; flex-direction: column; gap: 1.5rem;">
                    <div style="display: flex; align-items: center; justify-content: space-between; padding-bottom: 10px; border-bottom: 1px solid var(--bg-main);">
                        <h5 style="font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin: 0; letter-spacing: 0.05em;">Cấu hình các Ca học (Buổi học)</h5>
                        <button type="button" onclick="addSessionSlot()" style="background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border-color); padding: 6px 14px; border-radius: 8px; font-size: 11px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s;">
                            <i data-lucide="plus" style="width: 14px;"></i> Thêm ca mới
                        </button>
                    </div>
                    <div id="sessions-container" style="display: grid; gap: 1.25rem; max-height: 600px; overflow-y: auto; padding-right: 8px;">
                        <!-- Session slots will be injected here -->
                    </div>
                </div>
            </div>

            <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end; gap: 1rem;">
                <a href="{{ route('classrooms.index', ['classroom_id' => $classroom->id]) }}" style="background: white; border: 1px solid var(--border-color); padding: 0.75rem 2rem; border-radius: 12px; font-weight: 600; font-size: 14px; color: var(--text-muted); text-decoration: none; display: flex; align-items: center; justify-content: center; transition: all 0.2s;">Hủy bỏ</a>
                <button type="submit" class="save-btn" style="padding: 0.75rem 2.5rem;">
                    <i data-lucide="check" style="width: 16px;"></i>
                    Lưu các thay đổi
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    :root {
        --brand-primary: #334155;
        --brand-accent: #6366f1;
        --bg-main: #f8fafc;
        --text-main: #334155;
        --text-muted: #64748b;
        --border-color: #e2e8f0;
    }

    .refined-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: white;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-main);
        transition: all 0.2s ease;
    }
    .refined-input:focus {
        border-color: var(--brand-accent);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.08);
        outline: none;
    }
    .refined-select {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: white;
        font-size: 14px;
        font-weight: 500;
        color: var(--text-main);
        appearance: none;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .refined-select:focus {
        border-color: var(--brand-accent);
        outline: none;
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.08);
    }
    .save-btn {
        background: var(--brand-primary);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .save-btn:hover {
        background: #1e293b;
        transform: translateY(-1px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
    }
    
    .card-glass {
        background: white;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
    }

    @keyframes slideIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    lucide.createIcons();

    let sessionCount = 0;

    // Load existing sessions
    window.onload = () => {
        const existingSessions = @json($schedules);
        if (existingSessions.length > 0) {
            existingSessions.forEach(s => {
                addSessionSlot(s);
            });
        } else {
            addSessionSlot();
        }
    };

    function addSessionSlot(data = null) {
        const container = document.getElementById('sessions-container');
        const id = sessionCount++;
        
        const day = data ? data.day_of_week : 1;
        const room = data ? data.room : 'A.101';
        const startP = data ? data.start_period : '';
        const endP = data ? data.end_period : '';
        const startT = data ? data.start_time : '07:30';
        const endT = data ? data.end_time : '11:30';

        const slotHtml = `
            <div id="session-slot-${id}" style="background: white; border: 1px solid var(--border-color); border-radius: 14px; padding: 1.25rem; position: relative; animation: slideIn 0.3s ease-out; box-shadow: 0 1px 3px rgba(0,0,0,0.02);">
                <div style="display: grid; grid-template-columns: 160px 1fr; gap: 1.25rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Thứ trong tuần</label>
                        <select name="sessions[${id}][day_of_week]" class="refined-select session-day" style="padding: 0.6rem 1rem;" onchange="updatePreview()">
                            <option value="1" ${day == 1 ? 'selected' : ''}>Thứ 2</option>
                            <option value="2" ${day == 2 ? 'selected' : ''}>Thứ 3</option>
                            <option value="3" ${day == 3 ? 'selected' : ''}>Thứ 4</option>
                            <option value="4" ${day == 4 ? 'selected' : ''}>Thứ 5</option>
                            <option value="5" ${day == 5 ? 'selected' : ''}>Thứ 6</option>
                            <option value="6" ${day == 6 ? 'selected' : ''}>Thứ 7</option>
                            <option value="0" ${day == 0 ? 'selected' : ''}>Chủ nhật</option>
                        </select>
                    </div>
                    <div>
                        <label style="display: block; font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Phòng học</label>
                        <input type="text" name="sessions[${id}][room]" class="refined-input" placeholder="Ví dụ: A.101" required value="${room}" style="padding: 0.6rem 1rem;">
                    </div>
                </div>
                <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem;">
                    <div>
                        <label style="display: block; font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Tiết học (Từ - Đến)</label>
                        <div style="display: flex; align-items: center; gap: 6px;">
                            <input type="number" name="sessions[${id}][start_period]" class="refined-input session-start" placeholder="BĐ" required style="padding: 0.6rem; text-align: center;" onchange="updatePreview()" value="${startP}">
                            <span style="color: var(--text-muted); font-weight: 700;">-</span>
                            <input type="number" name="sessions[${id}][end_period]" class="refined-input session-end" placeholder="KT" required style="padding: 0.6rem; text-align: center;" onchange="updatePreview()" value="${endP}">
                        </div>
                    </div>
                    <div>
                        <label style="display: block; font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Giờ bắt đầu</label>
                        <input type="time" name="sessions[${id}][start_time]" class="refined-input" required style="padding: 0.5rem 0.75rem;" value="${startT}">
                    </div>
                    <div>
                        <label style="display: block; font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px;">Giờ kết thúc</label>
                        <input type="time" name="sessions[${id}][end_time]" class="refined-input" required style="padding: 0.5rem 0.75rem;" value="${endT}">
                    </div>
                </div>
                <!-- ID > 0 logic needs to be based on DOM count to allow removing all but one -->
                <button type="button" class="remove-btn" onclick="removeSessionSlot(${id})" style="position: absolute; top: 12px; right: 12px; width: 24px; height: 24px; background: #fff1f2; color: #e11d48; border: 1px solid #ffe4e6; border-radius: 8px; display: flex; align-items: center; justify-content: center; cursor: pointer; transition: all 0.2s;">
                    <i data-lucide="minus" style="width: 14px;"></i>
                </button>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', slotHtml);
        updateRemoveButtons();
        lucide.createIcons();
    }

    function removeSessionSlot(id) {
        const slot = document.getElementById(`session-slot-${id}`);
        slot.remove();
        updateRemoveButtons();
        updatePreview();
    }

    function updateRemoveButtons() {
        const btns = document.querySelectorAll('.remove-btn');
        btns.forEach(btn => {
            btn.style.display = btns.length > 1 ? 'flex' : 'none';
        });
    }

    async function updatePreview() {
        const btn = document.getElementById('preview-btn');
        const content = document.getElementById('preview-content');
        const form = document.getElementById('assign-form');
        
        const semester_id = form.querySelector('select[name="semester_id"]').value;
        const total_periods = document.getElementById('total_periods').value;
        
        const sessionSlots = [];
        const slots = document.querySelectorAll('[id^="session-slot-"]');
        
        slots.forEach(slot => {
            const day = slot.querySelector('.session-day').value;
            const start = slot.querySelector('.session-start').value;
            const end = slot.querySelector('.session-end').value;
            
            if (day && start && end) {
                sessionSlots.push({ day_of_week: day, start_period: start, end_period: end });
            }
        });

        if (!semester_id || !total_periods || sessionSlots.length === 0) {
            content.innerHTML = '<p style="color: #64748b; margin:0; font-style: italic; font-size: 11px; text-align: center; padding: 1rem 0;">Vui lòng thiết lập buổi học...</p>';
            return;
        }

        btn.disabled = true;
        btn.innerHTML = '...';

        try {
            let query = `/classrooms/preview-schedule?semester_id=${semester_id}&total_periods=${total_periods}`;
            sessionSlots.forEach((s, i) => {
                query += `&sessions[${i}][day_of_week]=${s.day_of_week}&sessions[${i}][start_period]=${s.start_period}&sessions[${i}][end_period]=${s.end_period}`;
            });

            const response = await fetch(query);
            const data = await response.json();

            if (data.error) {
                content.innerHTML = `<p style="color: #ef4444; margin:0; font-size: 11px; padding: 0.5rem; text-align: center;">${data.error}</p>`;
            } else {
                let html = `<div style="display: grid; gap: 6px;">`;
                html += `<div style="padding: 10px; background: #f0fdf4; border-radius: 10px; border: 1px solid #dcfce7; margin-bottom: 8px;">
                            <p style="margin:0; font-weight: 600; color: #166534; display: flex; justify-content: space-between; font-size: 11px;">
                                <span>Tổng cộng mới:</span> <span>${data.totalSessions} buổi học</span>
                            </p>
                         </div>`;
                
                data.sessions.forEach(s => {
                    html += `
                        <div style="display: flex; justify-content: space-between; padding: 6px 10px; background: white; border-radius: 8px; border: 1px solid var(--border-color); animation: slideIn 0.2s ease-out;">
                            <span style="font-size: 11px; font-weight: 500;">B.${s.index}: <strong style="font-weight: 600;">${s.date}</strong></span>
                            <span style="color: var(--text-muted); font-size: 10px;">${s.day} (T.${s.periods})</span>
                        </div>
                    `;
                });
                html += `</div>`;
                content.innerHTML = html;
            }
        } catch (e) {
            content.innerHTML = '<p style="color: #ef4444; margin:0; text-align: center; padding: 1rem 0;">Lỗi kết nối.</p>';
        } finally {
            btn.disabled = false;
            btn.innerHTML = 'Cập nhật';
        }
    }
</script>
@endsection
