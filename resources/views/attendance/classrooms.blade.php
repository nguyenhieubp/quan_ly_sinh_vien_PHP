@extends('layouts.app')

@section('title', 'Điểm danh - Chọn Lớp')

@section('content')
<div class="card" style="padding: 0; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
        <div>
            <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.25rem;">
                <a href="{{ route('attendance.index') }}" style="text-decoration: none; color: #94a3b8; display: flex; align-items: center;" title="Quay lại">
                    <i data-lucide="arrow-left" style="width: 16px;"></i>
                </a>
                <h3 style="font-size: 1.15rem; color: var(--text-title); margin: 0; font-weight: 700;">Hệ thống Điểm danh - Khoa {{ $department->name }}</h3>
            </div>
            <p style="color: var(--text-body); font-size: var(--fs-xs);">Bước 2: Chọn Lớp học để xem danh sách môn học cần điểm danh.</p>
        </div>
    </div>

    <div class="table-responsive">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 140px;">MÃ LỚP</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0;">TÊN LỚP HỌC</th>
                    <th style="padding: 0.75rem 1rem; text-align: center; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 140px;">SĨ SỐ</th>
                    <th style="padding: 0.75rem 1rem; text-align: right; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 100px;">THAO TÁC</th>
                </tr>
                <!-- Integrated Filter Row -->
                <tr style="background: #ffffff; border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 0.5rem 1rem;">
                        <input type="text" class="col-filter" data-col="0" placeholder="Lọc mã..." 
                            style="width: 100%; padding: 6px 12px; font-size: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; background: #fdfdfd; font-family: monospace;">
                    </td>
                    <td style="padding: 0.5rem 1rem;">
                        <input type="text" class="col-filter" data-col="1" placeholder="Lọc tên lớp..." 
                            style="width: 100%; padding: 6px 12px; font-size: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; background: #fdfdfd;">
                    </td>
                    <td style="padding: 0.5rem 1rem;"></td>
                    <td style="padding: 0.5rem 1rem;">
                        <button id="clear-filters" style="width: 100%; background: none; border: none; color: #ef4444; font-size: 10px; cursor: pointer; text-decoration: underline; font-weight: 600;">Xóa lọc</button>
                    </td>
                </tr>
            </thead>
            <tbody id="classroom-table-body">
                @foreach($department->classrooms as $cls)
                <tr class="table-row" style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 1rem; font-weight: 700; color: var(--brand-primary); font-family: monospace; font-size: var(--fs-sm);">{{ $cls->code }}</td>
                    <td style="padding: 1rem; font-weight: 600; color: var(--text-title); font-size: var(--fs-sm);">{{ $cls->name }}</td>
                    <td style="padding: 1rem; text-align: center;">
                        <span style="padding: 4px 10px; background: #f1f5f9; color: #475569; border-radius: 6px; font-size: var(--fs-xs); font-weight: 700; border: 1px solid #e2e8f0;">
                            {{ $cls->students_count }} SV
                        </span>
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <a href="{{ route('attendance.index', ['department_id' => $department->id, 'classroom_id' => $cls->id]) }}" class="btn-icon" title="Chọn lớp">
                            <i data-lucide="chevron-right" style="width: 18px; color: #64748b;"></i>
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    .table-row:hover { background: #fbfcfe; }
    .col-filter:focus { border-color: var(--brand-primary) !important; outline: none; box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1); }
    .btn-icon { background: #ffffff; border: 1px solid #e2e8f0; padding: 6px; border-radius: 6px; cursor: pointer; display: inline-flex; align-items: center; transition: all 0.2s; text-decoration: none; }
    .btn-icon:hover { background: #f1f5f9; border-color: #cbd5e1; transform: translateX(2px); }
</style>

<script>
    lucide.createIcons();

    const filters = document.querySelectorAll('.col-filter');
    const tableBody = document.getElementById('classroom-table-body');
    const clearBtn = document.getElementById('clear-filters');

    function filterTable() {
        const val0 = filters[0].value.toLowerCase();
        const val1 = filters[1].value.toLowerCase();
        
        const rows = tableBody.querySelectorAll('.table-row');
        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const match0 = cells[0].textContent.toLowerCase().includes(val0);
            const match1 = cells[1].textContent.toLowerCase().includes(val1);
            row.style.display = (match0 && match1) ? '' : 'none';
        });
    }

    filters.forEach(f => f.addEventListener('input', filterTable));
    clearBtn.addEventListener('click', () => {
        filters.forEach(f => f.value = '');
        filterTable();
    });
</script>
@endsection
