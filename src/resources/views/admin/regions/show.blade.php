<x-layouts.admin>
    <x-slot name="header">지역 상세</x-slot>

    <div class="max-w-4xl space-y-6">
        <!-- Header Actions -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.regions.index') }}"
                   class="p-2 text-slate-400 hover:text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h2 class="text-xl font-semibold text-slate-800">{{ $region->getName('ko') }}</h2>
                @if($region->is_active)
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-emerald-100 text-emerald-700">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                        활성
                    </span>
                @else
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-lg bg-slate-100 text-slate-600">
                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                        비활성
                    </span>
                @endif
                @if($region->is_featured)
                    <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-lg bg-amber-100 text-amber-700">
                        <svg class="w-3.5 h-3.5" fill="currentColor" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                        </svg>
                        추천
                    </span>
                @endif
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.regions.images', $region) }}"
                   class="px-4 py-2 bg-blue-50 text-blue-700 rounded-lg hover:bg-blue-100 transition-colors font-medium">
                    이미지 관리
                </a>
                <a href="{{ route('admin.regions.edit', $region) }}"
                   class="px-4 py-2 bg-amber-50 text-amber-700 rounded-lg hover:bg-amber-100 transition-colors font-medium">
                    수정
                </a>
            </div>
        </div>

        <!-- Basic Info -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
            <h3 class="text-sm font-semibold text-slate-800 mb-4">기본 정보</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div>
                    <dt class="text-sm text-slate-500">코드</dt>
                    <dd class="mt-1 font-mono text-slate-800">{{ $region->code }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">레벨</dt>
                    <dd class="mt-1 text-slate-800">
                        @if($region->level === 1)
                            국가
                        @elseif($region->level === 2)
                            시/도
                        @else
                            시/군/구
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">상위 지역</dt>
                    <dd class="mt-1 text-slate-800">
                        @if($region->parent)
                            <a href="{{ route('admin.regions.show', $region->parent) }}" class="text-blue-600 hover:underline">
                                {{ $region->parent->getName('ko') }}
                            </a>
                        @else
                            <span class="text-slate-400">-</span>
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">정렬 순서</dt>
                    <dd class="mt-1 text-slate-800">{{ $region->sort_order }}</dd>
                </div>
            </div>
        </div>

        <!-- Translations -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
            <h3 class="text-sm font-semibold text-slate-800 mb-4">번역</h3>
            <div class="space-y-4">
                @foreach($region->translations as $translation)
                    <div class="p-4 bg-slate-50 rounded-xl">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="px-2 py-0.5 text-xs font-medium rounded bg-slate-200 text-slate-700 uppercase">
                                {{ $translation->locale }}
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <dt class="text-sm text-slate-500">이름</dt>
                                <dd class="mt-1 text-slate-800">{{ $translation->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm text-slate-500">짧은 이름</dt>
                                <dd class="mt-1 text-slate-800">{{ $translation->short_name ?? '-' }}</dd>
                            </div>
                        </div>
                        @if($translation->description)
                            <div class="mt-3">
                                <dt class="text-sm text-slate-500">설명</dt>
                                <dd class="mt-1 text-slate-800">{{ $translation->description }}</dd>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Location -->
        @if($region->latitude && $region->longitude)
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
            <h3 class="text-sm font-semibold text-slate-800 mb-4">위치 정보</h3>
            <div class="grid grid-cols-3 gap-6">
                <div>
                    <dt class="text-sm text-slate-500">위도</dt>
                    <dd class="mt-1 font-mono text-slate-800">{{ $region->latitude }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">경도</dt>
                    <dd class="mt-1 font-mono text-slate-800">{{ $region->longitude }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">타임존</dt>
                    <dd class="mt-1 text-slate-800">{{ $region->timezone ?? 'Asia/Seoul' }}</dd>
                </div>
            </div>
        </div>
        @endif

        <!-- Statistics -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
            <h3 class="text-sm font-semibold text-slate-800 mb-4">통계</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center p-4 bg-slate-50 rounded-xl">
                    <div class="text-2xl font-bold text-slate-800">{{ $region->products_count }}</div>
                    <div class="text-sm text-slate-500 mt-1">활성 상품</div>
                </div>
                <div class="text-center p-4 bg-slate-50 rounded-xl">
                    <div class="text-2xl font-bold text-slate-800">{{ $region->children->count() }}</div>
                    <div class="text-sm text-slate-500 mt-1">하위 지역</div>
                </div>
                <div class="text-center p-4 bg-slate-50 rounded-xl">
                    <div class="text-2xl font-bold text-slate-800">{{ number_format($region->popularity) }}</div>
                    <div class="text-sm text-slate-500 mt-1">인기도</div>
                </div>
                <div class="text-center p-4 bg-slate-50 rounded-xl">
                    <div class="text-2xl font-bold text-slate-800">{{ $region->images->count() }}</div>
                    <div class="text-sm text-slate-500 mt-1">이미지</div>
                </div>
            </div>
        </div>

        <!-- Images -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-800">이미지</h3>
                <a href="{{ route('admin.regions.images', $region) }}"
                   class="text-sm text-blue-600 hover:underline">
                    이미지 관리
                </a>
            </div>
            @if($region->images->count() > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-3">
                    @foreach($region->images->take(6) as $image)
                        <div class="relative aspect-[4/3] rounded-xl overflow-hidden bg-slate-100">
                            <img src="{{ $image->url }}" alt="Region image" class="w-full h-full object-cover">
                            @if($image->is_primary)
                                <span class="absolute top-1 left-1 px-1.5 py-0.5 text-[10px] font-medium rounded bg-amber-500 text-white">
                                    대표
                                </span>
                            @endif
                        </div>
                    @endforeach
                </div>
                @if($region->images->count() > 6)
                    <p class="text-sm text-slate-500 mt-3">
                        +{{ $region->images->count() - 6 }}개 더 보기
                    </p>
                @endif
            @else
                <div class="text-center py-8">
                    <div class="w-12 h-12 rounded-xl bg-slate-100 flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <p class="text-slate-500 text-sm">등록된 이미지가 없습니다.</p>
                    <a href="{{ route('admin.regions.images', $region) }}" class="text-sm text-blue-600 hover:underline mt-1 inline-block">
                        이미지 추가하기
                    </a>
                </div>
            @endif
        </div>

        <!-- Children -->
        @if($region->children->count() > 0)
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-slate-800">하위 지역</h3>
                <a href="{{ route('admin.regions.index', ['level' => $region->level + 1, 'parent_id' => $region->id]) }}"
                   class="text-sm text-blue-600 hover:underline">
                    전체 보기
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                @foreach($region->children->take(8) as $child)
                    <a href="{{ route('admin.regions.show', $child) }}"
                       class="p-3 bg-slate-50 rounded-xl hover:bg-slate-100 transition-colors">
                        <div class="font-medium text-slate-800">{{ $child->getName('ko') }}</div>
                        <div class="text-sm text-slate-500">{{ $child->code }}</div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Timestamps -->
        <div class="bg-white rounded-2xl border border-slate-200/60 p-6">
            <h3 class="text-sm font-semibold text-slate-800 mb-4">기록</h3>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm text-slate-500">생성일</dt>
                    <dd class="mt-1 text-slate-800">{{ $region->created_at->format('Y-m-d H:i:s') }}</dd>
                </div>
                <div>
                    <dt class="text-sm text-slate-500">수정일</dt>
                    <dd class="mt-1 text-slate-800">{{ $region->updated_at->format('Y-m-d H:i:s') }}</dd>
                </div>
            </div>
        </div>
    </div>
</x-layouts.admin>
