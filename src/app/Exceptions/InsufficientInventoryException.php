<?php

namespace App\Exceptions;

use Exception;

class InsufficientInventoryException extends Exception
{
    public function __construct(string $message = '예약 가능한 인원이 부족합니다.')
    {
        parent::__construct($message);
    }
}
