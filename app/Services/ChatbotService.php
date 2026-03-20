<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\Grade;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\AcademicYear;
use Carbon\Carbon;

class ChatbotService
{
    public function getResponse(string $message): string
    {
        $message = mb_strtolower($message, 'UTF-8');

        // 1. GREETINGS
        if (preg_match('/(chào|hello|hi|xin chào)/i', $message)) {
            return "👋 Xin chào! Tôi là **EduAgent AI**. Tôi có thể giúp gì cho bạn hôm nay?\n" .
                   "Bạn có thể hỏi về: **Điểm số**, **Điểm danh**, **Lịch học**, hoặc **Thông tin chung** của hệ thống.";
        }

        // 2. THANKS
        if (preg_match('/(cảm ơn|thanks|tks|tkx)/i', $message)) {
            return "😊 Rất vui được hỗ trợ bạn! Hãy hỏi tôi bất cứ điều gì khác nếu bạn cần nhé.";
        }

        // 3. STUDENT INFO / GRADES (e.g., "điểm của SV001", "thông tin SV001")
        if (preg_match('/(điểm|thông tin|hồ sơ).*([a-z]{2}\d+)/i', $message, $matches)) {
            $code = strtoupper($matches[2]);
            return $this->getStudentInfo($code);
        }

        // 4. ATTENDANCE (e.g., "chuyên cần của SV001", "điểm danh SV001")
        if (preg_match('/(chuyên cần|điểm danh|vắng).*([a-z]{2}\d+)/i', $message, $matches)) {
            $code = strtoupper($matches[2]);
            return $this->getAttendanceReport($code);
        }

        // 5. TODAY'S SCHEDULE
        if (str_contains($message, 'lịch học hôm nay') || str_contains($message, 'hôm nay học gì')) {
            return $this->getTodaySchedule();
        }

        // 6. TOP PERFORMANCE
        if (str_contains($message, 'sinh viên xuất sắc') || str_contains($message, 'điểm cao nhất') || str_contains($message, 'top')) {
            return $this->getTopStudents();
        }

        // 7. SYSTEM STATS
        if (str_contains($message, 'thống kê') || str_contains($message, 'bao nhiêu sinh viên') || str_contains($message, 'tổng số')) {
            return $this->getSystemStats();
        }

        // 8. DEPARTMENT INFO
        if (str_contains($message, 'khoa') || str_contains($message, 'phòng ban')) {
            return $this->getDepartmentInfo();
        }

        // 9. TEACHER SEARCH
        if (str_contains($message, 'giảng viên') || str_contains($message, 'thầy') || str_contains($message, 'cô')) {
            return $this->getTeacherSearch($message);
        }

        // Default fallback (Helpful guide)
        return "🤔 Tôi chưa hiểu rõ ý bạn lắm. Bạn có thể thử một trong các câu hỏi sau:\n" .
               "• 📊 'Điểm của **SV001** là bao nhiêu?'\n" .
               "• 📅 'Báo cáo chuyên cần của **SV001**'\n" .
               "• 📖 'Lịch học **hôm nay** thế nào?'\n" .
               "• 🏢 'Hệ thống có những **khoa** nào?'\n" .
               "• 👨‍🏫 'Tìm giảng viên **Nguyễn**'";
    }

    private function getStudentInfo(string $code): string
    {
        $student = Student::with(['classroom.department'])->where('student_code', $code)->first();
        if (!$student) return "⚠️ Không tìm thấy sinh viên có mã **{$code}**.";

        $grades = Grade::where('student_id', $student->id)->get();
        if ($grades->isEmpty()) {
            return "👤 **Sinh viên: {$student->name}**\n" .
                   "📍 Lớp: **{$student->classroom->name}**\n" .
                   "📝 Hiện chưa có dữ liệu điểm số.";
        }

        $avg = round($grades->avg('total_score'), 2);
        $count = $grades->count();
        $best = $grades->sortByDesc('total_score')->first();
        
        return "👤 **Hồ sơ Sinh viên: {$student->name}**\n" .
               "📍 Lớp: **{$student->classroom->name}** ({$student->classroom->department->name})\n" .
               "📊 GPA trung bình: **{$avg}/10.0** (Tổng {$count} môn)\n" .
               "🌟 Môn cao nhất: **{$best->subject->name}** ({$best->total_score})\n" .
               "📧 Liên hệ: {$student->email}";
    }

