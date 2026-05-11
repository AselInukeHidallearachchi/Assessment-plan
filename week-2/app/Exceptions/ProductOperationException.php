<?php

namespace App\Exceptions;

use RuntimeException;
use Throwable;

class ProductOperationException extends RuntimeException
{
    public static function forAction(string $action, Throwable $previous): self
    {
        return new self("Unable to {$action} product at this time.", previous: $previous);
    }
}
