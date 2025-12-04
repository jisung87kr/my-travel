<?php

namespace Tests\Feature\Messages;

use App\Enums\BookingStatus;
use App\Enums\ProductStatus;
use App\Models\Booking;
use App\Models\Message;
use App\Models\Product;
use App\Models\ProductSchedule;
use App\Models\User;
use App\Models\Vendor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MessageTest extends TestCase
{
    use RefreshDatabase;

    private User $traveler;
    private User $vendorUser;
    private Vendor $vendor;
    private Product $product;
    private Booking $booking;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);

        $this->traveler = User::factory()->create();
        $this->traveler->assignRole('traveler');

        $this->vendorUser = User::factory()->create();
        $this->vendorUser->assignRole('vendor');

        $this->vendor = Vendor::factory()->approved()->create([
            'user_id' => $this->vendorUser->id,
        ]);

        $this->product = Product::factory()->create([
            'vendor_id' => $this->vendor->id,
            'status' => ProductStatus::ACTIVE,
        ]);

        $schedule = ProductSchedule::factory()->create([
            'product_id' => $this->product->id,
        ]);

        $this->booking = Booking::factory()->create([
            'user_id' => $this->traveler->id,
            'product_id' => $this->product->id,
            'schedule_id' => $schedule->id,
            'status' => BookingStatus::CONFIRMED,
        ]);
    }

    public function test_traveler_can_send_message_to_vendor(): void
    {
        $response = $this->actingAs($this->traveler)
            ->postJson("/bookings/{$this->booking->id}/messages", [
                'content' => '안녕하세요, 투어 관련 문의드립니다.',
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('messages', [
            'booking_id' => $this->booking->id,
            'sender_id' => $this->traveler->id,
            'receiver_id' => $this->vendorUser->id,
        ]);
    }

    public function test_vendor_can_reply_to_message(): void
    {
        // Traveler sends first message
        Message::factory()->create([
            'booking_id' => $this->booking->id,
            'sender_id' => $this->traveler->id,
            'receiver_id' => $this->vendorUser->id,
            'content' => '문의드립니다.',
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->postJson("/bookings/{$this->booking->id}/messages", [
                'content' => '네, 무엇을 도와드릴까요?',
            ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('messages', [
            'booking_id' => $this->booking->id,
            'sender_id' => $this->vendorUser->id,
            'receiver_id' => $this->traveler->id,
        ]);
    }

    public function test_traveler_can_view_message_thread(): void
    {
        Message::factory()->count(3)->create([
            'booking_id' => $this->booking->id,
            'sender_id' => $this->traveler->id,
            'receiver_id' => $this->vendorUser->id,
        ]);

        Message::factory()->count(2)->create([
            'booking_id' => $this->booking->id,
            'sender_id' => $this->vendorUser->id,
            'receiver_id' => $this->traveler->id,
        ]);

        $response = $this->actingAs($this->traveler)
            ->getJson("/bookings/{$this->booking->id}/messages");

        $response->assertStatus(200)
            ->assertJsonCount(5, 'messages');
    }

    public function test_vendor_can_view_message_thread(): void
    {
        Message::factory()->count(3)->create([
            'booking_id' => $this->booking->id,
            'sender_id' => $this->traveler->id,
            'receiver_id' => $this->vendorUser->id,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->getJson("/bookings/{$this->booking->id}/messages");

        $response->assertStatus(200)
            ->assertJsonCount(3, 'messages');
    }

    public function test_mark_message_as_read(): void
    {
        $message = Message::factory()->create([
            'booking_id' => $this->booking->id,
            'sender_id' => $this->traveler->id,
            'receiver_id' => $this->vendorUser->id,
            'read_at' => null,
        ]);

        $response = $this->actingAs($this->vendorUser)
            ->patchJson("/messages/{$message->id}/read");

        $response->assertStatus(200);
        $this->assertNotNull($message->fresh()->read_at);
    }

    public function test_sender_cannot_mark_own_message_as_read(): void
    {
        $message = Message::factory()->create([
            'booking_id' => $this->booking->id,
            'sender_id' => $this->traveler->id,
            'receiver_id' => $this->vendorUser->id,
            'read_at' => null,
        ]);

        $response = $this->actingAs($this->traveler)
            ->patchJson("/messages/{$message->id}/read");

        // Controller returns 403 since traveler is not the receiver
        $response->assertStatus(403);
    }

    public function test_traveler_can_view_conversations_list(): void
    {
        // Create messages in first booking
        Message::factory()->create([
            'booking_id' => $this->booking->id,
            'sender_id' => $this->traveler->id,
            'receiver_id' => $this->vendorUser->id,
        ]);

        $response = $this->actingAs($this->traveler)
            ->get('/messages');

        $response->assertStatus(200);
    }

    public function test_unauthorized_user_cannot_view_message_thread(): void
    {
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)
            ->getJson("/bookings/{$this->booking->id}/messages");

        $response->assertStatus(403);
    }

    public function test_unauthorized_user_cannot_send_message(): void
    {
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)
            ->postJson("/bookings/{$this->booking->id}/messages", [
                'content' => '테스트 메시지',
            ]);

        $response->assertStatus(403);
    }

    public function test_message_content_required_validation(): void
    {
        $response = $this->actingAs($this->traveler)
            ->postJson("/bookings/{$this->booking->id}/messages", [
                'content' => '', // Empty content
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('content');
    }

    public function test_message_content_max_length_validation(): void
    {
        $response = $this->actingAs($this->traveler)
            ->postJson("/bookings/{$this->booking->id}/messages", [
                'content' => str_repeat('가', 2001), // Exceeds 2000 characters
            ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors('content');
    }

    public function test_unauthenticated_user_cannot_send_message(): void
    {
        $response = $this->postJson("/bookings/{$this->booking->id}/messages", [
            'content' => '테스트 메시지',
        ]);

        $response->assertStatus(401);
    }

    public function test_unauthenticated_user_cannot_view_messages(): void
    {
        $response = $this->getJson("/bookings/{$this->booking->id}/messages");

        $response->assertStatus(401);
    }
}
