@extends('layouts.app')

@section('title', 'Chọn lớp nhập điểm')

@section('content')
<div style="max-width: 650px; margin: 3rem auto; padding: 0 1.5rem;">
    <!-- Centered Header -->
    <div style="text-align: center; margin-bottom: 3rem;">
        <div style="display: inline-flex; padding: 12px; background: var(--brand-primary); border-radius: 20px; color: white; margin-bottom: 1.5rem; box-shadow: 0 10px 20px rgba(79, 70, 229, 0.2);">
            <i data-lucide="award" style="width: 32px; height: 32px;"></i>
        </div>
        <h2 style="font-size: 2rem; font-weight: 800; color: var(--text-title); margin: 0; letter-spacing: -0.03em;">Quản lý điểm số</h2>
        <p style="color: #64748b; font-size: 1rem; margin-top: 0.75rem; font-weight: 500;">Chọn thông tin lớp học để bắt đầu nhập liệu</p>
    </div>

    @if (session('error'))
        <div style="background: #fef2f2; border: 1px solid #fee2e2; color: #991b1b; padding: 1rem; border-radius: 12px; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.75rem; font-size: 0.9rem;">
            <i data-lucide="alert-circle" style="width: 18px;"></i>
            <span style="font-weight: 600;">{{ session('error') }}</span>
        </div>
    @endif

    <form action="{{ route('grades.bulk') }}" method="GET">
        <div style="background: white; border-radius: 24px; border: 1px solid var(--border-color); padding: 2.5rem; box-shadow: 0 20px 40px rgba(0,0,0,0.03);">
            <div style="display: grid; gap: 1.75rem;">
                
                <!-- Classroom Selection -->
                <div class="form-group">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Lớp học mục tiêu</label>
                    <div style="position: relative;">
                        <i data-lucide="hotel" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); width: 18px; color: #94a3b8;"></i>
                        <select name="classroom_id" class="styled-select" required>
                            <option value="">-- Chọn lớp học --</option>
                            @foreach($classrooms as $cls)
                                <option value="{{ $cls->id }}">{{ $cls->name }} ({{ $cls->code }})</option>
                            @endforeach
                        </select>
                        <i data-lucide="chevron-down" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); width: 18px; color: #94a3b8; pointer-events: none;"></i>
                    </div>
                </div>

                <!-- Subject Selection -->
                <div class="form-group">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Môn học học phần</label>
                    <div style="position: relative;">
                        <i data-lucide="book-open" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); width: 18px; color: #94a3b8;"></i>
                        <select name="subject_id" class="styled-select" required>
                            <option value="">-- Chọn môn học --</option>
                            @foreach($subjects as $sub)
                                <option value="{{ $sub->id }}">{{ $sub->name }} ({{ $sub->code }})</option>
                            @endforeach
                        </select>
                        <i data-lucide="chevron-down" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); width: 18px; color: #94a3b8; pointer-events: none;"></i>
                    </div>
                </div>

                <!-- Semester -->
                <div class="form-group">
                    <label style="display: block; font-size: 0.75rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.75rem;">Học kỳ áp dụng</label>
                    <div style="position: relative;">
                        <i data-lucide="calendar" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); width: 18px; color: #94a3b8;"></i>
                        <select name="semester_id" class="styled-select" required>
                            @foreach($semesters as $sem)
                                <option value="{{ $sem->id }}" {{ $sem->is_active ? 'selected' : '' }}>{{ $sem->name }}</option>
                            @endforeach
                        </select>
                        <i data-lucide="chevron-down" style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); width: 18px; color: #94a3b8; pointer-events: none;"></i>
                    </div>
                </div>

                <div style="margin-top: 1.5rem;">
                    <button type="submit" class="premium-btn">
                        <span>Tiếp tục nhập liệu</span>
                        <i data-lucide="arrow-right" style="width: 20px;"></i>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<style>
    .styled-select {
        width: 100%;
        padding: 1rem 1rem 1rem 3rem;
        border-radius: 14px;
        border: 1.5px solid #eef2f6;
        background: #f8fafc;
        font-size: 0.95rem;
        font-weight: 600;
        color: var(--text-title);
        appearance: none;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .styled-select:focus {
        border-color: var(--brand-primary);
        background: white;
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        outline: none;
    }
    .premium-btn {
        width: 100%;
        background: var(--brand-primary);
        color: white;
        border: none;
        padding: 1.25rem;
        border-radius: 16px;
        font-size: 1rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 8px 25px rgba(79, 70, 229, 0.3);
    }
    .premium-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(79, 70, 229, 0.4);
        background: #4338ca;
    }
    .premium-btn:active {
        transform: translateY(0);
    }
</style>

<script>
    lucide.createIcons();
</script>
@endsection
