<x-layouts.admin>
    <x-slot name="header">시리즈 상세</x-slot>

    <!-- Header Actions -->
    <div class="mb-6 flex items-center justify-between">
        <a href="{{ route('admin.blog.series.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            목록으로
        </a>
        <div class="flex gap-2">
            <a href="{{ route('admin.blog.series.edit', $series) }}"
               class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                수정
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Series Info -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                @if($series->thumbnail_url)
                    <div class="aspect-video">
                        <img src="{{ $series->thumbnail_url }}" alt="{{ $series->title }}" class="w-full h-full object-cover">
                    </div>
                @endif
                <div class="p-5">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-lg font-semibold text-slate-800">{{ $series->title }}</h3>
                        @if($series->is_active)
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-emerald-100 text-emerald-700">활성</span>
                        @else
                            <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-slate-100 text-slate-600">비활성</span>
                        @endif
                    </div>
                    @if($series->description)
                        <p class="text-sm text-slate-500">{{ $series->description }}</p>
                    @endif
                    <dl class="mt-4 space-y-2 text-sm">
                        <div class="flex justify-between">
                            <dt class="text-slate-500">슬러그</dt>
                            <dd class="font-mono text-slate-800">{{ $series->slug }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">포스트 수</dt>
                            <dd class="text-slate-800">{{ $series->posts->count() }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">생성일</dt>
                            <dd class="text-slate-800">{{ $series->created_at->format('Y-m-d') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        <!-- Posts List -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                <h3 class="text-lg font-semibold text-slate-800 mb-4">포스트 목록</h3>
                @if($series->posts->count() > 0)
                    <div class="space-y-3">
                        @foreach($series->posts as $post)
                            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-xl">
                                <div class="w-10 h-10 rounded-lg bg-slate-200 flex items-center justify-center text-slate-600 font-semibold">
                                    {{ $post->series_order ?? '-' }}
                                </div>
                                @if($post->thumbnail_url)
                                    <img src="{{ $post->thumbnail_url }}" alt="" class="w-16 h-12 rounded-lg object-cover">
                                @else
                                    <div class="w-16 h-12 rounded-lg bg-slate-200 flex items-center justify-center">
                                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <a href="{{ route('admin.blog.posts.show', $post) }}" class="font-medium text-slate-800 hover:text-blue-600">
                                        {{ $post->title }}
                                    </a>
                                    <div class="flex items-center gap-2 text-sm text-slate-500">
                                        <span>{{ $post->author->name }}</span>
                                        <span>·</span>
                                        <span>{{ $post->created_at->format('Y-m-d') }}</span>
                                    </div>
                                </div>
                                @php $statusColor = $post->status->color(); @endphp
                                <span class="inline-flex items-center gap-1 px-2 py-0.5 text-xs font-medium rounded bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700">
                                    {{ $post->status->label() }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-12">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <p class="text-slate-500 font-medium">아직 포스트가 없습니다.</p>
                        <a href="{{ route('admin.blog.posts.create') }}" class="inline-flex items-center gap-2 mt-4 text-blue-600 hover:text-blue-700 font-medium">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                            </svg>
                            새 포스트 작성
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>
