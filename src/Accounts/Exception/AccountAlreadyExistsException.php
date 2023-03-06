<?php

namespace App\Accounts\Exception;

use JetBrains\PhpStorm\Pure;
use Throwable;

class AccountAlreadyExistsException extends \LogicException
{
    #[Pure]
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("Account with these details already exists", $code, $previous);
    }
}