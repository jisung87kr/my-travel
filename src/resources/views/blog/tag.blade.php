<x-layouts.app :title="'#' . $tag->name . ' - 블로그'">
    <div class="bg-gradient-to-b from-pink-50 to-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-12">
                <nav class="flex items-center gap-2 text-sm text-gray-500 mb-4">
                    <a href="{{ route('blog.index') }}" class="hover:text-pink-600 transition-colors">블로그</a>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                    <span class="text-gray-900">태그</span>
                </nav>
                <div class="flex items-center gap-3">
                    <span class="text-3xl md:text-4xl font-bold text-pink-600">#{{ $tag->name }}</span>
                    <span class="text-gray-500">{{ $posts->total() }}개의 글</span>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-8">
                <!-- Main Content -->
                <div class="flex-1">
                    @if($posts->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach($posts as $post)
                                @include('blog.partials.post-card', ['post' => $post])
                            @endforeach
                        </div>

                        @if($posts->hasPages())
                            <div class="mt-8">
                                {{ $posts->links() }}
                            </div>
                        @endif
                    @else
                        <div class="bg-white rounded-2xl shadow-sm p-12 text-center">
                            <div class="w-20 h-20 mx-auto mb-6 rounded-2xl bg-gray-100 flex items-center justify-center">
                                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">이 태그의 글이 없습니다</h3>
                            <a href="{{ route('blog.index') }}" class="inline-flex items-center gap-2 text-pink-600 hover:text-pink-700 font-medium mt-4">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                                </svg>
                                전체 글 보기
                            </a>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="w-full lg:w-80 flex-shrink-0">
                    @include('blog.partials.sidebar', [
                        'categories' => $categories,
                        'popularTags' => $popularTags,
                    ])
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
