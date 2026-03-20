@extends('layouts.app')

@section('title', 'Quản lý Giảng viên')

@section('content')
<div class="card" style="padding: 0; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
        <div>
            <h3 style="font-size: 1.15rem; color: var(--text-title); margin-bottom: 0.25rem; font-weight: 700;">Danh sách Giảng viên</h3>
            <p style="color: var(--text-body); font-size: var(--fs-xs);">Quản lý đội ngũ giảng viên và cán bộ giảng dạy trong toàn hệ thống.</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            <a href="{{ route('teachers.create') }}" class="btn btn-primary" style="font-size: var(--fs-xs); padding: 0.5rem 1rem;">
                <i data-lucide="plus" style="width: 14px; margin-right: 4px;"></i>
                Thêm giảng viên
            </a>
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
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0;">HỌ VÀ TÊN</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0;">EMAIL</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 150px;">SỐ ĐIỆN THOẠI</th>
                    <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 200px;">KHOA/ĐƠN VỊ</th>
                    <th style="padding: 0.75rem 1rem; text-align: right; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 120px;">THAO TÁC</th>
                </tr>
                <!-- Integrated Filter Row -->
                <tr style="background: #ffffff; border-bottom: 1px solid #e2e8f0;">
                    <td style="padding: 0.5rem 1rem;">
                        <input type="text" class="col-filter" data-col="0" placeholder="Lọc tên..." 
                            style="width: 100%; padding: 6px 8px; font-size: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; background: #fdfdfd;">
                    </td>
                    <td style="padding: 0.5rem 1rem;">
                        <input type="text" class="col-filter" data-col="1" placeholder="Lọc email..." 
                            style="width: 100%; padding: 6px 8px; font-size: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; background: #fdfdfd;">
                    </td>
                    <td style="padding: 0.5rem 1rem;">
                        <input type="text" class="col-filter" data-col="2" placeholder="Lọc SĐT..." 
                            style="width: 100%; padding: 6px 8px; font-size: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; background: #fdfdfd;">
                    </td>
                    <td style="padding: 0.5rem 1rem;">
                        <input type="text" class="col-filter" data-col="3" placeholder="Lọc khoa..." 
                            style="width: 100%; padding: 6px 8px; font-size: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; background: #fdfdfd;">
                    </td>
                    <td style="padding: 0.5rem 1rem; text-align: right;">
                        <button id="clear-filters" style="background: none; border: none; color: #ef4444; font-size: 10px; cursor: pointer; text-decoration: underline; font-weight: 600;">Xóa lọc</button>
                    </td>
                </tr>
            </thead>
            <tbody id="teacher-table-body">
                @foreach($teachers as $teacher)
                <tr class="table-row" style="border-bottom: 1px solid #f1f5f9;">
                    <td style="padding: 0.85rem 1rem; font-weight: 600; color: var(--text-title); font-size: var(--fs-sm);">{{ $teacher->name }}</td>
                    <td style="padding: 0.85rem 1rem; color: var(--text-body); font-size: var(--fs-sm);">{{ $teacher->email }}</td>
                    <td style="padding: 0.85rem 1rem; color: var(--text-body); font-size: var(--fs-sm);">{{ $teacher->phone }}</td>
                    <td style="padding: 0.85rem 1rem;">
                        <span style="padding: 4px 8px; background: #eff6ff; color: #1e40af; border-radius: 6px; font-size: var(--fs-xs); font-weight: 600;">
                            {{ $teacher->department->name }}
                        </span>
                    </td>
                    <td style="padding: 0.85rem 1rem; text-align: right;">
                        <div style="display: flex; gap: 0.4rem; justify-content: flex-end;">
                            <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn-icon" title="Sửa">
                                <i data-lucide="edit-3" style="width: 14px; color: #64748b;"></i>
                            </a>
                            <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Xóa giảng viên này?')" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-icon" title="Xóa">
                                    <i data-lucide="trash-2" style="width: 14px; color: #ef4444;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    .table-row:hover { background: #f8fafc; }
    .col-filter:focus { border-color: var(--brand-primary) !important; outline: none; box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1); }
    .btn-icon { background: #ffffff; border: 1px solid #e2e8f0; padding: 6px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; text-decoration: none; }
    .btn-icon:hover { background: #f1f5f9; border-color: #cbd5e1; transform: translateY(-1px); }
</style>

<script>
    lucide.createIcons();

    const filters = document.querySelectorAll('.col-filter');
    const tableBody = document.getElementById('teacher-table-body');
    const clearBtn = document.getElementById('clear-filters');

    function filterTable() {
        const filterValues = Array.from(filters).map(f => ({
            idx: parseInt(f.dataset.col),
            val: f.value.toLowerCase()
        }));

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
