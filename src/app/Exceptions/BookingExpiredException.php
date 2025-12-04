<?php

namespace App\Exceptions;

use Exception;

class BookingExpiredException extends Exception
{
    public function __construct(string $message = '지난 날짜에는 예약할 수 없습니다.')
    {
        parent::__construct($message);
    }
}
