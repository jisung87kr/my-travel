<x-layouts.admin>
    <x-slot name="header">블로그 시리즈</x-slot>

    <!-- Sub Navigation -->
    <div class="mb-6 flex gap-2">
        <a href="{{ route('admin.blog.posts.index') }}"
           class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg font-medium transition-colors">
            포스트
        </a>
        <a href="{{ route('admin.blog.categories.index') }}"
           class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 rounded-lg font-medium transition-colors">
            카테고리
        </a>
        <a href="{{ route('admin.blog.series.index') }}"
           class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium">
            시리즈
        </a>
    </div>

    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <h2 class="text-lg font-semibold text-slate-800">시리즈 목록</h2>
        <a href="{{ route('admin.blog.series.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-600 to-emerald-700 text-white rounded-xl hover:from-emerald-700 hover:to-emerald-800 transition-all shadow-lg shadow-emerald-500/25 font-medium">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            새 시리즈
        </a>
    </div>

    <!-- Series Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($series as $s)
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden hover:shadow-lg transition-shadow">
                @if($s->thumbnail_url)
                    <div class="aspect-video">
                        <img src="{{ $s->thumbnail_url }}" alt="{{ $s->title }}" class="w-full h-full object-cover">
                    </div>
                @else
                    <div class="aspect-video bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center">
                        <svg class="w-16 h-16 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                @endif
                <div class="p-5">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="font-semibold text-slate-800">{{ $s->title }}</h3>
                        @if($s->is_active)
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">활성</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-slate-100 text-slate-600">비활성</span>
                        @endif
                    </div>
                    @if($s->description)
                        <p class="text-sm text-slate-500 line-clamp-2 mb-3">{{ $s->description }}</p>
                    @endif
                    <div class="flex items-center justify-between text-sm">
                        <span class="text-slate-500">
                            포스트 {{ $s->published_posts_count }}/{{ $s->posts_count }}
                        </span>
                        <div class="flex gap-1">
                            <a href="{{ route('admin.blog.series.show', $s) }}"
                               class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"
                               title="상세">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.blog.series.edit', $s) }}"
                               class="p-1.5 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors"
                               title="수정">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            @if($s->posts_count === 0)
                                <form method="POST" action="{{ route('admin.blog.series.destroy', $s) }}" class="inline"
                                      onsubmit="return confirm('정말 삭제하시겠습니까?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="p-1.5 text-slate-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors"
                                            title="삭제">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="bg-white rounded-2xl border border-slate-200/60 p-16 text-center">
                    <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                    </div>
                    <p class="text-slate-500 font-medium">등록된 시리즈가 없습니다.</p>
                </div>
            </div>
        @endforelse
    </div>
</x-layouts.admin>
