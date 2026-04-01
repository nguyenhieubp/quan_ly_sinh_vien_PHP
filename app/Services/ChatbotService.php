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
use Illuminate\Support\Str;

class ChatbotService
{
    public function __construct()
    {
        // No AI dependencies needed
    }

    public function getResponse(string $message): string
    {
        $originalMessage = $message;
        $message = $this->normalize($message);

        // 1. GREETINGS
        if (preg_match('/(chao|hello|hi|xin chao)/i', $message)) {
            return "👋 Xin chào! Tôi là **EduAgent**. Tôi là hệ thống phản hồi tự động thông minh.\n" .
                   "Tôi có thể giúp bạn tra cứu: **Điểm số**, **Lịch học**, **Chuyên cần**, hoặc **Thông tin giảng viên**.";
        }

        // 2. THANKS
        if (preg_match('/(cam on|thanks|tks|tkx)/i', $message)) {
            return "😊 Rất vui được hỗ trợ bạn! Hãy hỏi tôi bất cứ điều gì khác nếu bạn cần nhé.";
        }

        // 3. TEACHER SEARCH (Check this before generic "info" queries)
        if (preg_match('/(giang vien|giao vien|thay|co)/i', $message)) {
            return $this->getTeacherSearch($originalMessage);
        }

        // 4. GRADE / STUDENT INFO (e.g., "điểm của Lê Văn C", "thông tin SV001")
        if (preg_match('/(diem|ket qua|thong tin|ho so|sinh vien)/i', $message)) {
            return $this->handleGradeQuery($originalMessage, $message);
        }

        // 5. ATTENDANCE (e.g., "báo cáo chuyên cần của Nguyễn Văn A")
        if (preg_match('/(chuyen can|diem danh|vang)/i', $message)) {
            return $this->handleAttendanceQuery($originalMessage, $message);
        }

        // 5. SCHEDULE (e.g., "lịch học hôm nay của lớp 21CDTH")
        if (preg_match('/(lich hoc|hom nay hoc gi|thoi khoa bieu)/i', $message)) {
            return $this->handleScheduleQuery($originalMessage, $message);
        }

        // 6. SCHEDULE (e.g., "lịch học hôm nay của lớp 21CDTH")
        if (preg_match('/(lich hoc|hom nay hoc gi|thoi khoa bieu)/i', $message)) {
            return $this->handleScheduleQuery($originalMessage, $message);
        }

        // 7. SYSTEM STATS
        if (preg_match('/(thong ke|bao nhieu|tong so)/i', $message)) {
            return $this->getSystemStats();
        }

        // 8. DEPARTMENT INFO
        if (preg_match('/(khoa|phong ban)/i', $message)) {
            return $this->getDepartmentInfo();
        }

        // Default fallback (Helpful guide)
        return "🤔 Tôi chưa hiểu rõ ý bạn lắm. Do tôi là hệ thống tự động, bạn vui lòng hỏi theo các mẫu sau:\n" .
               "• 📊 'Điểm **Lập trình Web** của **Lê Văn C**'\n" .
               "• 📅 'Báo cáo chuyên cần của **SV001**'\n" .
               "• 📖 'Lịch học **hôm nay** thế nào?'\n" .
               "• 👨‍🏫 'Tìm giảng viên **Nguyễn**'\n" .
               "• 🏥 'Hệ thống có bao nhiêu **khoa**?'";
    }

    /**
     * Handle complex grade queries by identifying student and subject.
     */
    private function handleGradeQuery($original, $normalized): string
    {
        $student = $this->extractStudent($original);
        $subject = $this->extractSubject($original);

        if (!$student) {
            return "🔍 Bạn vui lòng cung cấp **Tên** hoặc **Mã sinh viên** để tôi tra cứu điểm nhé.";
        }

        if ($subject) {
            $grade = Grade::where('student_id', $student->id)
                ->where('subject_id', $subject->id)
                ->first();

            if (!$grade) {
                return "ℹ️ Không tìm thấy dữ liệu điểm môn **{$subject->name}** cho sinh viên **{$student->name}**.";
            }

            return "📊 **Kết quả học tập môn {$subject->name}:**\n" .
                   "👤 SV: **{$student->name}** ({$student->student_code})\n" .
                   "• Chuyên cần: **{$grade->attendance}**\n" .
                   "• Giữa kỳ: **{$grade->midterm}**\n" .
                   "• Cuối kỳ: **{$grade->final}**\n" .
                   "➡️ **Tổng kết: {$grade->total_score}**";
        }

        // If only student is found, show overview
        return $this->getStudentInfo($student->student_code);
    }

