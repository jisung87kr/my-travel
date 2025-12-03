<?php

namespace Tests\Unit\Enums;

use App\Enums\BookingStatus;
use PHPUnit\Framework\TestCase;

class BookingStatusTest extends TestCase
{
    public function test_booking_status_values(): void
    {
        $values = BookingStatus::values();

        $this->assertContains('pending', $values);
        $this->assertContains('confirmed', $values);
        $this->assertContains('in_progress', $values);
        $this->assertContains('completed', $values);
        $this->assertContains('cancelled', $values);
        $this->assertContains('no_show', $values);
    }

    public function test_booking_status_labels(): void
    {
        $this->assertEquals('승인 대기', BookingStatus::PENDING->label());
        $this->assertEquals('확정', BookingStatus::CONFIRMED->label());
        $this->assertEquals('진행 중', BookingStatus::IN_PROGRESS->label());
        $this->assertEquals('완료', BookingStatus::COMPLETED->label());
        $this->assertEquals('취소', BookingStatus::CANCELLED->label());
        $this->assertEquals('노쇼', BookingStatus::NO_SHOW->label());
    }

    public function test_booking_status_colors(): void
    {
        $this->assertEquals('yellow', BookingStatus::PENDING->color());
        $this->assertEquals('blue', BookingStatus::CONFIRMED->color());
        $this->assertEquals('indigo', BookingStatus::IN_PROGRESS->color());
        $this->assertEquals('green', BookingStatus::COMPLETED->color());
        $this->assertEquals('gray', BookingStatus::CANCELLED->color());
        $this->assertEquals('red', BookingStatus::NO_SHOW->color());
    }

    public function test_pending_can_transition_to_confirmed_or_cancelled(): void
    {
        $status = BookingStatus::PENDING;

        $this->assertTrue($status->canTransitionTo(BookingStatus::CONFIRMED));
        $this->assertTrue($status->canTransitionTo(BookingStatus::CANCELLED));
        $this->assertFalse($status->canTransitionTo(BookingStatus::COMPLETED));
        $this->assertFalse($status->canTransitionTo(BookingStatus::IN_PROGRESS));
    }

    public function test_confirmed_can_transition_to_in_progress_cancelled_or_no_show(): void
    {
        $status = BookingStatus::CONFIRMED;

        $this->assertTrue($status->canTransitionTo(BookingStatus::IN_PROGRESS));
        $this->assertTrue($status->canTransitionTo(BookingStatus::CANCELLED));
        $this->assertTrue($status->canTransitionTo(BookingStatus::NO_SHOW));
        $this->assertFalse($status->canTransitionTo(BookingStatus::COMPLETED));
    }

    public function test_in_progress_can_transition_to_completed_or_no_show(): void
    {
        $status = BookingStatus::IN_PROGRESS;

        $this->assertTrue($status->canTransitionTo(BookingStatus::COMPLETED));
        $this->assertTrue($status->canTransitionTo(BookingStatus::NO_SHOW));
        $this->assertFalse($status->canTransitionTo(BookingStatus::CANCELLED));
    }

    public function test_terminal_states_cannot_transition(): void
    {
        $this->assertFalse(BookingStatus::COMPLETED->canTransitionTo(BookingStatus::PENDING));
        $this->assertFalse(BookingStatus::CANCELLED->canTransitionTo(BookingStatus::PENDING));
        $this->assertFalse(BookingStatus::NO_SHOW->canTransitionTo(BookingStatus::PENDING));
    }
}
