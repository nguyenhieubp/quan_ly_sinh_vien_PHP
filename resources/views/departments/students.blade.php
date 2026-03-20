@extends('layouts.app')

@section('title', 'Sinh viên đăng ký - ' . $subject->name)

@section('content')
<div class="card" style="padding: 0; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                <a href="{{ route('departments.index', ['department_id' => $classroom->department_id, 'classroom_id' => $classroom->id]) }}" style="text-decoration: none; color: #94a3b8; display: flex; align-items: center;" title="Quay lại">
                    <i data-lucide="arrow-left" style="width: 16px;"></i>
                </a>
                <h3 style="font-size: 1.15rem; color: var(--text-title); margin: 0; font-weight: 700;">Sinh viên đăng ký: {{ $subject->name }}</h3>
            </div>
            <p style="color: var(--text-body); font-size: var(--fs-xs);">Lớp: <b>{{ $classroom->name }}</b> | Môn: <b>{{ $subject->code }}</b></p>
        </div>
        <div style="display: flex; align-items: center; gap: 1rem;">
            <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.4rem 0.75rem; border-radius: 6px; display: flex; align-items: center; gap: 6px;">
                <i data-lucide="users" style="width: 14px; color: var(--brand-primary);"></i>
                <span style="font-size: 11px; font-weight: 700; color: #475569;">{{ $registrations->count() }} SV đăng ký</span>
            </div>
            @if($schedule)
            <button onclick="document.getElementById('enrollModal').style.display='flex'" class="btn btn-primary" style="font-size: var(--fs-xs); padding: 0.5rem 1rem;">
                <i data-lucide="plus" style="width: 14px; margin-right: 4px;"></i>
                Thêm sinh viên
            </button>
            @endif
        </div>
    </div>

    <div class="table-responsive">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 60px;">STT</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 140px;">MÃ SV</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0;">HỌ TÊN SINH VIÊN</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0;">EMAIL</th>
                    <th style="padding: 0.75rem 1rem; text-align: right; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 120px;">THAO TÁC</th>
                </tr>
                <!-- Integrated Filter Row -->
                <tr style="background: #ffffff; border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 0.5rem 1rem;"></td>
                    <td style="padding: 0.5rem 1rem;">
                        <input type="text" class="col-filter" data-col="1" placeholder="Mã..." 
                            style="width: 100%; padding: 6px 8px; font-size: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; background: #fdfdfd; font-family: monospace;">
                    </td>
                    <td style="padding: 0.5rem 1rem;">
                        <input type="text" class="col-filter" data-col="2" placeholder="Lọc tên..." 
                            style="width: 100%; padding: 6px 8px; font-size: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; background: #fdfdfd;">
                    </td>
                    <td style="padding: 0.5rem 1rem;"></td>
                    <td style="padding: 0.5rem 1rem; text-align: right;">
                        <button id="clear-filters" style="background: none; border: none; color: #ef4444; font-size: 10px; cursor: pointer; text-decoration: underline; font-weight: 600;">Xóa lọc</button>
                    </td>
                </tr>
            </thead>
            <tbody id="student-table-body">
                @foreach($registrations as $index => $reg)
                @php $student = $reg->student; @endphp
                <tr class="table-row" style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 0.85rem 1rem; font-weight: 700; color: #94a3b8; font-family: monospace; font-size: var(--fs-xs);">
                        {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                    </td>
                    <td style="padding: 0.85rem 1rem; font-weight: 700; color: var(--brand-primary); font-family: monospace; font-size: var(--fs-sm);">{{ $student->student_code }}</td>
                    <td style="padding: 0.85rem 1rem; font-weight: 600; color: var(--text-title); font-size: var(--fs-sm);">{{ $student->name }}</td>
                    <td style="padding: 0.85rem 1rem; color: #64748b; font-size: var(--fs-sm);">{{ $student->email }}</td>
                    <td style="padding: 0.85rem 1rem; text-align: right; display: flex; gap: 0.5rem; justify-content: flex-end;">
                        <a href="{{ route('students.show', $student->id) }}" class="btn-icon" title="Hồ sơ SV">
                            <i data-lucide="user" style="width: 14px; color: #64748b;"></i>
                        </a>
                        <form action="{{ route('classrooms.unenroll_student', $reg->id) }}" method="POST" onsubmit="return confirm('Gỡ sinh viên khỏi môn học này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-icon" style="color: #ef4444; border-color: #fee2e2;" title="Gỡ">
                                <i data-lucide="trash-2" style="width: 14px;"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
                @if($registrations->isEmpty())
                <tr>
                    <td colspan="5" style="padding: 3rem; text-align: center; color: #94a3b8; font-style: italic;">Chưa có sinh viên nào đăng ký môn học này.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<style>
    .table-row:hover { background: #fbfcfe; }
    .col-filter:focus { border-color: var(--brand-primary) !important; outline: none; box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1); }
    .btn-icon { background: #ffffff; border: 1px solid #e2e8f0; padding: 6px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; transition: all 0.2s; text-decoration: none; }
    .btn-icon:hover { background: #f1f5f9; border-color: #cbd5e1; transform: translateY(-1px); }
</style>

@if($schedule)
<!-- Enrollment Modal -->
<div id="enrollModal" style="display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1000; align-items: center; justify-content: center; backdrop-filter: blur(4px);">
    <div style="background: white; width: 500px; border-radius: 12px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1); overflow: hidden;">
        <div style="padding: 1.25rem; border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
            <h4 style="margin: 0; font-weight: 700; color: var(--text-title);">Thêm sinh viên vào môn học</h4>
            <button onclick="document.getElementById('enrollModal').style.display='none'" style="background: none; border: none; cursor: pointer; color: #94a3b8;">
                <i data-lucide="x" style="width: 20px;"></i>
            </button>
        </div>
        <form action="{{ route('classrooms.enroll_student', $schedule->id) }}" method="POST" style="padding: 1.5rem;">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: var(--fs-xs); font-weight: 700; color: #475569; margin-bottom: 0.5rem; text-transform: uppercase;">Chọn sinh viên từ lớp {{ $classroom->name }}</label>
                <select name="student_id" style="width: 100%; padding: 0.75rem; border-radius: 8px; border: 1px solid #e2e8f0; outline: none; font-size: var(--fs-sm); background: #f8fafc;" required>
                    <option value="">-- Chọn sinh viên --</option>
                    @foreach($availableStudents as $s)
                        <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->student_code }})</option>
                    @endforeach
                </select>
                @if($availableStudents->isEmpty())
                    <p style="font-size: 11px; color: #ef4444; margin-top: 0.5rem;">Tất cả sinh viên trong lớp đã được đăng ký môn học này.</p>
                @endif
            </div>
            <div style="display: flex; gap: 0.75rem; justify-content: flex-end;">
                <button type="button" onclick="document.getElementById('enrollModal').style.display='none'" class="btn btn-outline" style="padding: 0.5rem 1.25rem;">Hủy</button>
                <button type="submit" class="btn btn-primary" style="padding: 0.5rem 1.25rem;" {{ $availableStudents->isEmpty() ? 'disabled' : '' }}>Ghi danh</button>
            </div>
        </form>
    </div>
</div>
@endif

<script>
    lucide.createIcons();

    const filters = document.querySelectorAll('.col-filter');
    const tableBody = document.getElementById('student-table-body');
    const clearBtn = document.getElementById('clear-filters');

    function filterTable() {
        const filterValues = Array.from(filters).map(f => ({
            idx: parseInt(f.dataset.col),
            val: f.value.toLowerCase()
        })).filter(f => f.val !== '');

        const rows = tableBody.querySelectorAll('.table-row');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const isMatch = filterValues.every(f => {
                const cellText = cells[f.idx].textContent.toLowerCase();
                return cellText.includes(f.val);
            });
            row.style.display = isMatch ? '' : 'none';
        });
    }

    filters.forEach(input => {
        input.addEventListener('input', filterTable);
    });

    clearBtn.addEventListener('click', () => {
        filters.forEach(f => f.value = '');
        filterTable();
    });

    // Close modal on escape
    window.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            const modal = document.getElementById('enrollModal');
            if (modal) modal.style.display = 'none';
        }
    });

    // Close modal on outside click
    window.addEventListener('click', function(e) {
        const modal = document.getElementById('enrollModal');
        if (e.target === modal) {
            modal.style.display = 'none';
        }
    });
</script>
@endsection
