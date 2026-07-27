<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'login.required' => 'กรุณากรอกอีเมลหรือเบอร์โทรศัพท์',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
        ]);

        $login = trim($request->login);
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        $user = User::query()
            ->where($field, $login)
            ->where('status', 1)
            ->where('is_super_admin', 0)
            ->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()
                ->withInput($request->only('login'))
                ->with('error', 'อีเมล เบอร์โทรศัพท์ หรือรหัสผ่านไม่ถูกต้อง');
        }

        Auth::login($user, $request->boolean('remember'));

        $request->session()->regenerate();

        return redirect()
            ->intended(route('profile'))
            ->with('success', 'เข้าสู่ระบบเรียบร้อยแล้ว');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:50', 'unique:users,phone'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'line_id' => ['nullable', 'string', 'max:100'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'กรุณากรอกชื่อ-นามสกุล',
            'phone.required' => 'กรุณากรอกเบอร์โทรศัพท์',
            'phone.unique' => 'เบอร์โทรศัพท์นี้ถูกใช้งานแล้ว',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้ถูกใช้งานแล้ว',
            'password.required' => 'กรุณากรอกรหัสผ่าน',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร',
            'password.confirmed' => 'ยืนยันรหัสผ่านไม่ตรงกัน',
        ]);

        $user = User::create([
            'name' => trim($request->name),
            'email' => trim($request->email),
            'phone' => trim($request->phone),
            'line_id' => trim((string) $request->line_id),
            'password' => Hash::make($request->password),
            'status' => 1,
            'is_super_admin' => 0,
        ]);

        Auth::login($user);

        return redirect()
            ->route('profile')
            ->with('success', 'สมัครสมาชิกเรียบร้อยแล้ว');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()
            ->route('login')
            ->with('success', 'ออกจากระบบเรียบร้อยแล้ว');
    }
}
