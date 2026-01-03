@props(['categories', 'popularTags' => null, 'popularPosts' => null])

<aside class="space-y-6">
    <!-- Categories -->
    <div class="bg-white rounded-2xl shadow-sm p-6">
        <h3 class="font-bold text-gray-900 mb-4">카테고리</h3>
        <ul class="space-y-2">
            <li>
                <a href="{{ route('blog.index') }}"
                   class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50 transition-colors {{ !request()->route('slug') && request()->routeIs('blog.index') ? 'bg-pink-50 text-pink-600' : 'text-gray-700' }}">
                    <span>전체</span>
                </a>
            </li>
            @foreach($categories as $category)
                <li>
                    <a href="{{ route('blog.category', $category->slug) }}"
                       class="flex items-center justify-between py-2 px-3 rounded-lg hover:bg-gray-50 transition-colors {{ request()->route('slug') === $category->slug && request()->routeIs('blog.category') ? 'bg-pink-50 text-pink-600' : 'text-gray-700' }}">
                        <span>{{ $category->name }}</span>
                        <span class="text-sm text-gray-400">{{ $category->posts_count }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>

    <!-- Popular Tags -->
    @if($popularTags && $popularTags->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-4">인기 태그</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($popularTags as $tag)
                    <a href="{{ route('blog.tag', $tag->slug) }}"
                       class="px-3 py-1.5 bg-gray-100 hover:bg-pink-100 text-gray-700 hover:text-pink-600 rounded-full text-sm transition-colors">
                        #{{ $tag->name }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Popular Posts -->
    @if($popularPosts && $popularPosts->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <h3 class="font-bold text-gray-900 mb-4">인기 글</h3>
            <div class="space-y-4">
                @foreach($popularPosts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}" class="flex gap-3 group">
                        @if($post->thumbnail_url)
                            <img src="{{ $post->thumbnail_url }}" alt="" class="w-16 h-12 rounded-lg object-cover flex-shrink-0">
                        @else
                            <div class="w-16 h-12 rounded-lg bg-gray-100 flex-shrink-0 flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-gray-900 text-sm line-clamp-2 group-hover:text-pink-600 transition-colors">
                                {{ $post->title }}
                            </h4>
                            <p class="text-xs text-gray-500 mt-1">{{ number_format($post->view_count) }}회</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</aside>
