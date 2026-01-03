<x-layouts.app :title="$post->meta_title ?? $post->title">
    @push('head')
        @if($post->meta_description)
            <meta name="description" content="{{ $post->meta_description }}">
        @endif
        <meta property="og:title" content="{{ $post->meta_title ?? $post->title }}">
        <meta property="og:description" content="{{ $post->meta_description ?? $post->excerpt }}">
        @if($post->thumbnail_url)
            <meta property="og:image" content="{{ $post->thumbnail_url }}">
        @endif
    @endpush

    <article class="">
        <!-- Hero -->
        <div class="relative">
            @if($post->thumbnail_url)
                <div class="max-h-[400px] overflow-hidden">
                    <img src="{{ $post->thumbnail_url }}" alt="{{ $post->title }}" class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-black/20 to-transparent"></div>
                </div>
            @else
                <div class="h-32 bg-gradient-to-r from-pink-500 to-rose-500"></div>
            @endif
        </div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 -mt-16 relative z-10">
            <!-- Header Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8 mb-8">
                <div class="flex flex-wrap items-center gap-2 mb-4">
                    @if($post->category)
                        <a href="{{ route('blog.category', $post->category->slug) }}"
                           class="px-3 py-1 bg-pink-100 text-pink-700 rounded-full text-sm font-medium hover:bg-pink-200 transition-colors">
                            {{ $post->category->name }}
                        </a>
                    @endif
                    @if($post->series)
                        <a href="{{ route('blog.series', $post->series->slug) }}"
                           class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium hover:bg-blue-200 transition-colors">
                            {{ $post->series->title }}
                        </a>
                    @endif
                </div>

                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $post->title }}</h1>

                <div class="flex flex-wrap items-center gap-4 text-gray-500">
                    <div class="flex items-center gap-2">
                        <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center">
                            <span class="text-pink-600 font-medium">{{ mb_substr($post->author->name, 0, 1) }}</span>
                        </div>
                        <span class="font-medium text-gray-900">{{ $post->author->name }}</span>
                    </div>
                    <span>·</span>
                    <span>{{ $post->published_at?->format('Y년 m월 d일') }}</span>
                    <span>·</span>
                    <span>{{ $post->reading_time }}분 읽기</span>
                </div>

                <!-- Stats & Actions -->
                <div class="flex items-center justify-between mt-6 pt-6 border-t border-gray-100">
                    <div class="flex items-center gap-4 text-gray-500">
                        <span class="flex items-center gap-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            {{ number_format($post->view_count) }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                            </svg>
                            {{ number_format($post->comment_count) }}
                        </span>
                    </div>

                    @auth
                        <button type="button" id="likeBtn"
                                class="flex items-center gap-2 px-4 py-2 rounded-full transition-colors {{ $post->isLikedBy(auth()->user()) ? 'bg-pink-100 text-pink-600' : 'bg-gray-100 text-gray-600 hover:bg-pink-50 hover:text-pink-500' }}"
                                data-post-id="{{ $post->id }}"
                                data-liked="{{ $post->isLikedBy(auth()->user()) ? 'true' : 'false' }}">
                            <svg class="w-5 h-5" fill="{{ $post->isLikedBy(auth()->user()) ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span id="likeCount">{{ number_format($post->like_count) }}</span>
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="flex items-center gap-2 px-4 py-2 rounded-full bg-gray-100 text-gray-600 hover:bg-pink-50 hover:text-pink-500 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span>{{ number_format($post->like_count) }}</span>
                        </a>
                    @endauth
                </div>
            </div>

            <!-- Content -->
            <div class="bg-white rounded-2xl shadow-sm p-8 mb-8">
                <div class="prose prose-lg prose-pink max-w-none">
                    {!! $post->content !!}
                </div>

                <!-- Tags -->
                @if($post->tags->count() > 0)
                    <div class="flex flex-wrap gap-2 mt-8 pt-8 border-t border-gray-100">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('blog.tag', $tag->slug) }}"
                               class="px-3 py-1.5 bg-gray-100 hover:bg-pink-100 text-gray-700 hover:text-pink-600 rounded-full text-sm transition-colors">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Series Navigation -->
            @if($post->series && ($previousPost || $nextPost))
                <div class="bg-white rounded-2xl shadow-sm p-6 mb-8">
                    <div class="text-sm text-gray-500 mb-4">
                        <a href="{{ route('blog.series', $post->series->slug) }}" class="hover:text-pink-600 transition-colors">
                            {{ $post->series->title }}
                        </a>
                        시리즈
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($previousPost)
                            <a href="{{ route('blog.show', $previousPost->slug) }}" class="p-4 border border-gray-200 rounded-xl hover:border-pink-300 hover:bg-pink-50 transition-colors group">
                                <span class="text-xs text-gray-400">이전 글</span>
                                <p class="font-medium text-gray-900 group-hover:text-pink-600 transition-colors line-clamp-1">{{ $previousPost->title }}</p>
                            </a>
                        @else
                            <div></div>
                        @endif
                        @if($nextPost)
                            <a href="{{ route('blog.show', $nextPost->slug) }}" class="p-4 border border-gray-200 rounded-xl hover:border-pink-300 hover:bg-pink-50 transition-colors group text-right">
                                <span class="text-xs text-gray-400">다음 글</span>
                                <p class="font-medium text-gray-900 group-hover:text-pink-600 transition-colors line-clamp-1">{{ $nextPost->title }}</p>
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Comments Section -->
            <div class="bg-white rounded-2xl shadow-sm p-8 mb-8" id="comments">
                <h3 class="text-xl font-bold text-gray-900 mb-6">댓글 {{ $post->comment_count > 0 ? $post->comment_count : '' }}</h3>

                @auth
                    <form id="commentForm" class="mb-8">
                        <textarea id="commentContent" rows="3"
                                  class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500 resize-none"
                                  placeholder="댓글을 작성해주세요"></textarea>
                        <div class="flex justify-end mt-2">
                            <button type="submit" class="px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors font-medium">
                                댓글 작성
                            </button>
                        </div>
                    </form>
                @else
                    <div class="text-center py-6 bg-gray-50 rounded-xl mb-8">
                        <p class="text-gray-600 mb-4">댓글을 작성하려면 로그인이 필요합니다</p>
                        <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-6 py-2 bg-pink-600 text-white rounded-lg hover:bg-pink-700 transition-colors font-medium">
                            로그인하기
                        </a>
                    </div>
                @endauth

                <div id="commentsList" class="space-y-6">
                    @foreach($post->visibleComments as $comment)
                        <div class="flex gap-4" data-comment-id="{{ $comment->id }}">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center flex-shrink-0">
                                <span class="text-gray-600 font-medium">{{ mb_substr($comment->user->name, 0, 1) }}</span>
                            </div>
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-medium text-gray-900">{{ $comment->user->name }}</span>
                                    <span class="text-xs text-gray-400">{{ $comment->created_at->diffForHumans() }}</span>
                                    @if(auth()->id() === $comment->user_id || auth()->user()?->hasRole('admin'))
                                        <button type="button" class="delete-comment text-xs text-red-500 hover:text-red-700" data-id="{{ $comment->id }}">삭제</button>
                                    @endif
                                </div>
                                <p class="text-gray-700">{{ $comment->content }}</p>

                                @if($comment->replies->count() > 0)
                                    <div class="mt-4 ml-4 space-y-4">
                                        @foreach($comment->replies as $reply)
                                            <div class="flex gap-3" data-comment-id="{{ $reply->id }}">
                                                <div class="w-8 h-8 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                                                    <span class="text-gray-500 text-sm font-medium">{{ mb_substr($reply->user->name, 0, 1) }}</span>
                                                </div>
                                                <div class="flex-1">
                                                    <div class="flex items-center gap-2 mb-1">
                                                        <span class="font-medium text-gray-900 text-sm">{{ $reply->user->name }}</span>
                                                        <span class="text-xs text-gray-400">{{ $reply->created_at->diffForHumans() }}</span>
                                                        @if(auth()->id() === $reply->user_id || auth()->user()?->hasRole('admin'))
                                                            <button type="button" class="delete-comment text-xs text-red-500 hover:text-red-700" data-id="{{ $reply->id }}">삭제</button>
                                                        @endif
                                                    </div>
                                                    <p class="text-gray-700 text-sm">{{ $reply->content }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Related Posts -->
            @if($relatedPosts->count() > 0)
                <div class="mb-12">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">관련 글</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($relatedPosts as $relatedPost)
                            @include('blog.partials.post-card', ['post' => $relatedPost])
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </article>

    @push('scripts')
    <script>
        // Like Toggle
        document.getElementById('likeBtn')?.addEventListener('click', async function() {
            const btn = this;
            const postId = btn.dataset.postId;

            try {
                const response = await fetch(`/api/blog/${postId}/like`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                });
                const data = await response.json();

                if (data.success) {
                    document.getElementById('likeCount').textContent = data.like_count.toLocaleString();
                    if (data.liked) {
                        btn.classList.remove('bg-gray-100', 'text-gray-600');
                        btn.classList.add('bg-pink-100', 'text-pink-600');
                        btn.querySelector('svg').setAttribute('fill', 'currentColor');
                    } else {
                        btn.classList.remove('bg-pink-100', 'text-pink-600');
                        btn.classList.add('bg-gray-100', 'text-gray-600');
                        btn.querySelector('svg').setAttribute('fill', 'none');
                    }
                }
            } catch (error) {
                console.error('Like error:', error);
            }
        });

        // Comment Submit
        document.getElementById('commentForm')?.addEventListener('submit', async function(e) {
            e.preventDefault();
            const content = document.getElementById('commentContent').value.trim();
            if (!content) return;

            try {
                const response = await fetch('/api/blog/{{ $post->id }}/comments', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({ content }),
                });
                const data = await response.json();

                if (data.success) {
                    location.reload();
                }
            } catch (error) {
                console.error('Comment error:', error);
            }
        });

        // Delete Comment
        document.querySelectorAll('.delete-comment').forEach(btn => {
            btn.addEventListener('click', async function() {
                if (!confirm('댓글을 삭제하시겠습니까?')) return;

                const commentId = this.dataset.id;
                try {
                    const response = await fetch(`/api/blog/comments/${commentId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        },
                    });
                    const data = await response.json();

                    if (data.success) {
                        location.reload();
                    }
                } catch (error) {
                    console.error('Delete error:', error);
                }
            });
        });
    </script>
    @endpush
</x-layouts.app>
