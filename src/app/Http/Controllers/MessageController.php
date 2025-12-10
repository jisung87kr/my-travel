<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\MessageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function __construct(
        private MessageService $messageService
    ) {}

    public function index(Request $request, Booking $booking): View
    {
        $user = $request->user();

        // Verify access (traveler or vendor)
        if ($booking->user_id !== $user->id && $booking->product->vendor->user_id !== $user->id) {
            abort(403, '권한이 없습니다.');
        }

        // Mark messages as read
        $this->messageService->markThreadAsRead($booking, $user);

        $messages = $this->messageService->getThread($booking);

        return view('messages.thread', compact('booking', 'messages'));
    }

    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $user = $request->user();

        // Verify access (traveler or vendor)
        if ($booking->user_id !== $user->id && $booking->product->vendor->user_id !== $user->id) {
            return redirect()->back()->with('error', '권한이 없습니다.');
        }

        $validated = $request->validate([
            'content' => 'required|string|max:2000',
        ]);

        $this->messageService->send($booking, $user, $validated['content']);

        return redirect()->back()->with('success', '메시지가 전송되었습니다.');
    }

    public function conversations(Request $request): View
    {
        $user = $request->user();
        $conversations = $this->messageService->getConversations($user);
        $unreadCount = $this->messageService->getUnreadCount($user);

        return view('messages.index', compact('conversations', 'unreadCount'));
    }
}
