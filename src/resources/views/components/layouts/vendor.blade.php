<x-layouts.base-dashboard
    :title="$title ?? '제공자 대시보드'"
    theme="violet"
    dashboard-route="vendor.dashboard"
    role-label="Vendor"
    :role-sub-label="auth()->user()->vendor?->business_name ?? '제공자'"
>
    <x-slot:logoIcon>
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z"/>
        </svg>
    </x-slot:logoIcon>

    <x-slot:navigation>
        <div class="space-y-1">
            <p class="px-3 mb-2 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">메인</p>

            <x-layouts.partials.nav-item
                route="vendor.dashboard"
                label="대시보드"
                theme="violet"
            >
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                </x-slot:icon>
            </x-layouts.partials.nav-item>
        </div>

        <div class="mt-8 space-y-1">
            <p class="px-3 mb-2 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">관리</p>

            <x-layouts.partials.nav-item
                route="vendor.products.index"
                active-pattern="vendor.products.*"
                label="상품 관리"
                theme="violet"
            >
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9"/>
                </x-slot:icon>
            </x-layouts.partials.nav-item>

            <x-layouts.partials.nav-item
                route="vendor.bookings.index"
                active-pattern="vendor.bookings.*"
                label="예약 관리"
                theme="violet"
                :badge="isset($pendingBookingsCount) && $pendingBookingsCount > 0 ? $pendingBookingsCount : null"
            >
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </x-slot:icon>
            </x-layouts.partials.nav-item>

            <x-layouts.partials.nav-item
                route="vendor.schedules.index"
                active-pattern="vendor.schedules.*"
                label="일정 관리"
                theme="violet"
            >
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                </x-slot:icon>
            </x-layouts.partials.nav-item>
        </div>
    </x-slot:navigation>

    <x-slot:headerActions>
        <a href="{{ route('home') }}"
           class="hidden sm:flex items-center gap-2 px-4 py-2 text-sm font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
            </svg>
            사이트 보기
        </a>
    </x-slot:headerActions>

    {{ $slot }}
</x-layouts.base-dashboard>