    /**
     * Handle attendance queries.
     */
    private function handleAttendanceQuery($original, $normalized): string
    {
        $student = $this->extractStudent($original);
        if (!$student) {
            return "🔍 Để xem báo cáo chuyên cần, hãy nhập **Tên** hoặc **Mã SV** (VD: 'Vắng của SV001').";
        }

        return $this->getAttendanceReport($student->student_code);
    }

    /**
     * Handle schedule queries (by class or general).
     */
    private function handleScheduleQuery($original, $normalized): string
    {
        $classroom = $this->extractClassroom($original);
        $dayOfWeek = Carbon::now()->dayOfWeek;

        $query = Schedule::with(['classroom', 'subject', 'teacher'])
            ->where('day_of_week', $dayOfWeek);

        if ($classroom) {
            $query->where('classroom_id', $classroom->id);
        }

        $schedules = $query->orderBy('start_time')->get();

        if ($schedules->isEmpty()) {
            $scope = $classroom ? "của lớp **{$classroom->name}**" : "hệ thống";
            return "☕ Hôm nay {$scope} không có lịch học nào.";
        }

        $response = "📅 **Lịch học hôm nay (" . ($classroom ? "Lớp {$classroom->name}" : "Toàn trường") . "):**\n";
        foreach ($schedules as $s) {
            $response .= "• **" . Carbon::parse($s->start_time)->format('H:i') . "**: " . 
                         "{$s->subject->name} " . ($classroom ? "" : "(Lớp {$s->classroom->name})") . " - Tiết {$s->start_period}-{$s->end_period}\n";
        }
        return $response;
    }

    /**
     * Extract student from message (by code or name).
     */
    private function extractStudent($message): ?Student
    {
        // 1. Try code pattern (e.g., SV001)
        if (preg_match('/([a-z]{2}\d+)/i', $message, $matches)) {
            $student = Student::where('student_code', strtoupper($matches[1]))->first();
            if ($student) return $student;
        }

        // 2. Try by matching all student names in DB
        $normalizedMessage = $this->normalize($message);
        $students = Student::all(['id', 'name', 'student_code']);
        
        $match = null;
        $maxLen = 0;

        foreach ($students as $student) {
            $normalizedName = $this->normalize($student->name);
            if (str_contains($normalizedMessage, $normalizedName)) {
                if (strlen($normalizedName) > $maxLen) {
                    $maxLen = strlen($normalizedName);
                    $match = $student;
                }
            }
        }

        return $match;
    }

    /**
     * Extract subject from message.
     */
    private function extractSubject($message): ?Subject
    {
        $subjects = Subject::all();
        $normalizedMessage = $this->normalize($message);
        
        $match = null;
        $maxLen = 0;

        foreach ($subjects as $subject) {
            $normalizedName = $this->normalize($subject->name);
            if (str_contains($normalizedMessage, $normalizedName)) {
                if (strlen($normalizedName) > $maxLen) {
                    $maxLen = strlen($normalizedName);
                    $match = $subject;
                }
            }
        }
        return $match;
    }

    /**
     * Extract classroom from message.
     */
    private function extractClassroom($message): ?Classroom
    {
        $classrooms = Classroom::all();
        $normalizedMessage = strtoupper($message);

        $match = null;
        $maxLen = 0;

        foreach ($classrooms as $class) {
            $className = strtoupper($class->name);
            if (str_contains($normalizedMessage, $className)) {
                if (strlen($className) > $maxLen) {
                    $maxLen = strlen($className);
                    $match = $class;
                }
            }
        }
        return $match;
    }

