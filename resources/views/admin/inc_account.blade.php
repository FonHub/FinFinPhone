{{-- @if (Session()->has('login'))
    @php
        $id = Session('login');
        $userp = DB::table('users')->where('id', $id)->first();
    @endphp
@endif --}}
<div class="intro-x dropdown w-8 h-8">
    <div class="dropdown-toggle w-8 h-8 rounded-full overflow-hidden shadow-lg image-fit zoom-in scale-110" role="button"
        aria-expanded="false" data-tw-toggle="dropdown">
        <img alt="Midone - HTML Admin Template" src="{{ asset('dist/images/profile-13.jpg') }}">
    </div>
    <div class="dropdown-menu w-56">
        <ul
            class="dropdown-content bg-primary/80 before:block before:absolute before:bg-black before:inset-0 before:rounded-md before:z-[-1] text-white">
            <li class="p-2">
                <div class="font-medium">Head Admin</div>
                <div class="text-xs text-white/60 mt-0.5 dark:text-slate-500">admin test
                </div>
            </li>
            @if (session('login'))
                <li>
                    <a href="{{ url('admin/profile/' . session('login')) }}" class="dropdown-item hover:bg-white/5"> <i
                            data-lucide="user" class="w-4 h-4 mr-2"></i> Profile </a>
                </li>
            @endif
            <li>
                <hr class="dropdown-divider border-white/[0.08]">
            </li>
            <li>
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item hover:bg-white/5 w-full text-left">
                        <i data-lucide="toggle-right" class="w-4 h-4 mr-2"></i>
                        Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
