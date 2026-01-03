<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Services\BlogService;
use Illuminate\Http\JsonResponse;

class BlogLikeController extends Controller
{
    public function __construct(
        private readonly BlogService $blogService
    ) {}

    public function toggle(BlogPost $post): JsonResponse
    {
        $result = $this->blogService->toggleLike($post, auth()->user());

        return response()->json([
            'success' => true,
            'liked' => $result['liked'],
            'like_count' => $result['like_count'],
        ]);
    }
}
