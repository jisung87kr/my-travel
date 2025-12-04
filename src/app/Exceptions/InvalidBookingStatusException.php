<?php

namespace App\Exceptions;

use Exception;

class InvalidBookingStatusException extends Exception
{
    public function __construct(string $message = '현재 상태에서는 해당 작업을 수행할 수 없습니다.')
    {
        parent::__construct($message);
    }
}
