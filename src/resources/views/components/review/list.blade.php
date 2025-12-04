@props(['reviews', 'product' => null])

<div class="space-y-6">
    @forelse($reviews as $review)
        <x-review.item :review="$review" :product="$product" />
    @empty
        <div class="text-center py-8 text-gray-500">
            아직 리뷰가 없습니다.
        </div>
    @endforelse

    @if($reviews->hasPages())
        <div class="mt-6">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
