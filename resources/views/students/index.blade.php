@extends('layouts.app')

@section('title', 'Quản lý Sinh viên')

@section('content')
<div style="max-width: 1200px; margin: 0 auto; padding-bottom: 3rem;">
    <!-- Breadcrumbs & Header -->
    <div style="margin-bottom: 2rem;">
        <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 1rem; font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">
            <a href="{{ route('students.index') }}" style="color: {{ isset($department) || isset($classroom) ? 'var(--text-muted)' : 'var(--brand-accent)' }}; text-decoration: none;">Tất cả Khoa</a>
            @if(isset($department))
                <i data-lucide="chevron-right" style="width: 12px; color: var(--text-muted);"></i>
                <a href="{{ route('students.index', ['department_id' => $department->id]) }}" style="color: {{ isset($classroom) ? 'var(--text-muted)' : 'var(--brand-accent)' }}; text-decoration: none;">{{ $department->name }}</a>
            @endif
            @if(isset($classroom))
                @if(!isset($department))
                    <i data-lucide="chevron-right" style="width: 12px; color: var(--text-muted);"></i>
                    <a href="{{ route('students.index', ['department_id' => $classroom->department_id]) }}" style="color: var(--text-muted); text-decoration: none;">{{ $classroom->department->name }}</a>
                @endif
                <i data-lucide="chevron-right" style="width: 12px; color: var(--text-muted);"></i>
                <span style="color: var(--brand-accent);">{{ $classroom->name }}</span>
            @endif
        </div>

        <div style="display: flex; justify-content: space-between; align-items: flex-end;">
            <div>
                <h2 style="font-size: 1.5rem; font-weight: 800; color: var(--text-main); margin: 0;">
                    @if(isset($classroom))
                        Danh sách Sinh viên: {{ $classroom->name }}
                    @elseif(isset($department))
                        Lớp học thuộc Khoa: {{ $department->name }}
                    @else
                        Hệ thống Quản lý Sinh viên
                    @endif
                </h2>
                <p style="margin: 4px 0 0 0; color: var(--text-muted); font-size: 14px;">
                    @if(isset($classroom))
                        Quản lý hồ sơ và đăng ký môn học của sinh viên trong lớp.
                    @elseif(isset($department))
                        Chọn lớp học để xem danh sách sinh viên chi tiết.
                    @else
                        Phân cấp theo Khoa và Lớp để quản lý sinh viên hiệu quả hơn.
                    @endif
                </p>
            </div>
            @if(isset($classroom))
                <a href="{{ route('students.create', ['classroom_id' => $classroom->id]) }}" class="save-btn" style="text-decoration: none; padding: 0.75rem 1.5rem;">
                    <i data-lucide="user-plus" style="width: 16px;"></i>
                    Thêm Sinh viên
                </a>
            @endif
        </div>
    </div>

    @if(!isset($department) && !isset($classroom))
        <!-- State 0: Departments Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 1.5rem;">
            @foreach($departments as $dept)
                <a href="{{ route('students.index', ['department_id' => $dept->id]) }}" class="nav-card" style="text-decoration: none;">
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 2rem;">
                        <div style="width: 48px; height: 48px; background: #f5f3ff; border-radius: 14px; display: flex; align-items: center; justify-content: center; border: 1px solid #ddd6fe;">
                            <i data-lucide="building-2" style="width: 24px; color: var(--brand-accent);"></i>
                        </div>
                        <span style="font-size: 10px; font-weight: 700; color: var(--text-muted); background: var(--bg-main); padding: 4px 10px; border-radius: 20px; border: 1px solid var(--border-color);">
                            {{ $dept->classrooms_count }} Lớp học
                        </span>
                    </div>
                    <h4 style="font-size: 1.15rem; font-weight: 700; color: var(--text-main); margin: 0 0 0.5rem 0;">{{ $dept->name }}</h4>
                    <p style="font-size: 13px; color: var(--text-muted); line-height: 1.5; margin: 0;">Xem danh sách các lớp học và sinh viên thuộc khoa này.</p>
                    <div style="margin-top: 1.5rem; display: flex; align-items: center; gap: 6px; font-size: 11px; font-weight: 700; color: var(--brand-accent);">
                        TRUY CẬP <i data-lucide="arrow-right" style="width: 12px;"></i>
                    </div>
                </a>
            @endforeach
        </div>

    @elseif(isset($department) && !isset($classroom))
        <!-- State 1: Classrooms Grid -->
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.5rem;">
            @foreach($classrooms as $cls)
                <a href="{{ route('students.index', ['classroom_id' => $cls->id]) }}" class="nav-card" style="text-decoration: none;">
                    <div style="display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1.5rem;">
                        <div style="width: 40px; height: 40px; background: #f0fdf4; border-radius: 12px; display: flex; align-items: center; justify-content: center; border: 1px solid #dcfce7;">
                            <i data-lucide="graduation-cap" style="width: 20px; color: #16a34a;"></i>
                        </div>
                        <span style="font-family: monospace; font-size: 11px; font-weight: 700; color: var(--brand-accent); background: #f5f3ff; padding: 4px 10px; border-radius: 8px; border: 1px solid #ddd6fe;">
                            {{ $cls->code }}
                        </span>
                    </div>
                    <h4 style="font-size: 1.1rem; font-weight: 700; color: var(--text-main); margin: 0 0 0.25rem 0;">{{ $cls->name }}</h4>
                    <p style="font-size: 12px; color: var(--text-muted); margin: 0;">Sĩ số: <b>{{ $cls->students_count }} sinh viên</b></p>
                    <div style="margin-top: 1.25rem; height: 1px; background: #f1f5f9;"></div>
                    <div style="margin-top: 1rem; display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Xem danh sách</span>
                        <i data-lucide="chevron-right" style="width: 14px; color: var(--text-muted);"></i>
                    </div>
                </a>
            @endforeach
        </div>

    @else
        <!-- State 2: Students Table -->
        <div class="card-glass" style="border-radius: 16px; overflow: hidden;">
            <div style="padding: 1rem 1.5rem; background: var(--bg-main); border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                <div style="display: flex; gap: 1rem; flex: 1; max-width: 600px;">
                    <div style="position: relative; flex: 1;">
                        <i data-lucide="search" style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 14px; color: var(--text-muted);"></i>
                        <input type="text" id="studentSearch" placeholder="Tìm theo tên hoặc mã sinh viên..." class="refined-input" style="padding-left: 2.5rem; font-size: 13px;">
                    </div>
                </div>
                <div style="font-size: 12px; font-weight: 600; color: var(--text-muted);">
                    Hiển thị <b>{{ $students->count() }}</b> sinh viên
                </div>
            </div>
            
            <div class="table-responsive">
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: var(--bg-main); border-bottom: 1px solid var(--border-color);">
                            <th style="padding: 1rem 1.5rem; text-align: left; font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; width: 140px;">Mã SV</th>
                            <th style="padding: 1rem 1.5rem; text-align: left; font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Họ và Tên</th>
                            <th style="padding: 1rem 1.5rem; text-align: left; font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Đăng ký Môn học</th>
                            <th style="padding: 1rem 1.5rem; text-align: right; font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em; width: 120px;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="student-table-body">
                        @foreach($students as $student)
                        <tr class="table-row" style="border-bottom: 1px solid var(--border-color);">
                            <td style="padding: 1.25rem 1.5rem; font-weight: 700; color: var(--brand-accent); font-family: monospace; font-size: 14px;">{{ $student->student_code }}</td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="font-weight: 600; color: var(--text-main); font-size: 14px;">{{ $student->name }}</div>
                                <div style="font-size: 12px; color: var(--text-muted);">{{ $student->email }}</div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem;">
                                <div style="display: flex; flex-wrap: wrap; gap: 4px;">
                                    @forelse($student->registrations->unique('subject_id') as $reg)
                                        <span style="font-size: 10px; font-weight: 700; color: var(--brand-primary); background: #f1f5f9; padding: 2px 8px; border-radius: 6px; border: 1px solid #e2e8f0;" title="{{ $reg->subject->name }}">
                                            {{ $reg->subject->code }}
                                        </span>
                                    @empty
                                        <span style="font-size: 11px; color: var(--text-muted); font-style: italic;">Chưa có môn học</span>
                                    @endforelse
                                </div>
                            </td>
                            <td style="padding: 1.25rem 1.5rem; text-align: right;">
                                <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                    <button onclick="showStudentDetails({{ json_encode($student) }})" class="btn-icon" title="Chi tiết môn học">
                                        <i data-lucide="calendar" style="width: 14px;"></i>
                                    </button>
                                    <a href="{{ route('students.edit', $student->id) }}" class="btn-icon" title="Sửa">
                                        <i data-lucide="edit-3" style="width: 14px;"></i>
                                    </a>
                                    <form action="{{ route('students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Xóa sinh viên này?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn-icon" style="color: #ef4444;" title="Xóa">
                                            <i data-lucide="trash-2" style="width: 14px;"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                        @if($students->isEmpty())
                        <tr>
                            <td colspan="4" style="padding: 4rem; text-align: center; color: var(--text-muted); font-style: italic; font-size: 14px;">Lớp học này hiện chưa có sinh viên.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>

