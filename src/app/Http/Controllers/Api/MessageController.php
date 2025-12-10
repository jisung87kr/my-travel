<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MessageController extends Controller
{
    public function __construct(
        private MessageService $messageService
    ) {}

    public function index(Request $request, Booking $booking): JsonResponse
    {
        $user = $request->user();

        // Verify access (traveler or vendor)
        if ($booking->user_id !== $user->id && $booking->product->vendor->user_id !== $user->id) {
            return Response::forbidden('권한이 없습니다.');
        }

        // Mark messages as read
        $this->messageService->markThreadAsRead($booking, $user);

        $messages = $this->messageService->getThread($booking);

        return Response::success(['messages' => $messages]);
    }

    public function store(Request $request, Booking $booking): JsonResponse
    {
        $user = $request->user();

        // Verify access (traveler or vendor)
        if ($booking->user_id !== $user->id && $booking->product->vendor->user_id !== $user->id) {
            return Response::forbidden('권한이 없습니다.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $message = $this->messageService->send($booking, $user, $validated['content']);

        return Response::created($message->load(['sender', 'receiver']), '메시지가 전송되었습니다.');
    }

    public function markAsRead(Request $request, Message $message): JsonResponse
    {
        $user = $request->user();

        if ($message->receiver_id !== $user->id) {
            return Response::forbidden('권한이 없습니다.');
        }

        $this->messageService->markAsRead($message, $user);

        return Response::success(null, '읽음 처리되었습니다.');
    }

    public function conversations(Request $request): JsonResponse
    {
        $user = $request->user();
        $conversations = $this->messageService->getConversations($user);
        $unreadCount = $this->messageService->getUnreadCount($user);

        return Response::success([
            'conversations' => $conversations,
            'unread_count' => $unreadCount,
        ]);
    }
}
