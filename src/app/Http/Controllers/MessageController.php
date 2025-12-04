<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Message;
use App\Services\MessageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function __construct(
        private MessageService $messageService
    ) {}

    public function index(Request $request, Booking $booking): JsonResponse|View
    {
        $user = $request->user();

        // Verify access (traveler or vendor)
        if ($booking->user_id !== $user->id && $booking->product->vendor->user_id !== $user->id) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => '권한이 없습니다.'], 403);
            }
            abort(403);
        }

        // Mark messages as read
        $this->messageService->markThreadAsRead($booking, $user);

        $messages = $this->messageService->getThread($booking);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'messages' => $messages,
            ]);
        }

        return view('messages.thread', compact('booking', 'messages'));
    }

    public function store(Request $request, Booking $booking): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        // Verify access (traveler or vendor)
        if ($booking->user_id !== $user->id && $booking->product->vendor->user_id !== $user->id) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'message' => '권한이 없습니다.'], 403);
            }
            return redirect()->back()->with('error', '권한이 없습니다.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $message = $this->messageService->send($booking, $user, $validated['content']);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message->load(['sender', 'receiver']),
            ], 201);
        }

        return redirect()->back()->with('success', '메시지가 전송되었습니다.');
    }

    public function markAsRead(Request $request, Message $message): JsonResponse
    {
        $user = $request->user();

        if ($message->receiver_id !== $user->id) {
            return response()->json(['success' => false, 'message' => '권한이 없습니다.'], 403);
        }

        $this->messageService->markAsRead($message, $user);

        return response()->json(['success' => true]);
    }

    public function conversations(Request $request): JsonResponse|View
    {
        $user = $request->user();
        $conversations = $this->messageService->getConversations($user);
        $unreadCount = $this->messageService->getUnreadCount($user);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'conversations' => $conversations,
                'unread_count' => $unreadCount,
            ]);
        }

        return view('messages.index', compact('conversations', 'unreadCount'));
    }
}
