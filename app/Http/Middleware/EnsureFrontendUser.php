<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureFrontendUser
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'กรุณาเข้าสู่ระบบก่อนใช้งาน');
        }

        $user = Auth::user();

        if (!$user || (int) $user->status !== 1) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('error', 'บัญชีนี้ไม่สามารถเข้าใช้งานได้');
        }

        if ((int) $user->is_super_admin === 1) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()
                ->route('login')
                ->with('error', 'บัญชีแอดมินไม่สามารถเข้าใช้งานหน้าสมาชิกได้');
        }

        return $next($request);
    }
}