    /**
     * Normalize string (lowercase and remove accents).
     */
    private function normalize($str): string
    {
        $str = mb_strtolower($str, 'UTF-8');
        $str = preg_replace('/(à|á|ạ|ả|ã|â|ầ|ấ|ậ|ẩ|ẫ|ă|ằ|ắ|ặ|ẳ|ẵ)/', 'a', $str);
        $str = preg_replace('/(è|é|ẹ|ẻ|ẽ|ê|ề|ế|ệ|ể|ễ)/', 'e', $str);
        $str = preg_replace('/(ì|í|ị|ỉ|ĩ)/', 'i', $str);
        $str = preg_replace('/(ò|ó|ọ|ỏ|ã|ô|ồ|ố|ộ|ổ|ỗ|ơ|ờ|ớ|ợ|ở|ỡ)/', 'o', $str);
        $str = preg_replace('/(ù|ú|ụ|ủ|ũ|ư|ừ|ứ|ự|ử|ữ)/', 'u', $str);
        $str = preg_replace('/(ỳ|ý|ỵ|ỷ|ỹ)/', 'y', $str);
        $str = preg_replace('/(đ)/', 'd', $str);
        return $str;
    }

    private function getStudentInfo(string $code): string
    {
        $student = Student::with(['classroom.department'])->where('student_code', $code)->first();
        if (!$student) return "⚠️ Không tìm thấy sinh viên có mã **{$code}**.";

        $grades = Grade::where('student_id', $student->id)->get();
        
        $header = "👤 **Hồ sơ Sinh viên: {$student->name}**\n" .
                  "📍 Lớp: **{$student->classroom->name}** ({$student->classroom->department->name})\n" .
                  "📧 Email: {$student->email}\n";

        if ($grades->isEmpty()) {
            return $header . "📝 Hiện chưa có dữ liệu điểm số.";
        }

        $avg = round($grades->avg('total_score'), 2);
        $best = $grades->sortByDesc('total_score')->first();
        
        return $header . 
               "📊 GPA: **{$avg}/10.0**\n" .
               "🌟 Môn giỏi nhất: **{$best->subject->name}** ({$best->total_score})";
    }

    private function getAttendanceReport(string $code): string
    {
        $student = Student::where('student_code', $code)->first();
        if (!$student) return "⚠️ Không tìm thấy sinh viên có mã **{$code}**.";

        $absentCount = Attendance::where('student_id', $student->id)->where('status', 'absent')->count();
        $lateCount = Attendance::where('student_id', $student->id)->where('status', 'late')->count();
        
        return "📝 **Báo cáo chuyên cần - {$student->name}**:\n" .
               "• Số buổi vắng: **{$absentCount}**\n" .
               "• Số buổi đi muộn: **{$lateCount}**\n" .
               "💡 Bạn có thể xem chi tiết tại trang cá nhân sinh viên.";
    }

    private function getSystemStats(): string
    {
        return "📊 **Thống kê hệ thống:**\n" .
               "• Tổng số sinh viên: **" . Student::count() . "**\n" .
               "• Tổng số giảng viên: **" . Teacher::count() . "**\n" .
               "• Số lớp học: **" . Classroom::count() . "**\n" .
               "• Số khoa đào tạo: **" . Department::count() . "**";
    }

    private function getDepartmentInfo(): string
    {
        $departments = Department::withCount('classrooms')->get();
        $response = "🏢 **Danh sách các khoa:**\n";
        foreach ($departments as $d) {
            $response .= "• **{$d->name}**: {$d->classrooms_count} lớp\n";
        }
        return $response;
    }

    private function getTeacherSearch(string $message): string
    {
        $normalizedMessage = $this->normalize($message);
        $teachers = Teacher::all(['id', 'name', 'email', 'phone']);
        
        $match = null;
        $maxLen = 0;

        foreach ($teachers as $teacher) {
            $normalizedName = $this->normalize($teacher->name);
            if (str_contains($normalizedMessage, $normalizedName)) {
                if (strlen($normalizedName) > $maxLen) {
                    $maxLen = strlen($normalizedName);
                    $match = $teacher;
                }
            }
        }

        if (!$match) {
            return "👨‍🏫 Bạn muốn tìm giảng viên nào? Hãy nhập tên thầy/cô (Ví dụ: 'Tìm thầy Nguyễn').";
        }

        return "🔎 **Thông tin giảng viên:**\n" .
               "• Họ tên: **{$match->name}**\n" .
               "• Email: **{$match->email}**\n" .
               "• SĐT: **" . ($match->phone ?: 'Chưa cập nhật') . "**\n" .
               "💡 Bạn có thể gửi email hoặc gọi điện cho thầy/cô nếu cần trao đổi.";
    }
}
