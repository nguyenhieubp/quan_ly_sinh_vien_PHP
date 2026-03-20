<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Teacher;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Schedule;
use App\Models\Grade;
use Carbon\Carbon;

class ChatbotService
{
    public function getResponse(string $message): string
    {
        $message = mb_strtolower($message, 'UTF-8');

        // 1. Specific Student profile / GPA query
        // Matches "điểm của SV001", "thông tin sinh viên SV001", etc.
        if (preg_match('/(điểm|thông tin|kết quả).*([a-z]{2}\d+)/i', $message, $matches)) {
            $code = strtoupper($matches[2]);
            return $this->getStudentInfo($code);
        }

        // 2. Department/Classroom count queries
        if (str_contains($message, 'bao nhiêu sinh viên') || str_contains($message, 'tổng số sinh viên')) {
            $count = Student::count();
            return "🎯 Hiện tại hệ thống đang quản lý tổng cộng **{$count} sinh viên**.";
        }

        if (str_contains($message, 'bao nhiêu giảng viên') || str_contains($message, 'tổng số giảng viên')) {
            $count = Teacher::count();
            return "👨‍🏫 Chúng ta có **{$count} giảng viên** đang công tác tại các khoa.";
        }

        // 3. Schedule queries
        if (str_contains($message, 'lịch học hôm nay') || str_contains($message, 'hôm nay học gì')) {
            return $this->getTodaySchedule();
        }

        // 4. Academic Performance queries
        if (str_contains($message, 'sinh viên xuất sắc') || str_contains($message, 'điểm cao nhất')) {
            return $this->getTopStudents();
        }

        // 5. Subject queries
        if (preg_match('/(môn|học phần).*(tín chỉ)/', $message)) {
            $count = Subject::count();
            $avg = round(Subject::avg('credits'), 1);
            return "📚 Hệ thống có **{$count} môn học** với số tín chỉ trung bình là **{$avg} TC**.";
        }

        // Default fallback (Helpful guide)
        return "🤖 Chào bạn! Tôi là EduAgent AI. Tôi có thể giúp bạn các việc sau:\n" .
               "• Tra cứu điểm/hồ sơ: 'Điểm của SV001'\n" .
               "• Thống kê: 'Hệ thống có bao nhiêu sinh viên?'\n" .
               "• Lịch học: 'Lịch học hôm nay thế nào?'\n" .
               "• Học thuật: 'Ai là sinh viên xuất sắc nhất?'";
    }

    private function getStudentInfo(string $code): string
    {
        $student = Student::with(['classroom', 'classroom.department'])->where('student_code', $code)->first();
        if (!$student) return "⚠️ Không tìm thấy sinh viên có mã **{$code}** trong hệ thống.";

        $grades = Grade::where('student_id', $student->id)->get();
        if ($grades->isEmpty()) {
            return "👤 **Sinh viên: {$student->name}**\n" .
                   "📍 Lớp: {$student->classroom->name}\n" .
                   "📝 Hiện chưa có dữ liệu điểm số.";
        }

        $avg = round($grades->avg('total_score'), 2);
        $count = $grades->count();
        
        return "👤 **Sinh viên: {$student->name}**\n" .
               "📍 Lớp: {$student->classroom->name}\n" .
               "📊 GPA trung bình: **{$avg}/10.0** (Dựa trên {$count} môn)\n" .
               "📧 Email: {$student->email}";
    }

    private function getTodaySchedule(): string
    {
        $dayOfWeek = Carbon::now()->dayOfWeek; // 0 (Sun) to 6 (Sat)
        $schedules = Schedule::with(['classroom', 'subject', 'teacher'])
            ->where('day_of_week', $dayOfWeek)
            ->orderBy('start_time')
            ->get();

        if ($schedules->isEmpty()) {
            return "☕ Hôm nay là ngày nghỉ hoặc chưa có lịch học nào được xếp.";
        }

        $response = "📅 **Lịch học hôm nay (" . Carbon::now()->format('d/m') . "):**\n";
        foreach ($schedules as $s) {
            $response .= "• " . Carbon::parse($s->start_time)->format('H:i') . ": " . 
                         "{$s->subject->name} ({$s->classroom->name}) - Phòng {$s->room}\n";
        }
        return $response;
    }

    private function getTopStudents(): string
    {
        $topGrades = Grade::with('student')
            ->orderByDesc('total_score')
            ->limit(3)
            ->get();

        if ($topGrades->isEmpty()) return "Chưa có dữ liệu điểm số để xếp hạng.";

        $response = "🚀 **Top 3 Sinh viên có điểm môn học cao nhất:**\n";
        foreach ($topGrades as $index => $g) {
            $emoji = ($index == 0) ? '🥇' : (($index == 1) ? '🥈' : '🥉');
            $response .= "{$emoji} {$g->student->name}: **{$g->total_score}** (Môn {$g->subject->name})\n";
        }
        return $response;
    }
}
