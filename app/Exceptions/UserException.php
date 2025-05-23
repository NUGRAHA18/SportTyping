<?php

namespace App\Exceptions;

use Exception;

class UserException extends Exception
{
    public static function profileNotFound(): self
    {
        return new self('User profile not found', 404);
    }

    public static function experienceUpdateFailed(): self
    {
        return new self('Failed to update user experience', 500);
    }

    public static function badgeAwardFailed(): self
    {
        return new self('Failed to award badge to user', 500);
    }
}
