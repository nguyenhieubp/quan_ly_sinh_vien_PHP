@extends('layouts.portal')

@section('content')
<div class="card">
    <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Bảng điểm chi tiết</h3>
        <p style="font-size: 0.85rem; color: #64748b;">Kết quả học tập tích lũy qua các học kỳ.</p>
    </div>

    <div style="overflow-x: auto;">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid var(--border-color);">
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">Môn học</th>
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">Học kỳ</th>
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">CC</th>
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">GK</th>
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">CK</th>
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">Tổng kết</th>
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">Xếp loại</th>
                </tr>
            </thead>
            <tbody>
                @forelse($grades as $grade)
                <tr style="border-bottom: 1px solid #f8fafc;">
                    <td style="padding: 1rem; font-weight: 500;">{{ $grade->subject->name }}</td>
                    <td style="padding: 1rem;"><span class="badge badge-info">{{ $grade->semester->name }}</span></td>
                    <td style="padding: 1rem; text-align: center;">{{ $grade->attendance }}</td>
                    <td style="padding: 1rem; text-align: center;">{{ $grade->midterm }}</td>
                    <td style="padding: 1rem; text-align: center;">{{ $grade->final }}</td>
                    <td style="padding: 1rem; text-align: center; font-weight: 700; color: var(--brand-primary);">{{ $grade->total_score }}</td>
                    <td style="padding: 1rem;">
                        <span class="badge {{ $grade->grade_letter == 'F' ? 'badge-danger' : 'badge-success' }}" style="{{ $grade->grade_letter == 'F' ? 'background:#fee2e2; color:#b91c1c' : '' }}">
                            {{ $grade->grade_letter }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" style="padding: 2rem; text-align: center; color: #94a3b8;">Chưa có dữ liệu điểm số.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
