<?php

namespace App\Exceptions;

use Exception;

class CompetitionException extends Exception
{
    public static function notFound(): self
    {
        return new self('Competition not found', 404);
    }

    public static function notActive(): self
    {
        return new self('Competition is not currently active', 400);
    }

    public static function alreadyJoined(): self
    {
        return new self('You have already joined this competition', 409);
    }

    public static function deviceNotCompatible(string $requiredDevice): self
    {
        return new self("This competition is only for {$requiredDevice} users", 403);
    }

    public static function notParticipant(): self
    {
        return new self('You are not a participant in this competition', 403);
    }
}
