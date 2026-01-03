<x-layouts.admin>
    <x-slot name="header">포스트 상세</x-slot>

    <div class="max-w-5xl">
        <!-- Header Actions -->
        <div class="mb-6 flex items-center justify-between">
            <a href="{{ route('admin.blog.posts.index') }}" class="inline-flex items-center gap-2 text-slate-600 hover:text-slate-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                목록으로
            </a>
            <div class="flex gap-2">
                @if($post->isDraft())
                    <form method="POST" action="{{ route('admin.blog.posts.publish', $post) }}" class="inline">
                        @csrf
                        @method('PATCH')
                        <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition-colors font-medium">
                            발행하기
                        </button>
                    </form>
                @elseif($post->isPublished())
                    <a href="{{ route('blog.show', $post->slug) }}" target="_blank"
                       class="px-4 py-2 bg-slate-100 text-slate-700 rounded-lg hover:bg-slate-200 transition-colors font-medium">
                        사이트에서 보기
                    </a>
                @endif
                <a href="{{ route('admin.blog.posts.edit', $post) }}"
                   class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                    수정
                </a>
                <form method="POST" action="{{ route('admin.blog.posts.destroy', $post) }}" class="inline"
                      onsubmit="return confirm('정말 삭제하시겠습니까?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium">
                        삭제
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Post Header -->
                <div class="bg-white rounded-2xl border border-slate-200/60 overflow-hidden">
                    @if($post->thumbnail_url)
                        <div class="aspect-video">
                            <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="p-6">
                        <div class="flex items-center gap-2 mb-4">
                            @php $statusColor = $post->status->color(); @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-{{ $statusColor }}-100 text-{{ $statusColor }}-700">
                                {{ $post->status->label() }}
                            </span>
                            @if($post->category)
                                <span class="px-2.5 py-1 text-xs font-medium rounded-lg bg-slate-100 text-slate-700">
                                    {{ $post->category->name }}
                                </span>
                            @endif
                        </div>
                        <h1 class="text-2xl font-bold text-slate-800 mb-4">{{ $post->title }}</h1>
                        <div class="flex items-center gap-4 text-sm text-slate-500">
                            <span>{{ $post->author->name }}</span>
                            <span>·</span>
                            <span>{{ $post->created_at->format('Y년 m월 d일') }}</span>
                            @if($post->published_at)
                                <span>·</span>
                                <span>발행: {{ $post->published_at->format('Y-m-d H:i') }}</span>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Excerpt -->
                @if($post->excerpt)
                    <div class="bg-slate-50 rounded-2xl border border-slate-200/60 p-6">
                        <h3 class="text-sm font-semibold text-slate-600 mb-2">요약</h3>
                        <p class="text-slate-700">{{ $post->excerpt }}</p>
                    </div>
                @endif

                <!-- Content -->
                <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">내용</h3>
                    <div class="prose prose-slate max-w-none">
                        {!! $post->content !!}
                    </div>
                </div>

                <!-- Comments -->
                @if($post->comments->count() > 0)
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">댓글 ({{ $post->comments->count() }})</h3>
                        <div class="space-y-4">
                            @foreach($post->comments->whereNull('parent_id') as $comment)
                                <div class="border-b border-slate-100 pb-4 last:border-0 last:pb-0">
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center">
                                            <span class="text-sm font-medium text-slate-600">{{ mb_substr($comment->user->name, 0, 1) }}</span>
                                        </div>
                                        <div class="flex-1">
                                            <div class="flex items-center gap-2">
                                                <span class="font-medium text-slate-800">{{ $comment->user->name }}</span>
                                                <span class="text-xs text-slate-400">{{ $comment->created_at->diffForHumans() }}</span>
                                            </div>
                                            <p class="mt-1 text-slate-600">{{ $comment->content }}</p>
                                            @if($comment->replies->count() > 0)
                                                <div class="mt-3 ml-4 space-y-3">
                                                    @foreach($comment->replies as $reply)
                                                        <div class="flex items-start gap-3">
                                                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center">
                                                                <span class="text-xs font-medium text-slate-500">{{ mb_substr($reply->user->name, 0, 1) }}</span>
                                                            </div>
                                                            <div>
                                                                <div class="flex items-center gap-2">
                                                                    <span class="font-medium text-slate-700 text-sm">{{ $reply->user->name }}</span>
                                                                    <span class="text-xs text-slate-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                                </div>
                                                                <p class="text-sm text-slate-600">{{ $reply->content }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <!-- Stats -->
                <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">통계</h3>
                    <div class="grid grid-cols-3 gap-4 text-center">
                        <div>
                            <p class="text-2xl font-bold text-slate-800">{{ number_format($post->view_count) }}</p>
                            <p class="text-sm text-slate-500">조회수</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-pink-600">{{ number_format($post->like_count) }}</p>
                            <p class="text-sm text-slate-500">좋아요</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-slate-800">{{ number_format($post->comment_count) }}</p>
                            <p class="text-sm text-slate-500">댓글</p>
                        </div>
                    </div>
                </div>

                <!-- Info -->
                <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                    <h3 class="text-lg font-semibold text-slate-800 mb-4">정보</h3>
                    <dl class="space-y-3">
                        <div class="flex justify-between">
                            <dt class="text-slate-500">작성자</dt>
                            <dd class="font-medium text-slate-800">{{ $post->author->name }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">카테고리</dt>
                            <dd class="font-medium text-slate-800">{{ $post->category?->name ?? '-' }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">시리즈</dt>
                            <dd class="font-medium text-slate-800">{{ $post->series?->title ?? '-' }}</dd>
                        </div>
                        @if($post->series_order)
                            <div class="flex justify-between">
                                <dt class="text-slate-500">시리즈 순서</dt>
                                <dd class="font-medium text-slate-800">{{ $post->series_order }}번째</dd>
                            </div>
                        @endif
                        <div class="flex justify-between">
                            <dt class="text-slate-500">작성일</dt>
                            <dd class="font-medium text-slate-800">{{ $post->created_at->format('Y-m-d H:i') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">수정일</dt>
                            <dd class="font-medium text-slate-800">{{ $post->updated_at->format('Y-m-d H:i') }}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-slate-500">슬러그</dt>
                            <dd class="font-medium text-slate-800 text-sm truncate max-w-[150px]">{{ $post->slug }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Tags -->
                @if($post->tags->count() > 0)
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">태그</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($post->tags as $tag)
                                <span class="px-3 py-1.5 bg-blue-50 text-blue-700 rounded-lg text-sm font-medium">
                                    #{{ $tag->name }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- SEO -->
                @if($post->meta_title || $post->meta_description)
                    <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
                        <h3 class="text-lg font-semibold text-slate-800 mb-4">SEO</h3>
                        <dl class="space-y-3">
                            @if($post->meta_title)
                                <div>
                                    <dt class="text-sm text-slate-500 mb-1">제목</dt>
                                    <dd class="text-slate-800">{{ $post->meta_title }}</dd>
                                </div>
                            @endif
                            @if($post->meta_description)
                                <div>
                                    <dt class="text-sm text-slate-500 mb-1">설명</dt>
                                    <dd class="text-slate-800 text-sm">{{ $post->meta_description }}</dd>
                                </div>
                            @endif
                        </dl>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.admin>
