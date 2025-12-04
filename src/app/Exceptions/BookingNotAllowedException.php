<?php

namespace App\Exceptions;

use Exception;

class BookingNotAllowedException extends Exception
{
    public function __construct(string $message = '예약이 제한되었습니다.')
    {
        parent::__construct($message);
    }
}
