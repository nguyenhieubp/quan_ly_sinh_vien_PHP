<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Attendance;
use App\Models\CourseRegistration;
use App\Models\Schedule;
use App\Models\Semester;
use App\Models\AcademicYear;
use App\Models\Grade;
use Carbon\Carbon;

class StudentPortalController extends Controller
{
    public function dashboard(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        // Ensure academic years exist
        AcademicYear::getRelevantYears();
        
        $academicYears = AcademicYear::orderBy('name', 'asc')->get();
        $selectedYearId = $request->query('academic_year_id');
        $search = $request->query('search');

        if (!$selectedYearId && !$search) {
            // Default to newest if nothing selected
            $selectedYearId = $academicYears->first()->id ?? null;
        }

        $query = CourseRegistration::where('student_id', $student->id)
            ->with(['schedule.subject', 'schedule.classroom', 'schedule.academicYear', 'semester']);

        if ($selectedYearId) {
            $query->whereHas('schedule', function($q) use ($selectedYearId) {
                $q->where('academic_year_id', $selectedYearId);
            });
        }

        if ($search) {
            $query->whereHas('schedule.subject', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $registrations = $query->get();
        
        return view('student.dashboard', compact(
            'student', 
            'registrations', 
            'academicYears', 
            'selectedYearId', 
            'search'
        ));
    }

    public function detailedSchedule()
    {
        $student = Auth::guard('student')->user();
        
        // Get unique subjects/classroom combos for the student in current/latest semester
        $activeSemester = Semester::where('is_active', true)->first() ?? Semester::orderBy('id', 'desc')->first();
        
        $registrations = CourseRegistration::where('student_id', $student->id)
            ->where('semester_id', $activeSemester->id)
            ->with(['schedule.subject', 'schedule.teacher', 'semester'])
            ->get();

        // Group by subject to calculate the full timeline for each
        $subjectSchedules = [];
        $groupedRegs = $registrations->groupBy('subject_id');

        foreach ($groupedRegs as $subjectId => $regs) {
            $firstReg = $regs->first();
            $subject = $firstReg->schedule->subject;
            $semester = $activeSemester;
            
            // Get all session slots for this subject/classroom
            $slots = Schedule::where('classroom_id', $student->classroom_id)
                ->where('subject_id', $subjectId)
                ->where('semester_id', $semester->id)
                ->get();
            
            if ($slots->isEmpty()) continue;

            $totalPeriods = $slots->first()->total_periods;
            $sessions = $this->calculateSessions($semester, $totalPeriods, $slots);

            $subjectSchedules[] = [
                'subject' => $subject,
                'teacher' => $firstReg->schedule->teacher,
                'total_sessions' => count($sessions),
                'start_date' => $sessions[0]['date'] ?? 'N/A',
                'end_date' => end($sessions)['date'] ?? 'N/A',
                'sessions' => $sessions,
                'credits' => $subject->credits
            ];
        }

        return view('student.detailed-schedule', compact('student', 'subjectSchedules', 'activeSemester'));
    }

    private function calculateSessions($semester, $totalPeriods, $slots)
    {
        if (!$semester->start_date) return [];

        $periodsPerWeek = 0;
        foreach ($slots as $slot) {
            $periodsPerWeek += (int)$slot->end_period - (int)$slot->start_period + 1;
        }

        if ($periodsPerWeek <= 0) return [];

        $sessions = [];
        $startDate = Carbon::parse($semester->start_date);
        $currentTotalPeriods = 0;
        $weekOffset = 0;

        while ($currentTotalPeriods < $totalPeriods) {
            $weeklySessions = [];
            foreach ($slots as $slot) {
                $dayOfWeek = (int)$slot->day_of_week;
                $sessionDate = $startDate->copy()->addWeeks($weekOffset);
                while ($sessionDate->dayOfWeek !== $dayOfWeek) {
                    $sessionDate->addDay();
                }

                $weeklySessions[] = [
                    'date_obj' => $sessionDate,
                    'date' => $sessionDate->format('d/m/Y'),
                    'day' => $this->getDayDisplay($dayOfWeek),
                    'periods' => $slot->start_period . ' - ' . $slot->end_period,
                    'p_count' => (int)$slot->end_period - (int)$slot->start_period + 1,
                    'room' => $slot->room
                ];
            }

            usort($weeklySessions, function($a, $b) {
                return $a['date_obj']->timestamp <=> $b['date_obj']->timestamp;
            });

            foreach ($weeklySessions as $s) {
                if ($currentTotalPeriods >= $totalPeriods) break;
                
                $sessions[] = [
                    'index' => count($sessions) + 1,
                    'date' => $s['date'],
                    'day' => $s['day'],
                    'periods' => $s['periods'],
                    'room' => $s['room']
                ];
                $currentTotalPeriods += $s['p_count'];
            }
            
            $weekOffset++;
            if ($weekOffset > 52) break;
        }

        return $sessions;
    }

    private function getDayDisplay($day)
    {
        $days = [0 => 'Chủ nhật', 1 => 'Thứ 2', 2 => 'Thứ 3', 3 => 'Thứ 4', 4 => 'Thứ 5', 5 => 'Thứ 6', 6 => 'Thứ 7'];
        return $days[$day] ?? '';
    }

    public function profile()
    {
        $student = Auth::guard('student')->user();
        return view('student.profile', compact('student'));
    }

    public function updateProfile(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (empty($validated['password'])) {
            unset($validated['password']);
        } else {
            $validated['password'] = bcrypt($validated['password']);
        }

        $student->update($validated);

        return redirect()->route('student.profile')->with('success', 'Thông tin cá nhân đã được cập nhật!');
    }

    public function schedule()
    {
        $student = Auth::guard('student')->user();
        
        // Get all schedules student is registered for
        $schedules = Schedule::whereIn('id', function($query) use ($student) {
            $query->select('schedule_id')
                ->from('course_registrations')
                ->where('student_id', $student->id);
        })->with(['subject', 'classroom'])->get();

        return view('student.schedule', compact('student', 'schedules'));
    }

    public function grades(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        AcademicYear::getRelevantYears();
        $academicYears = AcademicYear::orderBy('name', 'asc')->get();
        $selectedYearId = $request->query('academic_year_id');
        $search = $request->query('search');

        $query = Grade::where('student_id', $student->id)
            ->with(['subject', 'semester.academicYear']);

        if ($selectedYearId) {
            $query->where('academic_year_id', $selectedYearId);
        }

        if ($search) {
            $query->whereHas('subject', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        $grades = $query->get()->groupBy('semester.name');

        return view('student.grades', compact('student', 'grades', 'academicYears', 'selectedYearId', 'search'));
    }

    public function support()
    {
        $student = Auth::guard('student')->user();
        return view('student.support', compact('student'));
    }
}
