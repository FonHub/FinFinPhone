<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('admin')->check()) {
            return redirect('/admin/login')->with('error', 'กรุณาเข้าสู่ระบบก่อนใช้งาน');
        }

        $user = Auth::guard('admin')->user();

        if (!$user || (int) $user->status !== 1) {
            Auth::guard('admin')->logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/admin/login')->with('error', 'บัญชีนี้ไม่สามารถเข้าใช้งานหลังบ้านได้');
        }

        return $next($request);
    }
}