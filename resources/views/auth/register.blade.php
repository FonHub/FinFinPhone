@extends('layouts.app')

@section('title', 'สมัครสมาชิก | Cashkub')

@section('content')
    <section class="min-h-screen bg-[#F5F7F6] flex items-center justify-center px-4 py-10">
        <div class="w-full max-w-[560px]">
            <div class="text-center mb-8">
                

                <h1 class="mt-5 text-[#111827] text-[28px] sm:text-[34px] font-extrabold">
                    สมัครสมาชิก
                </h1>

                <p class="mt-3 text-[#7A8793] text-[15px] leading-7">
                    สมัครสมาชิกเพื่อใช้โค้ดบวกราคา และติดตามคำสั่งขายของคุณ
                </p>
            </div>

            <div
                class="rounded-[24px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] p-5 sm:p-7">
                @if (session('success'))
                    <div
                        class="mb-4 rounded-[14px] border border-[#CDEADC] bg-[#EAF7F1] px-4 py-3 text-[#0E8C5B] text-[14px]">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div
                        class="mb-4 rounded-[14px] border border-[#F7CACA] bg-[#FFF1F1] px-4 py-3 text-[#C0392B] text-[14px]">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div
                        class="mb-4 rounded-[14px] border border-[#F7CACA] bg-[#FFF1F1] px-4 py-3 text-[#C0392B] text-[14px]">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('register.store') }}">
                    @csrf

                    <div class="space-y-5">
                        <div>
                            <label class="block text-[15px] font-semibold text-[#111827] mb-2">
                                ชื่อ-นามสกุล <span class="text-[#E05454]">*</span>
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}"
                                class="w-full h-[54px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition"
                                placeholder="ชื่อ-นามสกุล">
                        </div>

                        <div>
                            <label class="block text-[15px] font-semibold text-[#111827] mb-2">
                                เบอร์โทรศัพท์ <span class="text-[#E05454]">*</span>
                            </label>
                            <input type="text" name="phone" value="{{ old('phone') }}"
                                class="w-full h-[54px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition"
                                placeholder="0812345678">
                        </div>

                        <div>
                            <label class="block text-[15px] font-semibold text-[#111827] mb-2">
                                อีเมล <span class="text-[#E05454]">*</span>
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}"
                                class="w-full h-[54px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition"
                                placeholder="example@email.com">
                        </div>

                        <div>
                            <label class="block text-[15px] font-semibold text-[#111827] mb-2">
                                Line ID
                            </label>
                            <input type="text" name="line_id" value="{{ old('line_id') }}"
                                class="w-full h-[54px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition"
                                placeholder="Line ID">
                        </div>

                        <div>
                            <label class="block text-[15px] font-semibold text-[#111827] mb-2">
                                รหัสผ่าน <span class="text-[#E05454]">*</span>
                            </label>
                            <input type="password" name="password"
                                class="w-full h-[54px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition"
                                placeholder="อย่างน้อย 8 ตัวอักษร">
                        </div>

                        <div>
                            <label class="block text-[15px] font-semibold text-[#111827] mb-2">
                                ยืนยันรหัสผ่าน <span class="text-[#E05454]">*</span>
                            </label>
                            <input type="password" name="password_confirmation"
                                class="w-full h-[54px] rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A] transition"
                                placeholder="กรอกรหัสผ่านอีกครั้ง">
                        </div>

                        <button type="submit"
                            class="w-full h-[56px] rounded-full bg-[#10A36A] hover:bg-[#0E8C5B] text-white text-[17px] font-bold shadow-[0_12px_30px_rgba(16,163,106,0.22)] transition">
                            สมัครสมาชิก
                        </button>
                    </div>
                </form>

                <div class="mt-6 text-center text-[14px] text-[#7A8793]">
                    มีบัญชีอยู่แล้ว?
                    <a href="{{ route('login') }}" class="text-[#10A36A] font-bold underline underline-offset-2">
                        เข้าสู่ระบบ
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection