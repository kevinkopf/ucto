<?php

namespace App\Accounts\Exception;

use JetBrains\PhpStorm\Pure;
use Throwable;

class AccountNotFoundException extends \LogicException
{
    #[Pure]
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("Account could not be found, perhaps it is missing in the database in the database", $code, $previous);
    }
}