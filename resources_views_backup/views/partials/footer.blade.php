<footer class="w-full mt-0">
    <div class="bg-[#38875E] text-white">
        <div class="max-w-[1200px] mx-auto px-4 md:px-6 py-12 md:py-14">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16">
                <div>
                    <h3 class="text-[22px] md:text-[24px] font-bold mb-4">
                        Fin Fin Phone Co., Ltd.
                    </h3>

                    <p class="text-[14px] md:text-[15px] leading-7 text-white/95 mb-5">
                        รับซื้อโทรศัพท์ทุกรุ่น พร้อมบริการถึงที่ให้ราคาดีที่คุณต้องถูกใจ
                    </p>

                    <p class="text-[13px] md:text-[14px] leading-7 text-white/90">
                        บริการรับซื้อโทรศัพท์มือถือมือสองทุกรุ่น ทุกยี่ห้อ พร้อมประเมินราคาก่อนขาย
                        ทีมงานดูแลอย่างมืออาชีพ ซื่อตรง โปร่งใส และรวดเร็ว <br>
                        โดยจะมีพนักงานเข้าไปรับสินค้าถึงหน้าบ้าน หรือตามสถานที่ที่คุณสะดวกในวันเวลาที่คุณต้องการ พร้อมจ่ายเงินสดทันที โดยคุณไม่ต้องเสียเวลาเดินทาง
                        สะดวกสบาย และมั่นใจได้ถึงความปลอดภัย รองรับหลากหลายยี่ห้อและรุ่น
                        ไม่ว่าจะเป็น แอปเปิล ไอโฟน (Apple iPhone) ซัมซุง (Samsung) <br> ออปโป้(Oppo) วีโว้ (Vivo) หรือ หัวเว่ย (Huawei)
                    </p>
                </div>

                <div class="md:pl-12">
                    <h4 class="text-[20px] md:text-[22px] font-bold mb-5">
                        ศูนย์ดูแลลูกค้า
                    </h4>

                    <ul class="space-y-3 text-[14px] md:text-[15px]">
                        <li><a href="{{ route('faq') }}" class="text-white/95 hover:text-white hover:underline transition">คำถามพบบ่อย</a></li>
                        <li><a href="{{ route('cancel.selling') }}" class="text-white/95 hover:text-white hover:underline transition">วิธีการยกเลิกการขาย</a></li>
                        <li><a href="{{ route('sell.at.finfinphone') }}" class="text-white/95 hover:text-white hover:underline transition"> ขายที่ Fin Fin Phone</a></li>
                        <li><a href="{{ route('how.to.sell') }}" class="text-white/95 hover:text-white hover:underline transition">วิธีการขายสินค้า</a></li>
                        <li><a href="{{ route('how.to.get.paid') }}" class="text-white/95 hover:text-white hover:underline transition">บริการรับเงิน</a></li>
                    </ul>

                    <div class="mt-8 flex items-center gap-3">
                        <!-- โทร -->
                        <a href="tel:0812345678"
                            class="w-11 h-11 rounded-full bg-white flex items-center justify-center hover:scale-105 transition"
                            aria-label="Phone">
                            <span class="social-icon"
                                style="--icon: url('{{ asset('assets/media/svg/social-logos/u_outgoing-call.svg') }}');"></span>
                        </a>

                        <!-- Email -->
                        <a href="mailto:hello@example.com"
                            class="w-11 h-11 rounded-full bg-white flex items-center justify-center hover:scale-105 transition"
                            aria-label="Email">
                            <span class="social-icon"
                                style="--icon: url('{{ asset('assets/media/svg/social-logos/u_envelope-alt.svg') }}');"></span>
                        </a>

                        <!-- Facebook (ตามที่คุณบอก) -->
                        <a href="#"
                            class="w-11 h-11 rounded-full bg-white flex items-center justify-center hover:scale-105 transition"
                            aria-label="Facebook">
                            <span class="social-icon"
                                style="--icon: url('{{ asset('assets/media/svg/social-logos/facebook-fill.svg') }}');"></span>
                        </a>

                        <!-- LINE -->
                        <a href="#"
                            class="w-11 h-11 rounded-full bg-white flex items-center justify-center hover:scale-105 transition"
                            aria-label="LINE">
                            <span class="social-icon"
                                style="--icon: url('{{ asset('assets/media/svg/social-logos/u_line.svg') }}');"></span>
                        </a>

                        <!-- Twitter/X -->
                        <a href="#"
                            class="w-11 h-11 rounded-full bg-white flex items-center justify-center hover:scale-105 transition"
                            aria-label="Twitter">
                            <span class="social-icon"
                                style="--icon: url('{{ asset('assets/media/svg/social-logos/u_twitter-alt.svg') }}');"></span>
                        </a>

                        <!-- YouTube -->
                        <a href="#"
                            class="w-11 h-11 rounded-full bg-white flex items-center justify-center hover:scale-105 transition"
                            aria-label="YouTube">
                            <span class="social-icon"
                                style="--icon: url('{{ asset('assets/media/svg/social-logos/youtube.svg') }}');"></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-[#285F43] text-white/90">
            <div class="max-w-[1200px] mx-auto px-4 md:px-6 py-3">
                <p class="text-center text-[12px] md:text-[13px] tracking-wide">
                    © {{ date('Y') }} All rights reserved. Reliance Retail Ltd.
                </p>
            </div>
        </div>
</footer>