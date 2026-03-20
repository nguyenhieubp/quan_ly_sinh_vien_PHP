@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 0 auto;">
    <div style="margin-bottom: 2rem;">
        <a href="{{ route('subjects.index') }}" style="display: flex; align-items: center; gap: 0.5rem; text-decoration: none; color: #64748b; font-weight: 500; font-size: 0.9rem;">
            <i data-lucide="arrow-left" style="width: 16px;"></i>
            Quay lại danh sách
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 style="font-size: 1.5rem;">Thêm Môn học mới</h2>
        </div>

        @if ($errors->any())
            <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem;">
                <ul style="list-style: none; font-size: 0.9rem;">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('subjects.store') }}" method="POST">
            @csrf
            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-title);">Khoa phụ trách</label>
                <select name="department_id" style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color); outline: none;" required>
                    <option value="">-- Chọn Khoa --</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}" {{ (isset($department_id) && $department_id == $dept->id) || old('department_id') == $dept->id ? 'selected' : '' }}>
                            {{ $dept->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-title);">Tên Môn học</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="VD: Lập trình PHP nâng cao" 
                       style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color); outline: none;" required>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 2rem;">
                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-title);">Mã Môn học</label>
                    <input type="text" name="code" value="{{ old('code') }}" placeholder="VD: PHP02" 
                           style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color); outline: none;" required>
                </div>
                <div>
                    <label style="display: block; font-size: 0.9rem; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-title);">Số tín chỉ</label>
                    <input type="number" name="credits" value="{{ old('credits') }}" placeholder="VD: 3" 
                           style="width: 100%; padding: 0.75rem 1rem; border-radius: 10px; border: 1px solid var(--border-color); outline: none;" required>
                </div>
            </div>

            <div style="display: flex; gap: 1rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1; justify-content: center;">Lưu môn học</button>
                <a href="{{ route('subjects.index') }}" class="btn btn-outline" style="flex: 1; justify-content: center;">Hủy</a>
            </div>
        </form>
    </div>
</div>

<script>
    lucide.createIcons();
</script>
@endsection
