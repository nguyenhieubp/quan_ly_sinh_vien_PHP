@extends('layouts.app')

@section('title', 'Quản lý Điểm - Chọn Khoa')

@section('content')
<div class="card" style="padding: 0; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
        <div>
            <h3 style="font-size: 1.15rem; color: var(--text-title); margin-bottom: 0.25rem; font-weight: 700;">Quản lý Điểm số</h3>
            <p style="color: var(--text-body); font-size: var(--fs-xs);">Chọn Khoa để truy xuất danh sách lớp học và ghi nhận kết quả học tập.</p>
        </div>
    </div>

    @if (session('error'))
        <div style="margin: 1rem 1.5rem; background: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; padding: 0.75rem 1rem; border-radius: 8px; display: flex; align-items: center; gap: 0.75rem; font-size: var(--fs-sm);">
            <i data-lucide="alert-circle" style="width: 16px;"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    <div class="table-responsive">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 140px;">MÃ KHOA</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0;">TÊN KHOA / ĐƠN VỊ</th>
                    <th style="padding: 0.75rem 1rem; text-align: center; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 160px;">SỐ LƯỢNG LỚP</th>
                    <th style="padding: 0.75rem 1rem; text-align: right; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 120px;">THAO TÁC</th>
                </tr>
                <!-- Integrated Filter Row -->
                <tr style="background: #ffffff; border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 0.5rem 1rem;">
                        <input type="text" class="col-filter" data-col="0" placeholder="Mã..." 
                            style="width: 100%; padding: 6px 8px; font-size: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; background: #fdfdfd; font-family: monospace;">
                    </td>
                    <td style="padding: 0.5rem 1rem;">
                        <input type="text" class="col-filter" data-col="1" placeholder="Lọc tên..." 
                            style="width: 100%; padding: 6px 8px; font-size: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; background: #fdfdfd;">
                    </td>
                    <td style="padding: 0.5rem 1rem;"></td>
                    <td style="padding: 0.5rem 1rem; text-align: right;">
                        <button id="clear-filters" style="background: none; border: none; color: #ef4444; font-size: 10px; cursor: pointer; text-decoration: underline; font-weight: 600;">Xóa lọc</button>
                    </td>
                </tr>
            </thead>
            <tbody id="dept-table-body">
                @foreach($departments as $dept)
                <tr class="table-row" style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 0.85rem 1rem; font-weight: 700; color: var(--brand-primary); font-family: monospace; font-size: var(--fs-sm);">{{ $dept->code }}</td>
                    <td style="padding: 0.85rem 1rem; font-weight: 600; color: var(--text-title); font-size: var(--fs-sm);">{{ $dept->name }}</td>
                    <td style="padding: 0.85rem 1rem; text-align: center;">
                        <span style="padding: 4px 10px; background: #f1f5f9; color: #475569; border-radius: 6px; font-size: var(--fs-xs); font-weight: 600; border: 1px solid #e2e8f0; display: inline-flex; align-items: center; gap: 4px;">
                            <i data-lucide="layout" style="width: 12px;"></i>
                            {{ $dept->classrooms_count }} Lớp
                        </span>
                    </td>
                    <td style="padding: 0.85rem 1rem; text-align: right;">
                        <a href="{{ route('grades.index', ['department_id' => $dept->id]) }}" class="btn-icon" title="Truy cập">
                            <i data-lucide="chevron-right" style="width: 16px; color: #64748b;"></i>
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
    const tableBody = document.getElementById('dept-table-body');
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
</script>
@endsection
