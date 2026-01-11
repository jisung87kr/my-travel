<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Report;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function __construct(
        private readonly ReviewService $reviewService
    ) {}

    public function store(Request $request, Booking $booking): RedirectResponse
    {
        // Verify ownership
        if ($booking->user_id !== $request->user()->id) {
            return redirect()->back()->with('error', '권한이 없습니다.');
        }

        // Check if can review
        if (!$this->reviewService->canReview($booking)) {
            return redirect()->back()->with('error', '리뷰를 작성할 수 없습니다.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:10|max:1000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $images = $request->file('images', []);
        $this->reviewService->create($booking, $validated, $images);

        return redirect()->back()->with('success', '리뷰가 등록되었습니다.');
    }

    public function update(Request $request, Review $review): RedirectResponse
    {
        // Verify ownership
        if ($review->user_id !== $request->user()->id) {
            return redirect()->back()->with('error', '권한이 없습니다.');
        }

        $validated = $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'content' => 'sometimes|string|min:10|max:1000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|exists:review_images,id',
        ]);

        $newImages = $request->file('images', []);
        $deleteImageIds = $validated['delete_images'] ?? [];

        $this->reviewService->update($review, $validated, $newImages, $deleteImageIds);

        return redirect()->back()->with('success', '리뷰가 수정되었습니다.');
    }

    public function destroy(Request $request, Review $review): RedirectResponse
    {
        // Verify ownership
        if ($review->user_id !== $request->user()->id) {
            return redirect()->back()->with('error', '권한이 없습니다.');
        }

        $this->reviewService->delete($review);

        return redirect()->back()->with('success', '리뷰가 삭제되었습니다.');
    }

    public function reply(Request $request, Review $review): RedirectResponse
    {
        // Verify vendor ownership
        $vendor = $review->product->vendor;
        if ($vendor->user_id !== $request->user()->id && !$request->user()->hasRole('admin')) {
            return redirect()->back()->with('error', '권한이 없습니다.');
        }

        $validated = $request->validate([
            'reply' => 'required|string|min:1|max:1000',
        ]);

        $this->reviewService->reply($review, $validated['reply']);

        return redirect()->back()->with('success', '답변이 등록되었습니다.');
    }

    public function report(Request $request, Review $review): RedirectResponse
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        Report::create([
            'reporter_id' => $request->user()->id,
            'reportable_type' => Review::class,
            'reportable_id' => $review->id,
            'reason' => $validated['reason'],
        ]);

        return redirect()->back()->with('success', '신고가 접수되었습니다.');
    }
}
