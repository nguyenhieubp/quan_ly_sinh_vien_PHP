@extends('layouts.portal')

@section('content')
<div class="card">
    <div style="margin-bottom: 2rem;">
        <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Đăng ký học phần</h3>
        <p style="font-size: 0.85rem; color: #64748b;">Chọn các môn học muốn tham gia trong học kỳ tới.</p>
    </div>

    @if(session('success'))
        <div style="background: #ecfdf5; color: #065f46; padding: 0.75rem 1rem; border-radius: 10px; margin-bottom: 1.5rem; border: 1px solid #a7f3d0; font-size: 0.85rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="table-responsive">
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: left; border-bottom: 1px solid var(--border-color);">
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">Môn học</th>
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">Lớp</th>
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">Giảng viên</th>
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">Lịch học</th>
                    <th style="padding: 1rem; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">Trạng thái</th>
                    <th style="padding: 1rem; text-align: right; font-size: 0.75rem; text-transform: uppercase; color: #94a3b8;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $schedule)
                @php $reg = $registrations->where('schedule_id', $schedule->id)->first(); @endphp
                <tr style="border-bottom: 1px solid #f8fafc;">
                    <td style="padding: 1rem;">
                        <span style="display: block; font-weight: 600;">{{ $schedule->subject->name }}</span>
                        <span style="display: block; font-size: 0.75rem; color: #94a3b8;">{{ $schedule->subject->code }}</span>
                    </td>
                    <td style="padding: 1rem;">{{ $schedule->classroom->name }}</td>
                    <td style="padding: 1rem;">{{ $schedule->teacher->name }}</td>
                    <td style="padding: 1rem;">
                        <span style="font-size: 0.8rem; font-weight: 600; color: #64748b;">
                            Thứ {{ $schedule->day_of_week + 1 == 1 ? 'CN' : $schedule->day_of_week + 1 }} | {{ $schedule->room }}
                        </span>
                    </td>
                    <td style="padding: 1rem;">
                        @if($reg)
                            <span class="badge {{ $reg->status == 'Approved' ? 'badge-success' : 'badge-info' }}">
                                {{ $reg->status == 'Approved' ? 'Đã duyệt' : ($reg->status == 'Pending' ? 'Chờ duyệt' : 'Từ chối') }}
                            </span>
                        @else
                            <span style="color: #94a3b8; font-size: 0.8rem;">Chưa đăng ký</span>
                        @endif
                    </td>
                    <td style="padding: 1rem; text-align: right;">
                        @if(!$reg)
                            <form action="{{ route('portal.store_registration', $student->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="schedule_id" value="{{ $schedule->id }}">
                                <button type="submit" class="btn btn-primary" style="padding: 0.4rem 0.8rem; font-size: 0.75rem;">
                                    Đăng ký
                                </button>
                            </form>
                        @else
                            <button class="btn btn-outline" style="padding: 0.4rem 0.8rem; font-size: 0.75rem; cursor: not-allowed; opacity: 0.5;" disabled>
                                Đang xử lý
                            </button>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
