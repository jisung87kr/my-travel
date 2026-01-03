<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBlogCommentRequest;
use App\Models\BlogComment;
use App\Models\BlogPost;
use App\Services\BlogService;
use Illuminate\Http\JsonResponse;

class BlogCommentController extends Controller
{
    public function __construct(
        private readonly BlogService $blogService
    ) {}

    public function store(StoreBlogCommentRequest $request, BlogPost $post): JsonResponse
    {
        $comment = $this->blogService->createComment(
            $post,
            $request->user(),
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'content' => $comment->content,
                'created_at' => $comment->created_at->diffForHumans(),
                'user' => [
                    'name' => $comment->user->name,
                    'initial' => mb_substr($comment->user->name, 0, 1),
                ],
                'parent_id' => $comment->parent_id,
            ],
        ], 201);
    }

    public function destroy(BlogComment $comment): JsonResponse
    {
        $user = auth()->user();

        if ($comment->user_id !== $user->id && !$user->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => '삭제 권한이 없습니다.',
            ], 403);
        }

        $this->blogService->deleteComment($comment);

        return response()->json([
            'success' => true,
        ]);
    }
}
