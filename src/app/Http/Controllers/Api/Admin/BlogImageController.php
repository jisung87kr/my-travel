<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Services\BlogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class BlogImageController extends Controller
{
    public function __construct(
        private readonly BlogService $blogService
    ) {}

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'image' => 'required|image|max:5120',
        ]);

        $url = $this->blogService->uploadEditorImage($request->file('image'));

        return response()->json([
            'success' => true,
            'url' => $url,
        ]);
    }
}
