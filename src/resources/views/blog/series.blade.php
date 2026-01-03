<x-layouts.app :title="$series->title . ' - 블로그'">
    <div class="bg-gradient-to-b from-pink-50 to-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-12">
                <nav class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                    <a href="{{ route('blog.index') }}" class="hover:text-pink-600 transition-colors">블로그</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-gray-900">시리즈</span>
                </nav>

                <div class="bg-white rounded-2xl shadow-sm overflow-hidden">
                    @if($series->thumbnail_url)
                        <div class="aspect-video max-h-[300px] overflow-hidden">
                            <img src="{{ $series->thumbnail_url }}" alt="{{ $series->title }}" class="w-full h-full object-cover">
                        </div>
                    @endif
                    <div class="p-8">
                        <span class="inline-flex items-center gap-1 px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium mb-4">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                            </svg>
                            시리즈
                        </span>
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">{{ $series->title }}</h1>
                        @if($series->description)
                            <p class="text-gray-600">{{ $series->description }}</p>
                        @endif
                        <p class="text-gray-500 mt-4">총 {{ $series->posts->count() }}편</p>
                    </div>
                </div>
            </div>

            <!-- Posts List -->
            <div class="space-y-4">
                @foreach($series->posts as $index => $post)
                    <a href="{{ route('blog.show', $post->slug) }}"
                       class="flex items-center gap-6 p-6 bg-white rounded-2xl shadow-sm hover:shadow-lg transition-all group">
                        <div class="w-12 h-12 rounded-xl bg-pink-100 flex items-center justify-center text-pink-600 font-bold text-lg flex-shrink-0">
                            {{ $post->series_order ?? $index + 1 }}
                        </div>
                        @if($post->thumbnail_url)
                            <img src="{{ $post->thumbnail_url }}" alt="" class="w-24 h-16 rounded-lg object-cover flex-shrink-0">
                        @endif
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-gray-900 text-lg group-hover:text-pink-600 transition-colors line-clamp-1">
                                {{ $post->title }}
                            </h3>
                            @if($post->excerpt)
                                <p class="text-gray-500 text-sm line-clamp-1 mt-1">{{ $post->excerpt }}</p>
                            @endif
                            <div class="flex items-center gap-4 text-sm text-gray-400 mt-2">
                                <span>{{ $post->published_at?->format('Y.m.d') }}</span>
                                <span>{{ $post->reading_time }}분 읽기</span>
                            </div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-pink-600 transition-colors flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
            </div>

            @if($series->posts->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                    <p class="text-gray-500">아직 이 시리즈에 글이 없습니다</p>
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
