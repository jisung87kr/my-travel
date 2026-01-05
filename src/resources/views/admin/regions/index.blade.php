<x-layouts.admin>
    <x-slot name="header">지역 관리</x-slot>

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-4">
            <h2 class="text-lg font-semibold text-slate-800">지역 목록</h2>
            <!-- Level Filter -->
            <div class="flex gap-2">
                <a href="{{ route('admin.regions.index') }}"
                   class="px-3 py-1.5 text-sm rounded-lg {{ !request('level') ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} transition-colors">
                    시/도
                </a>
                <a href="{{ route('admin.regions.index', ['level' => 3]) }}"
                   class="px-3 py-1.5 text-sm rounded-lg {{ request('level') == 3 ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} transition-colors">
                    시/군/구
                </a>
                <a href="{{ route('admin.regions.index', ['level' => 1]) }}"
                   class="px-3 py-1.5 text-sm rounded-lg {{ request('level') == 1 ? 'bg-blue-600 text-white' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }} transition-colors">
                    국가
                </a>
            </div>
        </div>
        <a href="{{ route('admin.regions.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all shadow-lg shadow-emerald-500/25 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            새 지역
        </a>
    </div>

    <!-- Filters -->
    <div class="mb-6 bg-white rounded-2xl border border-slate-200/60 p-4">
        <form method="GET" action="{{ route('admin.regions.index') }}" class="flex flex-wrap gap-4 items-end">
            @if(request('level'))
                <input type="hidden" name="level" value="{{ request('level') }}">
            @endif

            @if(request('level') == 3)
            <div class="min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 mb-1">상위 지역</label>
                <select name="parent_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500">
                    <option value="">전체</option>
                    @foreach($provinces as $province)
                        <option value="{{ $province->id }}" {{ request('parent_id') == $province->id ? 'selected' : '' }}>
                            {{ $province->getName('ko') }}
                        </option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="min-w-[200px]">
                <label class="block text-sm font-medium text-slate-700 mb-1">검색</label>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-lg focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500"
                       placeholder="코드 또는 이름">
            </div>

            <div class="flex gap-2">
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    검색
                </button>
                <a href="{{ route('admin.regions.index', request('level') ? ['level' => request('level')] : []) }}"
                   class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors">
                    초기화
                </a>
            </div>
        </form>
    </div>

    <!-- Regions Table -->
    <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200/60">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">순서</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">코드</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">이름</th>
                        @if(request('level') == 3)
                        <th class="px-6 py-4 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">상위 지역</th>
                        @endif
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">상품 수</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">하위 지역</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">상태</th>
                        <th class="px-6 py-4 text-center text-xs font-semibold text-slate-600 uppercase tracking-wider">추천</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-slate-600 uppercase tracking-wider">관리</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($regions as $region)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $region->sort_order }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-mono">{{ $region->code }}</td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">{{ $region->getName('ko') }}</div>
                                @if($region->getTranslation('en')?->name)
                                    <div class="text-sm text-slate-500">{{ $region->getName('en') }}</div>
                                @endif
                            </td>
                            @if(request('level') == 3)
                            <td class="px-6 py-4 text-sm text-slate-600">
                                {{ $region->parent?->getName('ko') ?? '-' }}
                            </td>
                            @endif
                            <td class="px-6 py-4 text-center text-sm text-slate-600">{{ $region->products_count }}</td>
                            <td class="px-6 py-4 text-center text-sm text-slate-600">
                                @if($region->children_count > 0)
                                    <a href="{{ route('admin.regions.index', ['level' => $region->level + 1, 'parent_id' => $region->id]) }}"
                                       class="text-blue-600 hover:text-blue-700 hover:underline">
                                        {{ $region->children_count }}
                                    </a>
                                @else
                                    0
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form method="POST" action="{{ route('admin.regions.toggle-active', $region) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg transition-colors {{ $region->is_active ? 'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $region->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                        {{ $region->is_active ? '활성' : '비활성' }}
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <form method="POST" action="{{ route('admin.regions.toggle-featured', $region) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="inline-flex items-center gap-1 px-2 py-1 text-xs font-medium rounded-lg transition-colors {{ $region->is_featured ? 'bg-amber-100 text-amber-700 hover:bg-amber-200' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                        <svg class="w-3.5 h-3.5" fill="{{ $region->is_featured ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                        </svg>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-1">
                                    <a href="{{ route('admin.regions.show', $region) }}"
                                       class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                                       title="상세">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                    <a href="{{ route('admin.regions.edit', $region) }}"
                                       class="p-2 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                                       title="수정">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    @if($region->products_count === 0 && $region->children_count === 0)
                                        <form method="POST" action="{{ route('admin.regions.destroy', $region) }}" class="inline"
                                              onsubmit="return confirm('정말 삭제하시겠습니까?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                                    title="삭제">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ request('level') == 3 ? '9' : '8' }}" class="px-6 py-16 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mb-4">
                                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </div>
                                    <p class="text-slate-500 font-medium">등록된 지역이 없습니다.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($regions->hasPages())
            <div class="px-6 py-4 border-t border-slate-200/60">
                {{ $regions->links() }}
            </div>
        @endif
    </div>
</x-layouts.admin>
