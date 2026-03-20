<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('student.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'student_code' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::guard('student')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->route('student.dashboard');
        }

        return back()->withErrors([
            'student_code' => 'Mã sinh viên hoặc mật khẩu không chính xác.',
        ])->onlyInput('student_code');
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('student.login');
    }
}
