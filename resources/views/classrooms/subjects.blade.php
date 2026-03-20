@extends('layouts.app')

@section('title', 'Môn học của lớp - ' . $classroom->name)

@section('content')
<div style="max-width: 1000px; margin: 0 auto;">
    <div style="margin-bottom: 1.5rem; display: flex; align-items: center; justify-content: space-between;">
        <div style="display: flex; align-items: center; gap: 0.75rem;">
            <a href="{{ route('classrooms.index') }}" style="background: white; border: 1px solid var(--border-color); width: 36px; height: 36px; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--text-muted); text-decoration: none; transition: all 0.2s;">
                <i data-lucide="arrow-left" style="width: 18px;"></i>
            </a>
            <div>
                <h3 style="font-size: 1.25rem; font-weight: 700; color: var(--text-main); margin: 0;">Quản lý Môn học: {{ $classroom->name }}</h3>
                <p style="margin: 2px 0 0 0; font-size: 13px; color: var(--text-muted);">Danh sách các môn học đã được gán vào lớp và lịch trình chi tiết.</p>
            </div>
        </div>
        <a href="{{ route('classrooms.show_assign_subject', $classroom->id) }}" class="save-btn" style="padding: 0.6rem 1.25rem; text-decoration: none;">
            <i data-lucide="plus" style="width: 14px;"></i>
            Gán Môn học mới
        </a>
    </div>

    <div class="card-glass" style="border-radius: 16px; overflow: hidden;">
        <div class="table-responsive">
            <table style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="background: var(--bg-main); border-bottom: 1px solid var(--border-color);">
                        <th style="padding: 1rem 1.5rem; text-align: left; font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Thông tin Môn học</th>
                        <th style="padding: 1rem 1.5rem; text-align: center; font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Tín chỉ</th>
                        <th style="padding: 1rem 1.5rem; text-align: left; font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Lịch học & Giảng viên</th>
                        <th style="padding: 1rem 1.5rem; text-align: right; font-size: 10px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.05em;">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="subject-table-body">
                    @foreach($subjects as $sub)
                    @php
                        // Get all sessions for this subject in this classroom
                        $schedules = \App\Models\Schedule::where('classroom_id', $classroom->id)
                            ->where('subject_id', $sub->id)
                            ->with('teacher')
                            ->get();
                        $teacher = $schedules->first() ? $schedules->first()->teacher->name : 'N/A';
                    @endphp
                    <tr class="table-row" style="border-bottom: 1px solid var(--border-color);">
                        <td style="padding: 1.25rem 1.5rem;">
                            <div style="font-weight: 700; color: var(--brand-accent); font-family: monospace; font-size: 14px; margin-bottom: 2px;">{{ $sub->code }}</div>
                            <div style="font-weight: 600; color: var(--text-main); font-size: 14px;">{{ $sub->name }}</div>
                        </td>
                        <td style="padding: 1.25rem 1.5rem; text-align: center;">
                            <span style="padding: 4px 10px; background: var(--bg-main); color: var(--text-main); border-radius: 8px; font-size: 11px; font-weight: 700; border: 1px solid var(--border-color);">
                                {{ $sub->credits }} TC
                            </span>
                        </td>
                        <td style="padding: 1.25rem 1.5rem;">
                            <div style="font-size: 13px; color: var(--text-main); font-weight: 600; margin-bottom: 6px;">
                                <i data-lucide="user" style="width: 12px; display: inline; margin-right: 4px; vertical-align: middle;"></i>
                                {{ $teacher }}
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 4px;">
                                @foreach($schedules as $sch)
                                    <div style="display: flex; align-items: center; gap: 8px; font-size: 11px; color: var(--text-muted);">
                                        <span style="font-weight: 600; min-width: 50px;">
                                            @php
                                                $days = [0 => 'CN', 1 => 'T2', 2 => 'T3', 3 => 'T4', 4 => 'T5', 5 => 'T6', 6 => 'T7'];
                                                echo $days[$sch->day_of_week];
                                            @endphp
                                        </span>
                                        <span>Tiết {{ $sch->start_period }}-{{ $sch->end_period }}</span>
                                        <span style="color: var(--brand-accent); font-weight: 600;">{{ $sch->room }}</span>
                                    </div>
                                @endforeach
                            </div>
                        </td>
                        <td style="padding: 1.25rem 1.5rem; text-align: right;">
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                                <a href="{{ route('classrooms.edit_subject_configuration', [$classroom->id, $sub->id]) }}" class="btn-icon" title="Sửa Cấu hình (Ca học)">
                                    <i data-lucide="calendar-days" style="width: 14px; color: var(--brand-accent);"></i>
                                </a>
                                <a href="{{ route('classrooms.index', ['classroom_id' => $classroom->id, 'subject_id' => $sub->id]) }}" class="btn-icon" title="Danh sách Sinh viên">
                                    <i data-lucide="users" style="width: 14px;"></i>
                                </a>
                                <a href="{{ route('classrooms.edit', $classroom->id) }}" class="btn-icon" title="Sửa chi tiết">
                                    <i data-lucide="settings" style="width: 14px;"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                    @if($subjects->isEmpty())
                    <tr>
                        <td colspan="4" style="padding: 4rem; text-align: center; color: var(--text-muted); font-style: italic; font-size: 14px;">Lớp học này hiện chưa được gán môn học nào.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
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

    .table-row { transition: background 0.2s; }
    .table-row:hover { background: #fbfcfe; }
    
    .btn-icon { 
        background: white; 
        border: 1px solid var(--border-color); 
        padding: 8px; 
        border-radius: 10px; 
        cursor: pointer; 
        display: flex; 
        align-items: center; 
        transition: all 0.2s; 
        text-decoration: none;
        color: var(--text-muted);
    }
    .btn-icon:hover { 
        background: var(--bg-main); 
        border-color: var(--brand-accent); 
        color: var(--brand-accent);
        transform: translateY(-1px);
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
        transition: all 0.2s ease;
    }
    .save-btn:hover {
        background: #1e293b;
        transform: translateY(-1px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .card-glass {
        background: white;
        border: 1px solid var(--border-color);
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    }
</style>

<script>
    lucide.createIcons();
</script>
@endsection
