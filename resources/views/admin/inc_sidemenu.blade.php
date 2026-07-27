<!-- BEGIN: Side Menu -->
<nav class="side-nav">
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
            Request::is('admin/mobile-product-categories/*') ||
            Request::is('admin/grade-masters') ||
            Request::is('admin/grade-masters/*');

        $contentMenuActive =
            Request::is('admin/news') ||
            Request::is('admin/form-news') ||
            Request::is('admin/news/*') ||
            Request::is('admin/about-us-our-value') ||
            Request::routeIs('admin.about-page.*');
    @endphp

    <ul>
        @if ($can('dashboard', 'view'))
            <li>
                <a href="{{ url('/admin') }}"
                    class="side-menu {{ Request::is('admin') ? 'side-menu--active side-menu--open' : '' }}">
                    <div class="side-menu__icon"><i data-lucide="home"></i></div>
                    <div class="side-menu__title"> Homepage </div>
                </a>
            </li>
        @endif

        @if ($can('home_banner', 'view'))
            <li>
                <a href="{{ route('admin.home-banner.index') }}"
                    class="side-menu {{ request()->is('admin/home-banner*') ? 'side-menu--active side-menu--open' : '' }}">
                    <div class="side-menu__icon"><i data-lucide="image"></i></div>
                    <div class="side-menu__title"> Banner </div>
                </a>
            </li>
        @endif
        <li class="side-nav__devider my-6"></li>

        @if ($can('sale_detail', 'view'))
            <li>
                <a href="{{ url('admin/sale-detail') }}"
                    class="side-menu {{ Request::is('admin/sale-detail') ? 'side-menu--active side-menu--open' : '' }}">
                    <div class="side-menu__icon"><i data-lucide="clipboard-list"></i></div>
                    <div class="side-menu__title"> รายละเอียดการขาย </div>
                </a>
            </li>
        @endif
        @if ($can('parcel_setting', 'view'))
            <li>
                <a href="{{ route('admin.parcel-setting.edit') }}"
                    class="side-menu {{ Request::is('admin/parcel-setting') ? 'side-menu--active side-menu--open' : '' }}">
                    <div class="side-menu__icon">
                        <i data-lucide="package"></i>
                    </div>
                    <div class="side-menu__title"> ศูนย์รับพัสดุ </div>
                </a>
            </li>
        @endif
        @if ($can('branches', 'view'))
            <li>
                <a href="{{ route('admin.branches.index') }}"
                    class="side-menu {{ Request::is('admin/branches') || Request::is('admin/branches/*') ? 'side-menu--active side-menu--open' : '' }}">
                    <div class="side-menu__icon">
                        <i data-lucide="map-pin"></i>
                    </div>
                    <div class="side-menu__title"> สาขา </div>
                </a>
            </li>
        @endif
        @if ($can('transit_lines', 'view'))
            <li>
                <a href="{{ route('admin.transit-lines.index') }}"
                    class="side-menu {{ Request::is('admin/transit-lines') || Request::is('admin/transit-lines/*') ? 'side-menu--active side-menu--open' : '' }}">
                    <div class="side-menu__icon">
                        <i data-lucide="git-branch"></i>
                    </div>
                    <div class="side-menu__title"> สายรถไฟฟ้า </div>
                </a>
            </li>
        @endif

        @if ($can('transit_stations', 'view'))
            <li>
                <a href="{{ route('admin.transit-stations.index') }}"
                    class="side-menu {{ Request::is('admin/transit-stations') || Request::is('admin/transit-stations/*') ? 'side-menu--active side-menu--open' : '' }}">
                    <div class="side-menu__icon">
                        <i data-lucide="map-pin"></i>
                    </div>
                    <div class="side-menu__title"> สถานีรถไฟฟ้า </div>
                </a>
            </li>
        @endif
        <li class="side-nav__devider my-6"></li>

        @if ($can('bonus_codes', 'view'))
            <li>
                <a href="{{ url('admin/bonus-codes') }}"
                    class="side-menu {{ Request::is('admin/bonus-codes') || Request::is('admin/bonus-codes/create') || Request::is('admin/bonus-codes/*/edit') ? 'side-menu--active side-menu--open' : '' }}">
                    <div class="side-menu__icon">
                        <i data-lucide="ticket"></i>
                    </div>
                    <div class="side-menu__title"> โค้ดบวกราคา </div>
                </a>
            </li>
        @endif
        @if ($can('sell_orders', 'view'))
            <li>
                <a href="{{ url('admin/sell-orders') }}"
                    class="side-menu {{ Request::is('admin/sell-orders') || Request::is('admin/sell-orders/*') ? 'side-menu--active side-menu--open' : '' }}">
                    <div class="side-menu__icon">
                        <i data-lucide="clipboard-list"></i>
                    </div>
                    <div class="side-menu__title">
                        คำสั่งขายสินค้า
                    </div>
                </a>
            </li>
        @endif
        <li class="side-nav__devider my-6"></li>

        @if ($can('mobile_brands', 'view') || $can('grade_masters', 'view') || $can('product_categories', 'view'))
            <li>
                <a href="javascript:;"
                    class="side-menu {{ $productMenuActive ? 'side-menu--active side-menu--open' : '' }}">
                    <div class="side-menu__icon">
                        <i data-lucide="package-search"></i>
                    </div>
                    <div class="side-menu__title">
                        สินค้า
                        <div class="side-menu__sub-icon">
                            <i data-lucide="chevron-down"></i>
                        </div>
                    </div>
                </a>

                <ul class="{{ $productMenuActive ? 'side-menu__sub-open' : '' }}">
                    {{-- เมนูเดิม: แบรนด์สินค้า --}}
                    @if ($can('mobile_brands', 'view'))
                        <li>
                            <a href="{{ url('admin/mobile-brands') }}"
                                class="side-menu {{ Request::is('admin/mobile-brands') || Request::is('admin/mobile-brands/*') || Request::is('admin/mobile-product-categories/*') || Request::is('admin/mobile-models/*') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon">
                                    <i data-lucide="tag"></i>
                                </div>
                                <div class="side-menu__title"> แบรนด์สินค้า </div>
                            </a>
                        </li>
                    @endif

                    {{-- เมนูที่เพิ่มใหม่: ประเภทสินค้า --}}
                    @if ($can('product_categories', 'view'))
                        <li>
                            <a href="{{ url('admin/mobile-product-categories') }}"
                                class="side-menu {{ Request::is('admin/product-categories') || Request::is('admin/product-categories/*') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon">
                                    <i data-lucide="list"></i>
                                </div>
                                <div class="side-menu__title"> ประเภทสินค้า </div>
                            </a>
                        </li>
                    @endif

                    {{-- เมนูเดิม: เกรดพื้นฐาน --}}
                    @if ($can('grade_masters', 'view'))
                        <li>
                            <a href="{{ url('admin/grade-masters') }}"
                                class="side-menu {{ Request::is('admin/grade-masters') || Request::is('admin/grade-masters/create') || Request::is('admin/grade-masters/*/edit') ? 'side-menu--active' : '' }}">
                                <div class="side-menu__icon">
                                    <i data-lucide="layers"></i>
                                </div>
                                <div class="side-menu__title"> เกรดพื้นฐาน </div>
                            </a>
                        </li>
                    @endif
                </ul>
            </li>
        @endif
        @if ($can('product_grade_questions', 'view'))
            <li>
                <a href="{{ url('admin/product-grade-questions/by-category') }}"
                    class="side-menu {{ Request::is('admin/product-grade-questions*') ? 'side-menu--active side-menu--open' : '' }}">
                    <div class="side-menu__icon">
                        <i data-lucide="help-circle"></i>
                    </div>
                    <div class="side-menu__title"> ชุดคำถามคัดเกรด </div>
                </a>
            </li>
        @endif



        @if ($can('product_questions', 'view'))
            <li>
                <a href="{{ url('admin/product-questions') }}"
                    class="side-menu {{ Request::is('admin/product-questions') || Request::is('admin/product-questions/*') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon">
                        <i data-lucide="help-circle"></i>
                    </div>
                    <div class="side-menu__title"> คำถามที่พบบ่อย </div>
                </a>
            </li>
        @endif
        @if ($can('news', 'view'))
            <li>
                <a href="{{ url('admin/news') }}"
                    class="side-menu {{ Request::is('admin/news') || Request::is('admin/form-news') || Request::is('admin/news/*') ? 'side-menu--active side-menu--open' : '' }}">
                    <div class="side-menu__icon"><i data-lucide="file-text"></i></div>
                    <div class="side-menu__title"> บทความ </div>
                </a>
            </li>
        @endif

        {{-- @if ($can('promotion', 'view'))
            <li>
                <a href="{{ url('admin/about-us-our-value') }}"
                    class="side-menu {{ Request::is('admin/about-us-our-value') ? 'side-menu--active side-menu--open' : '' }}">
                    <div class="side-menu__icon"><i data-lucide="tag"></i></div>
                    <div class="side-menu__title"> โปรโมชั่น </div>
                </a>
            </li>
        @endif --}}

        @if ($can('about_page', 'view'))
            <li class="side-nav__devider my-6"></li>
            <li>
                <a href="{{ route('admin.about-page.edit') }}"
                    class="side-menu {{ Request::routeIs('admin.about-page.*') ? 'side-menu--active' : '' }}">
                    <div class="side-menu__icon"><i data-lucide="file-text"></i></div>
                    <div class="side-menu__title"> จัดการหน้าเกี่ยวกับเรา </div>
                </a>
            </li>
        @endif
        <li>
            <a href="javascript:;"
                class="side-menu {{ request()->is('admin/support-pages*') ? 'side-menu--active side-menu--open' : '' }}">
                <div class="side-menu__icon">
                    <i data-lucide="headphones"></i>
                </div>

                <div class="side-menu__title">
                    ศูนย์ดูแลลูกค้า
                    <div
                        class="side-menu__sub-icon {{ request()->is('admin/support-pages*') ? 'transform rotate-180' : '' }}">
                        <i data-lucide="chevron-down"></i>
                    </div>
                </div>
            </a>

            <ul class="{{ request()->is('admin/support-pages*') ? 'side-menu__sub-open' : '' }}">
                @php
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

                @foreach ($adminSupportPages as $supportPageMenu)
                    @php
                        $supportPageSlug = $supportPageMenu->slug;

                        $isActiveSupportPage =
                            request()->is('admin/support-pages*') &&
                            (string) $currentSupportPageSlug === (string) $supportPageSlug;
                    @endphp

                    <li>
                        <a href="{{ route('admin.support-pages.edit', ['slug' => $supportPageSlug]) }}"
                            class="side-menu {{ $isActiveSupportPage ? 'side-menu--active' : '' }}">
                            <div class="side-menu__icon">
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

                            <div class="side-menu__title">
                                {{ $supportPageMenu->menu_label ?: $supportPageMenu->page_title }}
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>

        @if ($can('admin_users', 'view'))
            <li class="side-nav__devider my-6"></li>
            <li>
                <a href="{{ url('/admin/user') }}"
                    class="side-menu {{ Request::is('admin/user') || Request::is('admin/user-add') || Request::is('admin/user/*/edit') ? 'side-menu--active side-menu--open' : '' }}">
                    <div class="side-menu__icon">
                        <i data-lucide="user"></i>
                    </div>
                    <div class="side-menu__title"> บัญชีผู้ใช้งาน </div>
                </a>
            </li>
        @endif

        @if ($adminUser)
            <li class="side-nav__devider my-6"></li>
            <li>
                <form method="POST" action="{{ route('admin.logout') }}" class="m-0">
                    @csrf
                    <button type="submit" class="side-menu w-full text-left">
                        <div class="side-menu__icon">
                            <i data-lucide="log-out"></i>
                        </div>
                        <div class="side-menu__title"> ออกจากระบบ </div>
                    </button>
                </form>
            </li>
        @endif
    </ul>
</nav>
<!-- END: Side Menu -->
