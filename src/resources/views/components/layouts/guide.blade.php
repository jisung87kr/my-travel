<x-layouts.base-dashboard
    :title="$title ?? '가이드 대시보드'"
    theme="teal"
    dashboard-route="guide.dashboard"
    role-label="Guide"
    role-sub-label="가이드"
>
    <x-slot:logoIcon>
        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
    </x-slot:logoIcon>

    <x-slot:navigation>
        <div class="space-y-1">
            <p class="px-3 mb-2 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">메인</p>

            <x-layouts.partials.nav-item
                route="guide.dashboard"
                label="대시보드"
                theme="teal"
            >
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM14 5a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1V5zM4 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1H5a1 1 0 01-1-1v-4zM14 15a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"/>
                </x-slot:icon>
            </x-layouts.partials.nav-item>
        </div>

        <div class="mt-8 space-y-1">
            <p class="px-3 mb-2 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">일정</p>

            <x-layouts.partials.nav-item
                route="guide.schedules.index"
                active-pattern="guide.schedules.*"
                label="일정 관리"
                theme="teal"
            >
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                </x-slot:icon>
            </x-layouts.partials.nav-item>

            <x-layouts.partials.nav-item
                route="guide.checkin.index"
                active-pattern="guide.checkin.*"
                label="QR 체크인"
                theme="teal"
            >
                <x-slot:icon>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z"/>
                </x-slot:icon>
            </x-layouts.partials.nav-item>
        </div>
    </x-slot:navigation>

    <x-slot:headerActions>
        <span class="hidden sm:inline-flex items-center gap-2 px-3 py-1.5 text-xs font-medium text-teal-700 bg-teal-50 rounded-full">
            <span class="w-2 h-2 rounded-full bg-teal-500 animate-pulse"></span>
            가이드 모드
        </span>
    </x-slot:headerActions>

    {{ $slot }}
</x-layouts.base-dashboard>
