@php
$aboutFooter = \App\Models\AboutPageSetting::query()->first();

$footerCompanyName = $aboutFooter->footer_company_name ?? 'Cashkub Co., Ltd.';

$footerCompanyDescription = $aboutFooter->footer_company_description
?? 'รับซื้อโทรศัพท์ทุกรุ่น พร้อมบริการถึงที่ให้ราคาดีที่คุณต้องถูกใจ';

$contactPhone = trim((string) ($aboutFooter->contact_phone ?? ''));
$contactEmail = trim((string) ($aboutFooter->contact_email ?? ''));

$facebookUrl = trim((string) ($aboutFooter->social_facebook ?? ''));
$instagramUrl = trim((string) ($aboutFooter->social_instagram ?? ''));
$lineUrl = trim((string) ($aboutFooter->social_line ?? ''));
$youtubeUrl = trim((string) ($aboutFooter->social_youtube ?? ''));

$cleanPhone = preg_replace('/\s+/', '', $contactPhone);

$hasAnySocial = !empty($contactPhone)
|| !empty($contactEmail)
|| !empty($facebookUrl)
|| !empty($instagramUrl)
|| !empty($lineUrl)
|| !empty($youtubeUrl);
@endphp

<footer class="w-full mt-0">
    <div class="bg-[#38875E] text-white">
        <div class="max-w-[1200px] mx-auto px-4 md:px-6 py-12 md:py-14">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-10 md:gap-16">
                <div>
                    <h3 class="text-[22px] md:text-[24px] font-bold mb-4">
                        {{ $footerCompanyName }}
                    </h3>

                    <p class="text-[14px] md:text-[15px] leading-7 text-white/95 mb-5 whitespace-pre-line">
                        {{ $footerCompanyDescription }}
                    </p>
                </div>

                <div class="text-right md:pl-0">
                    <h4 class="text-[20px] md:text-[22px] font-bold mb-5">
                        ศูนย์ดูแลลูกค้า
                    </h4>

                    <ul class="space-y-3 text-[14px] md:text-[15px]">
                        <li>
                            <a href="{{ route('cancel.selling') }}"
                                class="text-white/95 hover:text-white hover:underline transition">
                                วิธีการยกเลิกการขาย
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('how.to.sell') }}"
                                class="text-white/95 hover:text-white hover:underline transition">
                                วิธีการขายสินค้า
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('how.to.get.paid') }}"
                                class="text-white/95 hover:text-white hover:underline transition">
                                บริการรับเงิน
                            </a>
                        </li>
                    </ul>

                    @if ($hasAnySocial)
                    <div class="mt-8 flex items-center justify-end md:justify-end gap-3 flex-wrap">
                        @if (!empty($contactPhone))
                        <a href="tel:{{ $cleanPhone }}"
                            class="w-11 h-11 rounded-full bg-white flex items-center justify-center hover:scale-105 transition"
                            aria-label="Phone">
                            <span class="social-icon"
                                style="--icon: url('{{ asset('assets/media/svg/social-logos/u_outgoing-call.svg') }}');"></span>
                        </a>
                        @endif

                        @if (!empty($contactEmail))
                        <a href="mailto:{{ $contactEmail }}"
                            class="w-11 h-11 rounded-full bg-white flex items-center justify-center hover:scale-105 transition"
                            aria-label="Email">
                            <span class="social-icon"
                                style="--icon: url('{{ asset('assets/media/svg/social-logos/u_envelope-alt.svg') }}');"></span>
                        </a>
                        @endif

                        @if (!empty($facebookUrl))
                        <a href="{{ $facebookUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-11 h-11 rounded-full bg-white flex items-center justify-center hover:scale-105 transition"
                            aria-label="Facebook">
                            <span class="social-icon"
                                style="--icon: url('{{ asset('assets/media/svg/social-logos/facebook-fill.svg') }}');"></span>
                        </a>
                        @endif

                        @if (!empty($lineUrl))
                        <a href="{{ $lineUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-11 h-11 rounded-full bg-white flex items-center justify-center hover:scale-105 transition"
                            aria-label="LINE">
                            <span class="social-icon"
                                style="--icon: url('{{ asset('assets/media/svg/social-logos/u_line.svg') }}');"></span>
                        </a>
                        @endif

                        @if (!empty($instagramUrl))
                        <a href="{{ $instagramUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-11 h-11 rounded-full bg-white flex items-center justify-center hover:scale-105 transition"
                            aria-label="Instagram">
                            <span class="social-icon"
                                style="--icon: url('{{ asset('assets/media/svg/social-logos/u_instagram.svg') }}');"></span>
                        </a>
                        @endif

                        @if (!empty($youtubeUrl))
                        <a href="{{ $youtubeUrl }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="w-11 h-11 rounded-full bg-white flex items-center justify-center hover:scale-105 transition"
                            aria-label="YouTube">
                            <span class="social-icon"
                                style="--icon: url('{{ asset('assets/media/svg/social-logos/youtube.svg') }}');"></span>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="bg-[#285F43] text-white/90">
            <div class="max-w-[1200px] mx-auto px-4 md:px-6 py-3">
                <p class="text-center text-[12px] md:text-[13px] tracking-wide">
                    © {{ date('Y') }} All rights reserved. {{ $footerCompanyName }}
                </p>
            </div>
        </div>
    </div>
</footer>