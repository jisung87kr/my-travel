@props(['post'])

<article class="group bg-white rounded-2xl shadow-sm overflow-hidden hover:shadow-lg transition-all duration-300">
    <a href="{{ route('blog.show', $post->slug) }}" class="block">
        <div class="relative aspect-[16/10] overflow-hidden">
            @if($post->thumbnail_url)
                <img src="{{ $post->thumbnail_url }}"
                     alt="{{ $post->title }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
            @else
                <div class="w-full h-full bg-gradient-to-br from-pink-100 to-rose-100 flex items-center justify-center">
                    <svg class="w-16 h-16 text-pink-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
            @endif
            @if($post->category)
                <span class="absolute top-3 left-3 px-3 py-1 bg-white/90 backdrop-blur-sm rounded-full text-xs font-medium text-gray-700">
                    {{ $post->category->name }}
                </span>
            @endif
        </div>
        <div class="p-5">
            <h3 class="font-bold text-gray-900 text-lg line-clamp-2 group-hover:text-pink-600 transition-colors mb-2">
                {{ $post->title }}
            </h3>
            @if($post->excerpt)
                <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ $post->excerpt }}</p>
            @endif
            <div class="flex items-center justify-between text-sm text-gray-500">
                <div class="flex items-center gap-2">
                    <span>{{ $post->author->name }}</span>
                    <span>·</span>
                    <span>{{ $post->published_at?->format('Y.m.d') }}</span>
                </div>
                <div class="flex items-center gap-3">
                    <span class="flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ number_format($post->view_count) }}
                    </span>
                    <span class="flex items-center gap-1 text-pink-500">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/>
                        </svg>
                        {{ number_format($post->like_count) }}
                    </span>
                </div>
            </div>
        </div>
    </a>
</article>
