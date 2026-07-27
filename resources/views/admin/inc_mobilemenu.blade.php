<!-- BEGIN: Mobile Menu -->
<div class="mobile-menu md:hidden">
    @php
        use Illuminate\Support\Facades\Auth;

        $adminUser = Auth::guard('admin')->user();

        $can = function ($menuKey, $action = 'view') {
            return \App\Helpers\AdminPermission::check($menuKey, $action);
        };

        $productMenuActive =
            Request::is('admin/mobile-brands') ||
            Request::is('admin/mobile-brands/*') ||
            Request::is('admin/mobile-models') ||
            Request::is('admin/mobile-models/*') ||
            Request::is('admin/mobile-product-categories') ||
            Request::is('admin/mobile-product-categories/*') ||
            Request::is('admin/grade-masters') ||
            Request::is('admin/grade-masters/*');

        $supportMenuActive = request()->is('admin/support-pages*');
    @endphp

    <div class="mobile-menu-bar">
        <a href="{{ url('/admin') }}" class="flex mr-auto">
            <img alt="Logo" class="w-6" src="{{ asset('dist/images/logo.svg') }}">
        </a>

        <a href="javascript:;" id="mobile-menu-toggler">
            <i data-lucide="bar-chart-2" class="w-8 h-8 text-white transform -rotate-90"></i>
        </a>
    </div>

    <ul class="border-t border-white/[0.08] py-5 hidden">
        @if ($can('dashboard', 'view'))
            <li>
                <a href="{{ url('/admin') }}" class="menu {{ Request::is('admin') ? 'menu--active' : '' }}">
                    <div class="menu__icon">
                        <i data-lucide="home"></i>
                    </div>
                    <div class="menu__title">
                        Homepage
                    </div>
                </a>
            </li>
        @endif

        @if ($can('home_banner', 'view'))
            <li>
                <a href="{{ route('admin.home-banner.index') }}"
                    class="menu {{ request()->is('admin/home-banner*') ? 'menu--active' : '' }}">
                    <div class="menu__icon">
                        <i data-lucide="image"></i>
                    </div>
                    <div class="menu__title">
                        Banner
                    </div>
                </a>
            </li>
        @endif

        <li class="menu__devider my-6"></li>

        @if ($can('sale_detail', 'view'))
            <li>
                <a href="{{ url('admin/sale-detail') }}"
                    class="menu {{ Request::is('admin/sale-detail') ? 'menu--active' : '' }}">
                    <div class="menu__icon">
                        <i data-lucide="clipboard-list"></i>
                    </div>
                    <div class="menu__title">
                        รายละเอียดการขาย
                    </div>
                </a>
            </li>
        @endif

        @if ($can('parcel_setting', 'view'))
            <li>
                <a href="{{ route('admin.parcel-setting.edit') }}"
                    class="menu {{ Request::is('admin/parcel-setting') ? 'menu--active' : '' }}">
                    <div class="menu__icon">
                        <i data-lucide="package"></i>
                    </div>
                    <div class="menu__title">
                        ศูนย์รับพัสดุ
                    </div>
                </a>
            </li>
        @endif

        @if ($can('branches', 'view'))
            <li>
                <a href="{{ route('admin.branches.index') }}"
                    class="menu {{ Request::is('admin/branches') || Request::is('admin/branches/*') ? 'menu--active' : '' }}">
                    <div class="menu__icon">
                        <i data-lucide="map-pin"></i>
                    </div>
                    <div class="menu__title">
                        สาขา
                    </div>
                </a>
            </li>
        @endif

        @if ($can('transit_lines', 'view'))
            <li>
                <a href="{{ route('admin.transit-lines.index') }}"
                    class="menu {{ Request::is('admin/transit-lines') || Request::is('admin/transit-lines/*') ? 'menu--active' : '' }}">
                    <div class="menu__icon">
                        <i data-lucide="git-branch"></i>
                    </div>
                    <div class="menu__title">
                        สายรถไฟฟ้า
                    </div>
                </a>
            </li>
        @endif

        @if ($can('transit_stations', 'view'))
            <li>
                <a href="{{ route('admin.transit-stations.index') }}"
                    class="menu {{ Request::is('admin/transit-stations') || Request::is('admin/transit-stations/*') ? 'menu--active' : '' }}">
                    <div class="menu__icon">
                        <i data-lucide="map-pin"></i>
                    </div>
                    <div class="menu__title">
                        สถานีรถไฟฟ้า
                    </div>
                </a>
            </li>
        @endif

        <li class="menu__devider my-6"></li>

        @if ($can('bonus_codes', 'view'))
            <li>
                <a href="{{ url('admin/bonus-codes') }}"
                    class="menu {{ Request::is('admin/bonus-codes') || Request::is('admin/bonus-codes/create') || Request::is('admin/bonus-codes/*/edit') ? 'menu--active' : '' }}">
                    <div class="menu__icon">
                        <i data-lucide="ticket"></i>
                    </div>
                    <div class="menu__title">
                        โค้ดบวกราคา
                    </div>
                </a>
            </li>
        @endif

        @if ($can('sell_orders', 'view'))
            <li>
                <a href="{{ url('admin/sell-orders') }}"
                    class="menu {{ Request::is('admin/sell-orders') || Request::is('admin/sell-orders/*') ? 'menu--active' : '' }}">
                    <div class="menu__icon">
                        <i data-lucide="clipboard-list"></i>
                    </div>
                    <div class="menu__title">
                        คำสั่งขายสินค้า
                    </div>
                </a>
            </li>
        @endif

        <li class="menu__devider my-6"></li>

        @if ($can('mobile_brands', 'view') || $can('grade_masters', 'view') || $can('product_categories', 'view'))
            <li>
                <a href="javascript:;" class="menu {{ $productMenuActive ? 'menu--active menu--open' : '' }}">
                    <div class="menu__icon">
                        <i data-lucide="package-search"></i>
                    </div>
                    <div class="menu__title">
                        สินค้า
                        <i data-lucide="chevron-down"
                            class="menu__sub-icon {{ $productMenuActive ? 'transform rotate-180' : '' }}"></i>
                    </div>
                </a>

                <ul class="{{ $productMenuActive ? 'menu__sub-open' : '' }}">
                    @if ($can('mobile_brands', 'view'))
                        <li>
                            <a href="{{ url('admin/mobile-brands') }}"
                                class="menu {{ Request::is('admin/mobile-brands') || Request::is('admin/mobile-brands/*') || Request::is('admin/mobile-product-categories/*') || Request::is('admin/mobile-models/*') ? 'menu--active' : '' }}">
                                <div class="menu__icon">
                                    <i data-lucide="tag"></i>
                                </div>
                                <div class="menu__title">
                                    แบรนด์สินค้า
                                </div>
                            </a>
                        </li>
                    @endif

                    @if ($can('product_categories', 'view'))
                        <li>
                            <a href="{{ url('admin/mobile-product-categories') }}"
                                class="menu {{ Request::is('admin/mobile-product-categories') || Request::is('admin/mobile-product-categories/*') ? 'menu--active' : '' }}">
                                <div class="menu__icon">
                                    <i data-lucide="list"></i>
                                </div>
                                <div class="menu__title">
                                    ประเภทสินค้า
                                </div>
                            </a>
                        </li>
                    @endif

                    @if ($can('grade_masters', 'view'))
                        <li>
                            <a href="{{ url('admin/grade-masters') }}"
                                class="menu {{ Request::is('admin/grade-masters') || Request::is('admin/grade-masters/create') || Request::is('admin/grade-masters/*/edit') ? 'menu--active' : '' }}">
                                <div class="menu__icon">
                                    <i data-lucide="layers"></i>
                                </div>
                                <div class="menu__title">
                                    เกรดพื้นฐาน
                                </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif

        @if ($can('product_grade_questions', 'view'))
            <li>
                <a href="{{ url('admin/product-grade-questions/by-category') }}"
                    class="menu {{ Request::is('admin/product-grade-questions*') ? 'menu--active' : '' }}">
                    <div class="menu__icon">
                        <i data-lucide="help-circle"></i>
                    </div>
                    <div class="menu__title">
                        ชุดคำถามคัดเกรด
                    </div>
                </a>
            </li>
        @endif

        @if ($can('product_questions', 'view'))
            <li>
                <a href="{{ url('admin/product-questions') }}"
                    class="menu {{ Request::is('admin/product-questions') || Request::is('admin/product-questions/*') ? 'menu--active' : '' }}">
                    <div class="menu__icon">
                        <i data-lucide="help-circle"></i>
                    </div>
                    <div class="menu__title">
                        คำถามที่พบบ่อย
                    </div>
                </a>
            </li>
        @endif

        @if ($can('news', 'view'))
            <li>
                <a href="{{ url('admin/news') }}"
                    class="menu {{ Request::is('admin/news') || Request::is('admin/form-news') || Request::is('admin/news/*') ? 'menu--active' : '' }}">
                    <div class="menu__icon">
                        <i data-lucide="file-text"></i>
                    </div>
                    <div class="menu__title">
                        บทความ
                    </div>
                </a>
            </li>
        @endif

        {{-- @if ($can('promotion', 'view'))
            <li>
                <a href="{{ url('admin/about-us-our-value') }}"
                    class="menu {{ Request::is('admin/about-us-our-value') ? 'menu--active' : '' }}">
                    <div class="menu__icon">
                        <i data-lucide="tag"></i>
                    </div>
                    <div class="menu__title">
                        โปรโมชั่น
                    </div>
                </a>
            </li>
        @endif --}}

        @if ($can('about_page', 'view'))
            <li class="menu__devider my-6"></li>

            <li>
                <a href="{{ route('admin.about-page.edit') }}"
                    class="menu {{ Request::routeIs('admin.about-page.*') ? 'menu--active' : '' }}">
                    <div class="menu__icon">
                        <i data-lucide="file-text"></i>
                    </div>
                    <div class="menu__title">
                        จัดการหน้าเกี่ยวกับเรา
                    </div>
                </a>
            </li>
        @endif

        <li class="menu__devider my-6"></li>

        @php
            $supportMenuActive = request()->is('admin/support-pages*');

            $adminSupportPages = \App\Models\SupportPage::query()
                ->whereIn('slug', ['cancel-selling', 'how-to-sell', 'how-to-get-paid'])
                ->orderBy('sort_order', 'asc')
                ->orderBy('id', 'asc')
                ->get();

            $currentSupportPageSlug = request()->route('slug');

            if (empty($currentSupportPageSlug) && request()->routeIs('admin.support-pages.index')) {
                $currentSupportPageSlug = 'cancel-selling';
            }
        @endphp

        <li>
            <a href="javascript:;" class="menu {{ $supportMenuActive ? 'menu--active menu--open' : '' }}">
                <div class="menu__icon">
                    <i data-lucide="headphones"></i>
                </div>

                <div class="menu__title">
                    ศูนย์ดูแลลูกค้า
                    <i data-lucide="chevron-down"
                        class="menu__sub-icon {{ $supportMenuActive ? 'transform rotate-180' : '' }}"></i>
                </div>
            </a>

            <ul class="{{ $supportMenuActive ? 'menu__sub-open' : '' }}">
                @foreach ($adminSupportPages as $supportPageMenu)
                    @php
                        $supportPageSlug = $supportPageMenu->slug;

                        $isActiveSupportPage =
                            $supportMenuActive && (string) $currentSupportPageSlug === (string) $supportPageSlug;
                    @endphp

                    <li>
                        <a href="{{ route('admin.support-pages.edit', ['slug' => $supportPageSlug]) }}"
                            class="menu {{ $isActiveSupportPage ? 'menu--active' : '' }}">
                            <div class="menu__icon">
                                @if ($supportPageSlug === 'cancel-selling')
                                    <i data-lucide="x-circle"></i>
                                @elseif ($supportPageSlug === 'how-to-sell')
                                    <i data-lucide="shopping-bag"></i>
                                @elseif ($supportPageSlug === 'how-to-get-paid')
                                    <i data-lucide="wallet"></i>
                                @else
                                    <i data-lucide="file-text"></i>
                                @endif
                            </div>

                            <div class="menu__title">
                                {{ $supportPageMenu->menu_label ?: $supportPageMenu->page_title }}
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>

        @if ($can('admin_users', 'view'))
            <li class="menu__devider my-6"></li>

            <li>
                <a href="{{ url('/admin/user') }}"
                    class="menu {{ Request::is('admin/user') || Request::is('admin/user-add') || Request::is('admin/user/*/edit') ? 'menu--active' : '' }}">
                    <div class="menu__icon">
                        <i data-lucide="user"></i>
                    </div>
                    <div class="menu__title">
                        บัญชีผู้ใช้งาน
                    </div>
                </a>
            </li>
        @endif

        @if ($adminUser)
            <li class="menu__devider my-6"></li>

            <li>
                <form method="POST" action="{{ route('admin.logout') }}" class="m-0">
                    @csrf

                    <button type="submit" class="menu w-full text-left">
                        <div class="menu__icon">
                            <i data-lucide="log-out"></i>
                        </div>
                        <div class="menu__title">
                            ออกจากระบบ
                        </div>
                    </button>
                </form>
            </li>
        @endif
    </ul>
</div>
<!-- END: Mobile Menu -->
