<?php

namespace App\Exceptions;

use Exception;

class TypingException extends Exception
{
    public static function invalidText(): self
    {
        return new self('Invalid typing text provided', 400);
    }

    public static function calculationFailed(): self
    {
        return new self('Failed to calculate typing statistics', 500);
    }

    public static function invalidTimeData(): self
    {
        return new self('Invalid time data provided', 400);
    }
}