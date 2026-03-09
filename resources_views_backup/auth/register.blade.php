@extends('layouts.app')

@section('title', 'สมัครสมาชิก')

@section('content')
<div class="max-w-md mx-auto px-4 py-12">
    <h1 class="text-2xl font-bold text-[#285F43]">สมัครสมาชิก</h1>

    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-4">
        @csrf

        <div>
            <label class="block text-sm font-medium text-gray-700">ชื่อ</label>
            <input name="name" type="text" value="{{ old('name') }}" required autofocus
                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
            @error('name') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">อีเมล</label>
            <input name="email" type="email" value="{{ old('email') }}" required
                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
            @error('email') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">รหัสผ่าน</label>
            <input name="password" type="password" required
                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
            @error('password') <p class="text-sm text-red-600 mt-1">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700">ยืนยันรหัสผ่าน</label>
            <input name="password_confirmation" type="password" required
                class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2">
        </div>

        <button type="submit"
            class="w-full rounded-lg bg-[#285F43] text-white py-2 font-semibold hover:opacity-90">
            สมัครสมาชิก
        </button>

        <p class="text-sm text-gray-600 text-center">
            มีบัญชีแล้ว?
            <a class="text-[#285F43] hover:underline" href="{{ route('login') }}">เข้าสู่ระบบ</a>
        </p>
    </form>
</div>
@endsection