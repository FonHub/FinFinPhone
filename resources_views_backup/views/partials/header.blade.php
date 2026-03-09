<header class="w-full bg-[#EAF4EF]/80 backdrop-blur-md border-b border-[#dbe8e1] sticky top-0 z-50">
    <div class="max-w-[1200px] mx-auto px-4 md:px-6">
        <div class="h-[72px] flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-2 shrink-0">
                <img
                    src="{{ asset('assets/media/svg/brand-logos/apple-black.svg') }}"
                    alt="Fin Fin Phone Logo"
                    class="w-7 h-7 object-contain" />
                <span class="text-[#1E5B3A] font-extrabold text-[24px] leading-none tracking-tight">
                    Fin Fin Phone
                </span>
            </a>

            <nav class="hidden md:flex items-center gap-10">
                <a href="{{ route('sell.product') }}"
                    class="text-[14px] font-semibold transition {{ request()->is('/') ? 'text-[#1E5B3A]' : 'text-[#1F2937] hover:text-[#2F7A4E]' }}">
                    ขายสินค้า
                </a>

                <a href="{{ route('articles') }}"
                    class="text-[14px] font-semibold transition {{ request()->is('review') ? 'text-[#1E5B3A]' : 'text-[#1F2937] hover:text-[#2F7A4E]' }}">
                    บทความ
                </a>

                <a href="{{ route('faq') }}"
                    class="text-[14px] font-semibold transition {{ request()->is('condition') ? 'text-[#1E5B3A]' : 'text-[#1F2937] hover:text-[#2F7A4E]' }}">
                    คำถามที่พบบ่อย
                </a>

                <a href="{{ route('about') }}"
                    class="text-[14px] font-semibold transition {{ request()->is('about') ? 'text-[#1E5B3A]' : 'text-[#1F2937] hover:text-[#2F7A4E]' }}">
                    เกี่ยวกับเรา
                </a>
            </nav>

            <div class="flex items-center gap-3">
                <a href="{{ url('/login') }}"
                    class="w-9 h-9 rounded-full border border-[#C7D8CE] bg-white flex items-center justify-center text-[#1E5B3A] hover:bg-[#F4FAF7] transition"
                    aria-label="บัญชีผู้ใช้">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M20 21a8 8 0 0 0-16 0"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </a>

                <button type="button"
                    id="mobileMenuBtn"
                    class="md:hidden w-9 h-9 rounded-full border border-[#C7D8CE] bg-white flex items-center justify-center text-[#1E5B3A]"
                    aria-label="เปิดเมนู">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <line x1="4" y1="6" x2="20" y2="6"></line>
                        <line x1="4" y1="12" x2="20" y2="12"></line>
                        <line x1="4" y1="18" x2="20" y2="18"></line>
                    </svg>
                </button>
            </div>
        </div>

        <div id="mobileMenu" class="md:hidden hidden pb-4">
            <div class="bg-white rounded-2xl border border-[#DDE9E2] shadow-sm p-3 space-y-1">
                <a href="{{ route('sell.product') }}"
                    class="block px-4 py-3 rounded-xl text-[14px] font-semibold {{ request()->is('/') ? 'bg-[#EAF4EF] text-[#1E5B3A]' : 'text-[#1F2937] hover:bg-[#F7FBF9]' }}">
                    ขายสินค้า
                </a>
                <a href="{{ route('articles') }}"
                    class="block px-4 py-3 rounded-xl text-[14px] font-semibold {{ request()->is('review') ? 'bg-[#EAF4EF] text-[#1E5B3A]' : 'text-[#1F2937] hover:bg-[#F7FBF9]' }}">
                    บทความ
                </a>
                <a href="{{ route('faq') }}"
                    class="block px-4 py-3 rounded-xl text-[14px] font-semibold {{ request()->is('condition') ? 'bg-[#EAF4EF] text-[#1E5B3A]' : 'text-[#1F2937] hover:bg-[#F7FBF9]' }}">
                    คำถามที่พบบ่อย
                </a>
                <a href="{{ route('about') }}"
                    class="block px-4 py-3 rounded-xl text-[14px] font-semibold {{ request()->is('about') ? 'bg-[#EAF4EF] text-[#1E5B3A]' : 'text-[#1F2937] hover:bg-[#F7FBF9]' }}">
                    เกี่ยวกับเรา
                </a>
                <a href="{{ url('/login') }}"
                    class="block px-4 py-3 rounded-xl text-[14px] font-semibold text-[#1E5B3A] hover:bg-[#F7FBF9]">
                    เข้าสู่ระบบ
                </a>
            </div>
        </div>
    </div>
</header>

<script>
    (function() {
        const btn = document.getElementById('mobileMenuBtn');
        const menu = document.getElementById('mobileMenu');
        if (!btn || !menu) return;
        btn.addEventListener('click', function() {
            menu.classList.toggle('hidden');
        });
    })();
</script>