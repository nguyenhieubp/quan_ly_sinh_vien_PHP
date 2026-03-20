@extends('layouts.app')

@section('title', 'Quản lý Môn học')

@section('content')
<div class="card" style="padding: 0; border-radius: 8px; overflow: hidden; border: 1px solid var(--border-color);">
    <div style="padding: 1.25rem 1.5rem; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center; background: #ffffff;">
        <div>
            @if(isset($department))
                <a href="{{ route('subjects.index') }}" style="display: inline-flex; align-items: center; gap: 0.4rem; text-decoration: none; color: #94a3b8; font-weight: 700; font-size: 11px; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.5rem;">
                    <i data-lucide="arrow-left" style="width: 12px;"></i>
                    Quay lại danh sách Khoa
                </a>
                <h3 style="font-size: 1.15rem; color: var(--text-title); margin-bottom: 0.25rem; font-weight: 700;">Môn học thuộc {{ $department->name }}</h3>
            @else
                <h3 style="font-size: 1.15rem; color: var(--text-title); margin-bottom: 0.25rem; font-weight: 700;">Danh mục Môn học theo Khoa</h3>
            @endif
            <p style="color: var(--text-body); font-size: var(--fs-xs);">Quản lý chương trình đào tạo, mã học phần và số tín chỉ của các môn học.</p>
        </div>
        <div style="display: flex; gap: 0.75rem;">
            @if(isset($department))
                <a href="{{ route('subjects.create', ['department_id' => $department->id]) }}" class="btn btn-primary" style="font-size: var(--fs-xs); padding: 0.5rem 1rem;">
                    <i data-lucide="plus" style="width: 14px; margin-right: 4px;"></i>
                    Thêm môn mới
                </a>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div style="margin: 1rem 1.5rem; background: #ecfdf5; color: #065f46; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid #a7f3d0; display: flex; align-items: center; gap: 0.75rem; font-size: var(--fs-sm);">
            <i data-lucide="check-circle" style="width: 16px;"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="margin: 1rem 1.5rem; background: #fef2f2; color: #991b1b; padding: 0.75rem 1rem; border-radius: 8px; border: 1px solid #fecaca; display: flex; align-items: center; gap: 0.75rem; font-size: var(--fs-sm);">
            <i data-lucide="alert-circle" style="width: 16px;"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="table-responsive">
        @if(isset($department))
            <!-- Subjects Table -->
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 15%;">MÃ MÔN</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0;">TÊN MÔN HỌC</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 120px;">SỐ TÍN CHỈ</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 120px;">THAO TÁC</th>
                    </tr>
                </thead>
                <tbody id="subject-table-body">
                    @forelse($subjects as $sub)
                    <tr class="table-row" style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 0.85rem 1rem; font-weight: 700; color: var(--brand-primary); font-family: monospace; font-size: var(--fs-sm);">{{ $sub->code }}</td>
                        <td style="padding: 0.85rem 1rem; font-weight: 600; color: var(--text-title); font-size: var(--fs-sm);">{{ $sub->name }}</td>
                        <td style="padding: 0.85rem 1rem; text-align: center;">
                            <span style="padding: 4px 10px; background: #f0fdf4; color: #166534; border-radius: 6px; font-size: var(--fs-xs); font-weight: 700; border: 1px solid #dcfce7;">
                                {{ $sub->credits }} TC
                            </span>
                        </td>
                        <td style="padding: 0.85rem 1rem; text-align: right;">
                            <div style="display: flex; gap: 0.4rem; justify-content: flex-end;">
                                <a href="{{ route('subjects.edit', $sub->id) }}" class="btn-icon" title="Sửa">
                                    <i data-lucide="edit-3" style="width: 14px; color: #64748b;"></i>
                                </a>
                                <form action="{{ route('subjects.destroy', $sub->id) }}" method="POST" onsubmit="return confirm('Xóa môn học này?')" style="display: inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn-icon" title="Xóa">
                                        <i data-lucide="trash-2" style="width: 14px; color: #ef4444;"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="padding: 3rem; text-align: center; color: #94a3b8; font-size: var(--fs-sm); font-weight: 600;">Khoa hiện chưa có môn học nào.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        @else
            <!-- Departments Table -->
            <table class="data-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: #f8fafc;">
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 15%;">MÃ KHOA</th>
                        <th style="padding: 0.75rem 1rem; text-align: left; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0;">TÊN KHOA</th>
                        <th style="padding: 0.75rem 1rem; text-align: center; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 120px;">SỐ MÔN HỌC</th>
                        <th style="padding: 0.75rem 1rem; text-align: right; font-size: var(--fs-xs); color: #475569; border-bottom: 2px solid #e2e8f0; width: 150px;">THAO TÁC</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($departments as $dept)
                    <tr class="table-row" style="border-bottom: 1px solid #f1f5f9;">
                        <td style="padding: 0.85rem 1rem; font-weight: 700; color: var(--brand-primary); font-family: monospace; font-size: var(--fs-sm);">{{ $dept->code }}</td>
                        <td style="padding: 0.85rem 1rem; font-weight: 600; color: var(--text-title); font-size: var(--fs-sm);">{{ $dept->name }}</td>
                        <td style="padding: 0.85rem 1rem; text-align: center;">
                            <span style="padding: 4px 10px; background: #eff6ff; color: #1e40af; border-radius: 6px; font-size: var(--fs-xs); font-weight: 700; border: 1px solid #dbeafe;">
                                {{ $dept->subjects_count }} môn
                            </span>
                        </td>
                        <td style="padding: 0.85rem 1rem; text-align: right;">
                            <a href="{{ route('subjects.index', ['department_id' => $dept->id]) }}" class="btn btn-primary" style="font-size: 11px; padding: 0.4rem 0.75rem; font-weight: 700;">
                                Quản lý môn học
                                <i data-lucide="chevron-right" style="width: 12px; margin-left: 4px;"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>

<style>
    .table-row:hover { background: #f8fafc; }
    .col-filter:focus { border-color: var(--brand-primary) !important; outline: none; box-shadow: 0 0 0 2px rgba(79, 70, 229, 0.1); }
    .btn-icon { background: #ffffff; border: 1px solid #e2e8f0; padding: 6px; border-radius: 6px; cursor: pointer; display: flex; align-items: center; transition: all 0.2s; text-decoration: none; border-style: solid; box-sizing: border-box; }
    .btn-icon:hover { background: #f1f5f9; border-color: #cbd5e1; transform: translateY(-1px); }
</style>

<script>
    lucide.createIcons();

    const filters = document.querySelectorAll('.col-filter');
    const tableBody = document.getElementById('subject-table-body');
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
