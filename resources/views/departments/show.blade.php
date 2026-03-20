@extends('layouts.app')

@section('title', 'Giảng viên khoa ' . $department->name)

@section('content')
<div style="display: flex; flex-direction: column; gap: 1rem;">
    <!-- Simple Header -->
    <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 0.5rem; padding-bottom: 0.5rem; border-bottom: 1px solid var(--border-color);">
        <div>
            <h2 style="font-size: 1.25rem; color: var(--text-title); margin: 0; font-weight: 700;">Khoa {{ $department->name }}</h2>
            <p style="font-size: var(--fs-xs); color: #64748b; margin-top: 2px;">
                Mã: <strong>{{ $department->code }}</strong> | 
                Quy mô: <strong>{{ $department->teachers->count() }}</strong> giảng viên
            </p>
        </div>
        <div style="display: flex; gap: 0.5rem;">
            <a href="{{ route('departments.index') }}" class="btn btn-outline" style="font-size: var(--fs-xs); padding: 0.4rem 0.8rem;">
                <i data-lucide="arrow-left" style="width: 14px;"></i>
                Quay lại
            </a>
        </div>
    </div>

    <!-- Main Teacher Table Card -->
    <div class="card" style="padding: 0; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
        <div class="table-responsive">
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 30%;">HỌ VÀ TÊN</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0;">EMAIL LIÊN HỆ</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 20%;">SỐ ĐIỆN THOẠI</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 100px;">THAO TÁC</th>
                    </tr>
                    <!-- Improved Filter Row -->
                    <tr style="background: #ffffff; border-bottom: 1px solid #e2e8f0;">
                        <td style="padding: 0.5rem 1rem;">
                            <div style="position: relative;">
                                <i data-lucide="search" style="position: absolute; left: 8px; top: 50%; transform: translateY(-50%); width: 12px; color: #94a3b8;"></i>
                                <input type="text" class="col-filter" data-col="0" placeholder="Tìm tên..." 
                                    style="width: 100%; padding: 6px 6px 6px 26px; font-size: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; background: #fdfdfd;">
                            </div>
                        </td>
                        <td style="padding: 0.5rem 1rem;">
                            <div style="position: relative;">
                                <i data-lucide="mail" style="position: absolute; left: 8px; top: 50%; transform: translateY(-50%); width: 12px; color: #94a3b8;"></i>
                                <input type="text" class="col-filter" data-col="1" placeholder="Tìm email..." 
                                    style="width: 100%; padding: 6px 6px 6px 26px; font-size: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; background: #fdfdfd;">
                            </div>
                        </td>
                        <td style="padding: 0.5rem 1rem;">
                            <div style="position: relative;">
                                <i data-lucide="phone" style="position: absolute; left: 8px; top: 50%; transform: translateY(-50%); width: 12px; color: #94a3b8;"></i>
                                <input type="text" class="col-filter" data-col="2" placeholder="Tìm SĐT..." 
                                    style="width: 100%; padding: 6px 6px 6px 26px; font-size: 0.75rem; border: 1px solid #e2e8f0; border-radius: 6px; background: #fdfdfd;">
                            </div>
                        </td>
                        <td style="padding: 0.5rem 1rem; text-align: right;">
                             <button id="clear-filters" style="background: none; border: none; color: #ef4444; font-size: 10px; cursor: pointer; text-decoration: underline; font-weight: 600;">Xóa lọc</button>
                        </td>
                    </tr>
                </thead>
                <tbody id="teacher-table-body">
                    @forelse($department->teachers as $teacher)
                    <tr class="table-row" style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 0.85rem 1rem; font-weight: 600; color: var(--text-title); font-size: var(--fs-sm);">{{ $teacher->name }}</td>
                        <td style="padding: 0.85rem 1rem; color: var(--text-body); font-size: var(--fs-sm);">{{ $teacher->email }}</td>
                        <td style="padding: 0.85rem 1rem; color: var(--text-body); font-size: var(--fs-sm);">{{ $teacher->phone }}</td>
                        <td style="padding: 0.85rem 1rem; text-align: right;">
                            <div style="display: flex; gap: 0.4rem; justify-content: flex-end;">
                                <a href="{{ route('teachers.edit', $teacher->id) }}" class="btn-icon" style="color: #64748b;" title="Sửa">
                                    <i data-lucide="edit-3" style="width: 14px;"></i>
                                </a>
                                <form action="{{ route('teachers.destroy', $teacher->id) }}" method="POST" onsubmit="return confirm('Xóa giảng viên này?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon" style="color: #ef4444;" title="Xóa">
                                        <i data-lucide="trash-2" style="width: 14px;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr id="empty-row">
                        <td colspan="4" style="text-align: center; padding: 4rem; color: #94a3b8; font-size: var(--fs-sm);">
                            <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                                <i data-lucide="inbox" style="width: 32px; opacity: 0.3;"></i>
                                Chưa có giảng viên nào thuộc khoa này.
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    .table-row:hover { background: #f8fafc; }
    .col-filter:focus { border-color: var(--brand-primary) !important; outline: none; box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1); }
    .btn-icon { background: none; border: 1px solid #e2e8f0; padding: 5px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; }
    .btn-icon:hover { background: #f1f5f9; border-color: #cbd5e1; }
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
        let visibleCount = 0;

        rows.forEach(row => {
            const cells = row.querySelectorAll('td');
            const isMatch = filterValues.every(f => {
                const cellText = cells[f.idx].textContent.toLowerCase();
                return cellText.includes(f.val);
            });
            row.style.display = isMatch ? '' : 'none';
            if (isMatch) visibleCount++;
        });

        // Toggle empty state if needed (optional)
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