    private function getAttendanceReport(string $code): string
    {
        $student = Student::where('student_code', $code)->first();
        if (!$student) return "⚠️ Không tìm thấy sinh viên có mã **{$code}**.";

        $absentCount = Attendance::where('student_id', $student->id)->where('status', 'absent')->count();
        $lateCount = Attendance::where('student_id', $student->id)->where('status', 'late')->count();
        
        if ($absentCount == 0 && $lateCount == 0) {
            return "✅ Sinh viên **{$student->name}** rất chăm chỉ! Hiện chưa có buổi vắng hay đi muộn nào.";
        }

        return "📝 **Báo cáo chuyên cần - {$student->name}**:\n" .
               "• Số buổi vắng: **{$absentCount}**\n" .
               "• Số buổi đi muộn: **{$lateCount}**\n" .
               "💡 Bạn có thể xem chi tiết tại trang cá nhân của sinh viên.";
    }

    private function getTodaySchedule(): string
    {
        $dayOfWeek = Carbon::now()->dayOfWeek; 
        $schedules = Schedule::with(['classroom', 'subject', 'teacher'])
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time')
            ->get();

        if ($schedules->isEmpty()) {
            return "☕ Hôm nay là ngày nghỉ hoặc chưa có lịch học nào được xếp.";
        }

        $response = "📅 **Lịch học hôm nay (" . Carbon::now()->format('d/m') . "):**\n";
        foreach ($schedules as $s) {
            $periods = (int)$s->end_period - (int)$s->start_period + 1;
            $response .= "• **" . Carbon::parse($s->start_time)->format('H:i') . "**: " . 
                         "{$s->subject->name} (Lớp {$s->classroom->name}) - Tiết {$s->start_period}-{$s->end_period}\n";
        }
        return $response;
    }

    private function getTopStudents(): string
    {
        $topGrades = Grade::with(['student', 'subject'])
            ->orderByDesc('total_score')
            ->limit(5)
            ->get();

        if ($topGrades->isEmpty()) return "Chưa có dữ liệu điểm số để xếp hạng.";

        $response = "🚀 **Vinh danh Top 5 Sinh viên điểm cao nhất:**\n";
        foreach ($topGrades as $index => $g) {
            $emoji = match($index) { 0 => '🥇', 1 => '🥈', 2 => '🥉', default => '⭐' };
            $response .= "{$emoji} **{$g->student->name}**: {$g->total_score} (Môn {$g->subject->name})\n";
        }
        return $response;
    }

    private function getSystemStats(): string
    {
        $sCount = Student::count();
        $tCount = Teacher::count();
        $cCount = Classroom::count();
        $ayCount = AcademicYear::count();

        return "📊 **Thống kê hệ thống EduAgent:**\n" .
               "• Tổng số sinh viên: **{$sCount}**\n" .
               "• Tổng số giảng viên: **{$tCount}**\n" .
               "• Số lớp học: **{$cCount}**\n" .
               "• Niên khóa đang quản lý: **{$ayCount}**";
    }

    private function getDepartmentInfo(): string
    {
        $departments = Department::withCount('classrooms')->get();
        if ($departments->isEmpty()) return "Hệ thống hiện chưa có thông tin về các khoa.";

        $response = "🏢 **Danh sách các khoa đào tạo:**\n";
        foreach ($departments as $d) {
            $response .= "• **{$d->name}**: {$d->classrooms_count} lớp học trực thuộc.\n";
        }
        return $response;
    }

    private function getTeacherSearch(string $message): string
    {
        // Try to find if there's a name mentioned after 'giảng viên' or 'thầy' or 'cô'
        $search = trim(preg_replace('/(giảng viên|thầy|cô|tìm|kiếm)/i', '', $message));
        
        if (strlen($search) < 2) {
            $count = Teacher::count();
            return "👨‍🏫 Hiện có **{$count} giảng viên** đang công tác. Bạn muốn tìm cụ thể tên ai không?";
        }

        $teachers = Teacher::where('name', 'like', "%{$search}%")->limit(3)->get();
        if ($teachers->isEmpty()) return "🔍 Không tìm thấy giảng viên nào có tên liên quan đến '**{$search}**'.";

        $response = "🔎 **Kết quả tìm kiếm giảng viên:**\n";
        foreach ($teachers as $t) {
            $response .= "• **{$t->name}** (Email: {$t->email})\n";
        }
        return $response;
    }
}
