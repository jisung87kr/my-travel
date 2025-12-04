<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Report;
use App\Models\Review;
use App\Services\ReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReviewController extends Controller
{
    public function __construct(
        private ReviewService $reviewService
    ) {}

    public function store(Request $request, Booking $booking): RedirectResponse|JsonResponse
    {
        // Verify ownership
        if ($booking->user_id !== $request->user()->id) {
            return $this->errorResponse($request, '권한이 없습니다.', 403);
        }

        // Check if can review
        if (! $this->reviewService->canReview($booking)) {
            return $this->errorResponse($request, '리뷰를 작성할 수 없습니다.', 400);
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'content' => 'required|string|min:10|max:1000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg|max:5120',
        ]);

        $images = $request->file('images', []);
        $review = $this->reviewService->create($booking, $validated, $images);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '리뷰가 등록되었습니다.',
                'review' => $review->load(['user', 'images']),
            ], 201);
        }

        return redirect()->back()->with('success', '리뷰가 등록되었습니다.');
    }

    public function update(Request $request, Review $review): RedirectResponse|JsonResponse
    {
        // Verify ownership
        if ($review->user_id !== $request->user()->id) {
            return $this->errorResponse($request, '권한이 없습니다.', 403);
        }

        $validated = $request->validate([
            'rating' => 'sometimes|integer|min:1|max:5',
            'content' => 'sometimes|string|min:10|max:1000',
        ]);

        $review = $this->reviewService->update($review, $validated);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '리뷰가 수정되었습니다.',
                'review' => $review->load(['user', 'images']),
            ]);
        }

        return redirect()->back()->with('success', '리뷰가 수정되었습니다.');
    }

    public function destroy(Request $request, Review $review): RedirectResponse|JsonResponse
    {
        // Verify ownership
        if ($review->user_id !== $request->user()->id) {
            return $this->errorResponse($request, '권한이 없습니다.', 403);
        }

        $this->reviewService->delete($review);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '리뷰가 삭제되었습니다.',
            ]);
        }

        return redirect()->back()->with('success', '리뷰가 삭제되었습니다.');
    }

    public function reply(Request $request, Review $review): RedirectResponse|JsonResponse
    {
        // Verify vendor ownership
        $vendor = $review->product->vendor;
        if ($vendor->user_id !== $request->user()->id && ! $request->user()->hasRole('admin')) {
            return $this->errorResponse($request, '권한이 없습니다.', 403);
        }

        $validated = $request->validate([
            'reply' => 'required|string|min:1|max:1000',
        ]);

        $review = $this->reviewService->reply($review, $validated['reply']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '답변이 등록되었습니다.',
                'review' => $review,
            ]);
        }

        return redirect()->back()->with('success', '답변이 등록되었습니다.');
    }

    public function report(Request $request, Review $review): RedirectResponse|JsonResponse
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

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => '신고가 접수되었습니다.',
            ]);
        }

        return redirect()->back()->with('success', '신고가 접수되었습니다.');
    }

    private function errorResponse(Request $request, string $message, int $status): RedirectResponse|JsonResponse
    {
        if ($request->wantsJson()) {
            return response()->json(['success' => false, 'message' => $message], $status);
        }

        return redirect()->back()->with('error', $message);
    }
}
