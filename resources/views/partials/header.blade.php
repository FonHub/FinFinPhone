<header class="w-full bg-[#EAF4EF]/80 backdrop-blur-md border-b border-[#dbe8e1] sticky top-0 z-50"
    style="overflow: hidden;">

    <div class="max-w-[1200px] mx-auto px-4 md:px-6">
        <div class="flex items-center justify-between" style="height: 64px; overflow: hidden;">

            <a href="{{ route('home') }}" class="flex items-center shrink-0 overflow-hidden"
                style="width: 145px; height: 44px;">

                <img src="{{ asset('assets/media/logo/logo-cashkub-02.png') }}" alt="Cashkub Logo"
                    style="
                        display: block;
                        width: 100%;
                        height: 100%;
                        max-width: 145px;
                        max-height: 44px;
                        object-fit: contain;
                        object-position: left center;
                    ">
            </a>

            <nav class="hidden md:flex items-center gap-10">
                <a href="{{ route('sell.product') }}"
                    class="text-[14px] font-semibold transition {{ request()->is('sell-product*') ? 'text-[#1E5B3A]' : 'text-[#1F2937] hover:text-[#2F7A4E]' }}">
                    ขายสินค้า
                </a>

                <a href="{{ route('articles') }}"
                    class="text-[14px] font-semibold transition {{ request()->is('articles*') ? 'text-[#1E5B3A]' : 'text-[#1F2937] hover:text-[#2F7A4E]' }}">
                    บทความ
                </a>

                <a href="{{ route('faq') }}"
                    class="text-[14px] font-semibold transition {{ request()->is('faq') ? 'text-[#1E5B3A]' : 'text-[#1F2937] hover:text-[#2F7A4E]' }}">
                    คำถามที่พบบ่อย
                </a>

                <a href="{{ route('about') }}"
                    class="text-[14px] font-semibold transition {{ request()->is('about') ? 'text-[#1E5B3A]' : 'text-[#1F2937] hover:text-[#2F7A4E]' }}">
                    เกี่ยวกับเรา
                </a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    <div class="relative hidden md:block js-user-dropdown">
                        <button type="button"
                            class="js-user-dropdown-btn w-9 h-9 rounded-full border border-[#C7D8CE] bg-white flex items-center justify-center text-[#1E5B3A] hover:bg-[#F4FAF7] transition"
                            aria-label="บัญชีผู้ใช้">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2">
                                <path d="M20 21a8 8 0 0 0-16 0"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>
                        </button>

                        <div
                            class="js-user-dropdown-menu hidden absolute right-0 mt-3 w-[220px] rounded-2xl border border-[#E5ECE8] bg-white shadow-[0_18px_45px_rgba(15,23,42,0.12)] overflow-hidden">
                            <div class="px-4 py-3 border-b border-[#EEF2EF] bg-[#FCFDFC]">
                                <div class="text-[13px] text-[#8A97A2] font-medium">
                                    บัญชีของฉัน
                                </div>
                                <div class="text-[14px] text-[#1E5B3A] font-bold truncate mt-1">
                                    {{ Auth::user()->name ?? 'สมาชิก' }}
                                </div>
                            </div>

                            <a href="{{ route('profile') }}"
                                class="block px-4 py-3 text-[14px] font-semibold text-[#1E5B3A] hover:bg-[#F7FBF9]">
                                ดูโปรไฟล์
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-3 text-[14px] font-semibold text-[#C0392B] hover:bg-[#FFF5F5]">
                                    ออกจากระบบ
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="w-9 h-9 rounded-full border border-[#C7D8CE] bg-white flex items-center justify-center text-[#1E5B3A] hover:bg-[#F4FAF7] transition"
                        aria-label="เข้าสู่ระบบ">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2">
                            <path d="M20 21a8 8 0 0 0-16 0"></path>
                            <circle cx="12" cy="7" r="4"></circle>
                        </svg>
                    </a>
                @endauth

                <button type="button" id="mobileMenuBtn"
                    class="md:hidden w-9 h-9 rounded-full border border-[#C7D8CE] bg-white flex items-center justify-center text-[#1E5B3A]"
                    aria-label="เปิดเมนู">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
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
                    class="block px-4 py-3 rounded-xl text-[14px] font-semibold {{ request()->is('sell-product*') ? 'bg-[#EAF4EF] text-[#1E5B3A]' : 'text-[#1F2937] hover:bg-[#F7FBF9]' }}">
                    ขายสินค้า
                </a>

                <a href="{{ route('articles') }}"
                    class="block px-4 py-3 rounded-xl text-[14px] font-semibold {{ request()->is('articles*') ? 'bg-[#EAF4EF] text-[#1E5B3A]' : 'text-[#1F2937] hover:bg-[#F7FBF9]' }}">
                    บทความ
                </a>

                <a href="{{ route('faq') }}"
                    class="block px-4 py-3 rounded-xl text-[14px] font-semibold {{ request()->is('faq') ? 'bg-[#EAF4EF] text-[#1E5B3A]' : 'text-[#1F2937] hover:bg-[#F7FBF9]' }}">
                    คำถามที่พบบ่อย
                </a>

                <a href="{{ route('about') }}"
                    class="block px-4 py-3 rounded-xl text-[14px] font-semibold {{ request()->is('about') ? 'bg-[#EAF4EF] text-[#1E5B3A]' : 'text-[#1F2937] hover:bg-[#F7FBF9]' }}">
                    เกี่ยวกับเรา
                </a>

                @auth
                    <div class="js-user-dropdown">
                        <button type="button"
                            class="js-user-dropdown-btn w-full flex items-center justify-between gap-3 px-4 py-3 rounded-xl text-[14px] font-semibold text-[#1E5B3A] hover:bg-[#F7FBF9] transition">
                            <span class="truncate">
                                {{ Auth::user()->name ?? 'บัญชีของฉัน' }}
                            </span>

                            <svg class="js-user-dropdown-icon w-4 h-4 transition-transform duration-200" viewBox="0 0 24 24"
                                fill="none">
                                <path d="M6 9l6 6 6-6" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>

                        <div
                            class="js-user-dropdown-menu hidden mt-2 rounded-xl border border-[#E5ECE8] bg-white shadow-[0_14px_35px_rgba(15,23,42,0.10)] overflow-hidden">
                            <a href="{{ route('profile') }}"
                                class="block px-4 py-3 text-[14px] font-semibold text-[#1E5B3A] hover:bg-[#F7FBF9]">
                                ดูโปรไฟล์
                            </a>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="w-full text-left block px-4 py-3 text-[14px] font-semibold text-[#C0392B] hover:bg-[#FFF5F5]">
                                    ออกจากระบบ
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <a href="{{ route('login') }}"
                        class="block px-4 py-3 rounded-xl text-[14px] font-semibold text-[#1E5B3A] hover:bg-[#F7FBF9]">
                        เข้าสู่ระบบ
                    </a>
                @endauth
            </div>
        </div>
    </div>
</header>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function(event) {
                event.stopPropagation();
                mobileMenu.classList.toggle('hidden');
            });
        }

        const dropdowns = document.querySelectorAll('.js-user-dropdown');

        dropdowns.forEach(function(dropdown) {
            const button = dropdown.querySelector('.js-user-dropdown-btn');
            const menu = dropdown.querySelector('.js-user-dropdown-menu');
            const icon = dropdown.querySelector('.js-user-dropdown-icon');

            if (!button || !menu) {
                return;
            }

            button.addEventListener('click', function(event) {
                event.stopPropagation();

                dropdowns.forEach(function(otherDropdown) {
                    if (otherDropdown === dropdown) {
                        return;
                    }

                    const otherMenu = otherDropdown.querySelector(
                        '.js-user-dropdown-menu');
                    const otherIcon = otherDropdown.querySelector(
                        '.js-user-dropdown-icon');

                    if (otherMenu) {
                        otherMenu.classList.add('hidden');
                    }

                    if (otherIcon) {
                        otherIcon.classList.remove('rotate-180');
                    }
                });

                menu.classList.toggle('hidden');

                if (icon) {
                    icon.classList.toggle('rotate-180');
                }
            });
        });

        document.addEventListener('click', function(event) {
            dropdowns.forEach(function(dropdown) {
                if (dropdown.contains(event.target)) {
                    return;
                }

                const menu = dropdown.querySelector('.js-user-dropdown-menu');
                const icon = dropdown.querySelector('.js-user-dropdown-icon');

                if (menu) {
                    menu.classList.add('hidden');
                }

                if (icon) {
                    icon.classList.remove('rotate-180');
                }
            });
        });
    });
</script>
