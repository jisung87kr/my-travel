<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AttachmentController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|image|max:5120', // 5MB max
        ]);

        $file = $request->file('file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('attachments/' . date('Y/m'), $filename, 'public');

        $attachment = Attachment::create([
            'user_id' => auth()->id(),
            'disk' => 'public',
            'path' => $path,
            'filename' => $filename,
            'original_filename' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'type' => 'image',
        ]);

        return response()->json([
            'success' => true,
            'url' => $attachment->url,
            'attachment_id' => $attachment->id,
        ]);
    }

    public function destroy(Attachment $attachment): JsonResponse
    {
        if ($attachment->user_id !== auth()->id() && !auth()->user()->hasRole('admin')) {
            return response()->json([
                'success' => false,
                'message' => '삭제 권한이 없습니다.',
            ], 403);
        }

        $attachment->delete();

        return response()->json([
            'success' => true,
        ]);
    }
}
