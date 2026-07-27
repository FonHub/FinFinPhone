@extends('layouts.app')

@section('title', 'ลืมรหัสผ่าน | Cashkub')

@section('content')
<section class="min-h-screen bg-[#F5F7F6]">
    

    <div class="max-w-[1000px] mx-auto px-4 sm:px-6 lg:px-8 py-10 lg:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="rounded-[28px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                <div class="px-6 sm:px-8 lg:px-10 pt-8 pb-7 bg-[linear-gradient(135deg,#F7FCF9_0%,#FFFFFF_45%,#F2FAF6_100%)] border-b border-[#EEF2EF]">
                    <div class="inline-flex items-center rounded-full bg-[#EAF7F1] text-[#13885C] text-[13px] font-bold px-4 h-10 mb-5">
                        Password recovery
                    </div>

                    <h1 class="text-[#111827] text-[30px] sm:text-[38px] font-bold leading-tight tracking-tight">
                        ลืมรหัสผ่าน
                    </h1>

                    <p class="mt-4 text-[#6B7280] text-[16px] leading-8">
                        กรอก E-mail หรือ Phone number ที่ใช้ลงทะเบียนไว้ เพื่อรับลิงก์หรือรหัสสำหรับตั้งรหัสผ่านใหม่
                    </p>
                </div>

                <div class="px-6 sm:px-8 lg:px-10 py-8">
                    <form method="POST" action="#" class="space-y-5">
                        @csrf

                        <div>
                            <label class="block text-[#111827] text-[15px] font-semibold mb-2">
                                E-mail หรือ Phone number
                            </label>
                            <input
                                type="text"
                                name="recovery"
                                placeholder="กรอกอีเมล หรือเบอร์โทรศัพท์"
                                class="w-full h-14 rounded-[16px] border border-[#DCE6E0] bg-white px-4 text-[15px] text-[#111827] placeholder:text-[#9AA7B2] focus:outline-none focus:ring-2 focus:ring-[#10A36A]/20 focus:border-[#10A36A]">
                        </div>

                        <button
                            type="submit"
                            class="inline-flex w-full items-center justify-center rounded-full bg-[#10A36A] hover:bg-[#0E8C5B] text-white text-[16px] font-bold px-6 h-14 shadow-[0_12px_30px_rgba(16,163,106,0.22)] transition">
                            ส่งคำขอตั้งรหัสผ่านใหม่
                        </button>
                    </form>

                    <div class="mt-6 text-center text-[15px] text-[#66727D]">
                        จำรหัสผ่านได้แล้ว?
                        <a href="{{ route('login') }}" class="font-bold text-[#10A36A] hover:text-[#0E8C5B]">
                            เข้าสู่ระบบ
                        </a>
                    </div>
                </div>
            </div>

            <div class="rounded-[28px] border border-[#E6ECE8] bg-white shadow-[0_20px_60px_rgba(15,23,42,0.06)] overflow-hidden">
                <div class="px-6 sm:px-8 py-8">
                    <div class="text-[#16A36C] text-[18px] mb-2">ขั้นตอนแบบ mock-up</div>
                    <h2 class="text-[#111827] text-[28px] font-bold leading-tight">
                        วิธีรีเซ็ตรหัสผ่าน
                    </h2>

                    <div class="mt-6 space-y-4">
                        <div class="rounded-[20px] border border-[#E5ECE8] bg-[#FCFDFC] p-5">
                            <div class="text-[#111827] font-bold text-[18px]">1. กรอกข้อมูลบัญชี</div>
                            <p class="mt-2 text-[#66727D] text-[15px] leading-7">
                                กรอก E-mail หรือ Phone number ที่ใช้สมัครสมาชิก
                            </p>
                        </div>

                        <div class="rounded-[20px] border border-[#E5ECE8] bg-[#FCFDFC] p-5">
                            <div class="text-[#111827] font-bold text-[18px]">2. รับลิงก์หรือรหัส</div>
                            <p class="mt-2 text-[#66727D] text-[15px] leading-7">
                                ระบบจะส่งข้อมูลสำหรับตั้งรหัสผ่านใหม่ไปยังช่องทางที่ผูกไว้
                            </p>
                        </div>

                        <div class="rounded-[20px] border border-[#E5ECE8] bg-[#FCFDFC] p-5">
                            <div class="text-[#111827] font-bold text-[18px]">3. ตั้งรหัสผ่านใหม่</div>
                            <p class="mt-2 text-[#66727D] text-[15px] leading-7">
                                ตั้งรหัสผ่านใหม่และกลับไปเข้าสู่ระบบได้ทันที
                            </p>
                        </div>

                        
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection