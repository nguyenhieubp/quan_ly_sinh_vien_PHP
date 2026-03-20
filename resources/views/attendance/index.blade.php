@extends('layouts.app')

@section('title', 'Điểm danh - Chọn Khoa')

@section('content')
<div class="card" style="padding: 0; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
        <div>
            <h3 style="font-size: 1.15rem; color: var(--text-title); margin-bottom: 0.25rem; font-weight: 700;">Hệ thống Điểm danh</h3>
            <p style="color: var(--text-body); font-size: var(--fs-xs);">Bước 1: Chọn Khoa hoặc Đơn vị đào tạo để bắt đầu điểm danh.</p>
        </div>
    </div>

    @if(session('success'))
        <div style="margin: 1rem 1.5rem; background: #ecfdf5; color: #065f46; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid #a7f3d0; display: flex; align-items: center; gap: 0.75rem; font-size: var(--fs-sm);">
            <i data-lucide="check-circle" style="width: 16px;"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table class="data-table" style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="background: #f8fafc;">
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0;">TÊN KHOA / ĐƠN VỊ ĐÀO TẠO</th>
                    <th style="padding: 0.75rem 1rem; text-align: center; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 120px;">SỐ LỚP</th>
                    <th style="padding: 0.75rem 1rem; text-align: right; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 100px;">THAO TÁC</th>
                </tr>
                <!-- Integrated Filter Row -->
                <tr style="background: #ffffff; border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 0.5rem 1rem;">
                        <input type="text" class="col-filter" data-col="0" placeholder="Tìm tên khoa..." 
                            style="width: 100%; padding: 6px 12px; font-size: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; background: #fdfdfd;">
                    </td>
                    <td style="padding: 0.5rem 1rem;"></td>
                    <td style="padding: 0.5rem 1rem;">
                        <button id="clear-filters" style="width: 100%; background: none; border: none; color: #ef4444; font-size: 10px; cursor: pointer; text-decoration: underline; font-weight: 600;">Xóa lọc</button>
                    </td>
                </tr>
            </thead>
            <tbody id="dept-table-body">
                @foreach($departments as $dept)
                <tr class="table-row" style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 1rem; font-weight: 600; color: var(--text-title); font-size: var(--fs-sm);">{{ $dept->name }}</td>
                    <td style="padding: 1rem; text-align: center;">
                        <span style="padding: 4px 10px; background: #f1f5f9; color: #475569; border-radius: 6px; font-size: var(--fs-xs); font-weight: 700; border: 1px solid #e2e8f0;">
                            {{ $dept->classrooms_count }} Lớp
                        </span>
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        <a href="{{ route('attendance.index', ['department_id' => $dept->id]) }}" class="btn-icon" title="Chọn khoa">
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
    const tableBody = document.getElementById('dept-table-body');
    const clearBtn = document.getElementById('clear-filters');

    function filterTable() {
        const val = filters[0].value.toLowerCase();
        const rows = tableBody.querySelectorAll('.table-row');
        rows.forEach(row => {
            const text = row.querySelector('td').textContent.toLowerCase();
            row.style.display = text.includes(val) ? '' : 'none';
        });
    }

    filters[0].addEventListener('input', filterTable);
    clearBtn.addEventListener('click', () => {
        filters[0].value = '';
        filterTable();
    });
</script>
@endsection