<!-- Details Modal -->
<div id="detailsModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.4); backdrop-filter: blur(4px); z-index: 1000; align-items: center; justify-content: center; padding: 1.5rem;">
    <div style="background: white; width: 100%; max-width: 900px; border-radius: 20px; overflow: hidden; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
        <div style="padding: 1.5rem; background: var(--bg-main); border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
            <div>
                <h4 id="modalStudentName" style="font-size: 1.15rem; font-weight: 700; color: var(--text-main); margin: 0;">Chi tiết Đăng ký</h4>
                <p id="modalStudentCode" style="margin: 2px 0 0 0; font-size: 12px; font-weight: 700; color: var(--brand-accent); font-family: monospace;"></p>
            </div>
            <button onclick="document.getElementById('detailsModal').style.display='none'" style="background: none; border: none; padding: 8px; cursor: pointer; color: #94a3b8;">
                <i data-lucide="x" style="width: 24px;"></i>
            </button>
        </div>
        
        <!-- Filter Block -->
        <div style="padding: 1rem 1.5rem; background: #fff; border-bottom: 1px solid var(--border-color); display: flex; gap: 1rem; align-items: center;">
            <div style="flex: 1;">
                <label style="display: block; font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px;">Lọc theo Năm học</label>
                <select id="modalFilterYear" class="refined-select" style="padding: 0.5rem 0.75rem; font-size: 12px;" onchange="renderFilteredRegistrations()">
                    <option value="">Tất cả Năm học</option>
                    @foreach($academicYears ?? [] as $ay)
                        <option value="{{ $ay->id }}">{{ $ay->name }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; margin-bottom: 4px;">Lọc theo Học kỳ</label>
                <select id="modalFilterSemester" class="refined-select" style="padding: 0.5rem 0.75rem; font-size: 12px;" onchange="renderFilteredRegistrations()">
                    <option value="">Tất cả Học kỳ</option>
                    @foreach($semesters ?? [] as $sem)
                        <option value="{{ $sem->id }}">{{ $sem->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div style="padding: 1.5rem; max-height: 55vh; overflow-y: auto;">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--border-color); background: var(--bg-main);">
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; width: 250px;">Môn học & Kỳ</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Lịch học & Phòng</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: 9px; font-weight: 700; color: var(--text-muted); text-transform: uppercase;">Giảng viên</th>
                    </tr>
                </thead>
                <tbody id="modalRegistrationsTable"></tbody>
            </table>
        </div>
        
        <div style="padding: 1.25rem 1.5rem; background: var(--bg-main); border-top: 1px solid var(--border-color); display: flex; justify-content: flex-end;">
            <button onclick="document.getElementById('detailsModal').style.display='none'" class="save-btn" style="padding: 0.6rem 2rem;">Đóng</button>
        </div>
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

    .nav-card {
        background: white;
        border: 1px solid var(--border-color);
        border-radius: 20px;
        padding: 1.75rem;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        display: block;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }
    .nav-card:hover {
        transform: translateY(-4px);
        border-color: var(--brand-accent);
        box-shadow: 0 12px 20px -5px rgba(99, 102, 241, 0.1);
    }

    .card-glass {
        background: white;
        border: 1px solid var(--border-color);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
    }

    .table-row:hover { background: #fbfcfe; }

    .refined-input {
        width: 100%;
        padding: 0.65rem 1rem;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: white;
        transition: all 0.2s;
    }
    .refined-input:focus { border-color: var(--brand-accent); outline: none; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1); }

    .refined-select {
        width: 100%;
        padding: 0.65rem 1rem;
        border-radius: 10px;
        border: 1px solid var(--border-color);
        background: white;
        transition: all 0.2s;
        appearance: none;
        cursor: pointer;
    }

    .save-btn {
        background: var(--brand-primary);
        color: white;
        border: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 13px;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        cursor: pointer;
        transition: all 0.2s;
    }
    .save-btn:hover { background: #1e293b; transform: translateY(-1px); }

    .btn-icon {
        background: white;
        border: 1px solid var(--border-color);
        padding: 8px;
        border-radius: 10px;
        color: var(--text-muted);
        transition: all 0.2s;
        cursor: pointer;
        text-decoration: none;
        display: flex;
        align-items: center;
    }
    .btn-icon:hover { border-color: var(--brand-accent); color: var(--brand-accent); background: #f5f3ff; }
</style>

<script>
    lucide.createIcons();

    // Student Search Logic
    const searchInput = document.getElementById('studentSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function(e) {
            const term = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('.table-row');
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(term) ? '' : 'none';
            });
        });
    }

    // Modal Details Logic
    const days = ['Chủ nhật', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7'];
    let currentStudent = null;

    function showStudentDetails(student) {
        currentStudent = student;
        document.getElementById('modalStudentName').textContent = student.name;
        document.getElementById('modalStudentCode').textContent = student.student_code;
        
        // Reset filters
        document.getElementById('modalFilterYear').value = '';
        document.getElementById('modalFilterSemester').value = '';
        
        renderFilteredRegistrations();
        
        document.getElementById('detailsModal').style.display = 'flex';
        lucide.createIcons();
    }

    function renderFilteredRegistrations() {
        const yearFilter = document.getElementById('modalFilterYear').value;
        const semFilter = document.getElementById('modalFilterSemester').value;
        const tableBody = document.getElementById('modalRegistrationsTable');
        tableBody.innerHTML = '';

        if (!currentStudent || currentStudent.registrations.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="3" style="padding: 3rem; text-align: center; color: var(--text-muted); font-size: 13px; font-style: italic;">Chưa đăng ký môn học nào.</td></tr>`;
            return;
        }

        const filtered = currentStudent.registrations.filter(reg => {
            const matchesYear = !yearFilter || (reg.schedule && reg.schedule.academic_year_id == yearFilter);
            const matchesSem = !semFilter || reg.semester_id == semFilter;
            return matchesYear && matchesSem;
        });

        if (filtered.length === 0) {
            tableBody.innerHTML = `<tr><td colspan="3" style="padding: 3rem; text-align: center; color: var(--text-muted); font-size: 13px; font-style: italic;">Không tìm thấy đăng ký nào phù hợp với bộ lọc.</td></tr>`;
            return;
        }

        // Group by subject and semester to aggregate sessions
        const groups = {};
        filtered.forEach(reg => {
            const key = `${reg.subject_id}_${reg.semester_id}`;
            if (!groups[key]) {
                groups[key] = {
                    subject: reg.schedule.subject,
                    teacher: reg.schedule.teacher,
                    semester: reg.schedule.semester,
                    academicYear: reg.schedule.academic_year,
                    sessions: []
                };
            }
            groups[key].sessions.push(reg.schedule);
        });

        Object.values(groups).forEach(group => {
            const tr = document.createElement('tr');
            tr.style.borderBottom = '1px solid var(--border-color)';
            
            const sessions = group.sessions.map(s => `
                <div style="margin-bottom: 6px; font-size: 11px; display: flex; align-items: center; gap: 8px;">
                    <span style="font-weight: 700; min-width: 50px; color: var(--text-main);">${days[s.day_of_week]}</span>
                    <span style="color: var(--text-muted);">T.${s.start_period}-${s.end_period}</span>
                    <span style="background: #fefce8; color: #854d0e; font-size: 10px; font-weight: 700; padding: 1px 6px; border-radius: 4px; border: 1px solid #fef08a;">${s.room}</span>
                </div>
            `).join('');

            const yearLabel = group.academicYear ? `<div style="font-size: 10px; color: var(--text-muted); margin-top: 1px;">Năm: ${group.academicYear.name}</div>` : '';

            tr.innerHTML = `
                <td style="padding: 1rem; vertical-align: top;">
                    <div style="font-size: 13px; font-weight: 700; color: var(--text-main);">${group.subject.name}</div>
                    <div style="font-size: 11px; font-weight: 600; color: var(--brand-accent); margin-top: 2px;">${group.semester ? group.semester.name : 'N/A'}</div>
                    ${yearLabel}
                </td>
                <td style="padding: 1rem; vertical-align: top;">${sessions}</td>
                <td style="padding: 1rem; vertical-align: top; font-size: 12px; font-weight: 600; color: var(--text-main);">
                    ${group.teacher ? group.teacher.name : 'N/A'}
                </td>
            `;
            tableBody.appendChild(tr);
        });
        
        lucide.createIcons();
    }
</script>
@endsection
