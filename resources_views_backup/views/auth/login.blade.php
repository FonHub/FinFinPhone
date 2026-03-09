@extends('layouts.app')

@section('title', 'เข้าสู่ระบบ')

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold text-[#285F43]">เข้าสู่ระบบ</h1>

    @if (session('status'))
    <div class="mt-4 text-sm text-green-700">
        {{ session('status') }}
    </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">อีเมล</label>
            <input name="email" type="email" value="{{ old('email') }}" required autofocus
                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
            <input name="password" type="password" required
                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
            @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div class="flex items-center justify-between">
            <label class="inline-flex items-center gap-2">
                <input type="checkbox" name="remember" class="rounded border-gray-300">
                <span class="text-sm text-gray-700">จำฉันไว้</span>
            </label>

            @if (Route::has('password.request'))
            <a class="text-sm text-[#285F43] hover:underline" href="{{ route('password.request') }}">
                ลืมรหัสผ่าน?
            </a>
            @endif
        </div>

        <button type="submit"
            class="w-full rounded-lg bg-[#285F43] text-white py-2 font-semibold hover:opacity-90">
            เข้าสู่ระบบ
        </button>

        <p class="text-sm text-gray-600 text-center">
            ยังไม่มีบัญชี?
            <a class="text-[#285F43] hover:underline" href="{{ route('register') }}">สมัครสมาชิก</a>
        </p>
    </form>
</div>
@endsection