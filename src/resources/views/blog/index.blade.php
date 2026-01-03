<x-layouts.app title="블로그">
    <div class="bg-gradient-to-b py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="text-center mb-12">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">블로그</h1>
                <p class="text-gray-600 max-w-2xl mx-auto">여행 이야기, 팁, 그리고 새로운 발견을 공유합니다</p>
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                </svg>
                            </div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">아직 작성된 글이 없습니다</h3>
                            <p class="text-gray-500">곧 새로운 글로 찾아뵙겠습니다</p>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="w-full lg:w-80 flex-shrink-0">
                    @include('blog.partials.sidebar', [
                        'categories' => $categories,
                        'popularTags' => $popularTags,
                        'popularPosts' => $popularPosts,
                    ])
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
