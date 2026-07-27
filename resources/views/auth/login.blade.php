@extends('layouts.app')

@section('title', 'เข้าสู่ระบบ | Cashkub')

@section('content')
    <section class="min-h-screen bg-[#F5F7F6]">
        

        <div class="max-w-[1200px] mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-14">
            <div class="flex justify-center">
                <div class="w-full max-w-[560px]">
                    <div
                        class="h-full rounded-[28px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                        <div
                            class="text-center px-6 sm:px-8 lg:px-10 pt-8 pb-7 bg-[linear-gradient(135deg,#F7FCF9_0%,#FFFFFF_45%,#F2FAF6_100%)] border-b border-[#EEF2EF]">
                            <div
                                class="inline-flex items-center rounded-full bg-[#EAF7F1] text-[#13885C] text-[13px] font-bold px-4 h-10 mb-5">
                                Welcome back
                            </div>

                            <h1
                                class="text-[#111827] text-[30px] sm:text-[38px] lg:text-[44px] font-bold leading-tight tracking-tight">
                                เข้าสู่ระบบ
                            </h1>

                            <p class="mt-4 text-[#6B7280] text-[16px] leading-8 max-w-[560px] mx-auto">
                                เข้าสู่ระบบเพื่อดูประวัติการซื้อ-ขาย ตรวจสอบรีวิวของคุณ และจัดการบัญชีในที่เดียว
                            </p>
                        </div>

                        <div class="px-6 sm:px-8 lg:px-10 py-8">
                            @if (session('success'))
                                <div
                                    class="mb-5 rounded-[16px] border border-[#CDEADC] bg-[#EAF7F1] px-4 py-3 text-[#0E8C5B] text-[14px] leading-6">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div
                                    class="mb-5 rounded-[16px] border border-[#F7CACA] bg-[#FFF1F1] px-4 py-3 text-[#C0392B] text-[14px] leading-6">
                                    {{ session('error') }}
                                </div>
                            @endif

                            @if ($errors->any())
                                <div
                                    class="mb-5 rounded-[16px] border border-[#F7CACA] bg-[#FFF1F1] px-4 py-3 text-[#C0392B] text-[14px] leading-6">
                                    {{ $errors->first() }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('login.store') }}" class="space-y-5">
                                @csrf

                                <div>
                                    <label class="block text-[#111827] text-[15px] font-semibold mb-2">
                                        E-mail หรือ Phone number <span class="text-[#E05454]">*</span>
                                    </label>
                                    <input type="text" name="login" value="{{ old('login') }}"
                                        placeholder="กรอกอีเมล หรือเบอร์โทรศัพท์"
                                        class="w-full h-14 rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A]">
                                </div>

                                <div>
                                    <label class="block text-[#111827] text-[15px] font-semibold mb-2">
                                        รหัสผ่าน <span class="text-[#E05454]">*</span>
                                    </label>
                                    <input type="password" name="password" placeholder="กรอกรหัสผ่าน"
                                        class="w-full h-14 rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A]">
                                </div>

                                <div class="flex items-center justify-between gap-4 flex-wrap">
                                    <label class="inline-flex items-center gap-3 cursor-pointer">
                                        <input type="checkbox" name="remember" value="1"
                                            {{ old('remember') ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-[#C9D7D0] text-[#10A36A] focus:ring-[#10A36A]">
                                        <span class="text-[14px] text-[#66727D]">
                                            จดจำการเข้าสู่ระบบ
                                        </span>
                                    </label>

                                    <a href="{{ route('forgot.password') }}"
                                        class="text-[14px] font-semibold text-[#10A36A] hover:text-[#0E8C5B]">
                                        ลืมรหัสผ่าน?
                                    </a>
                                </div>

                                <button type="submit"
                                    class="inline-flex w-full items-center justify-center rounded-full bg-[#10A36A] hover:bg-[#0E8C5B] text-white text-[16px] font-bold px-6 h-14 shadow-[0_12px_30px_rgba(16,163,106,0.22)] transition">
                                    เข้าสู่ระบบ
                                </button>
                            </form>

                            <div class="mt-6 text-center text-[15px] text-[#66727D]">
                                ยังไม่มีบัญชี?
                                <a href="{{ route('register') }}" class="font-bold text-[#10A36A] hover:text-[#0E8C5B]">
                                    สมัครสมาชิก
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
