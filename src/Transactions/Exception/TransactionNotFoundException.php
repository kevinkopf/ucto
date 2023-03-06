<?php

namespace App\Transactions\Exception;

use JetBrains\PhpStorm\Pure;
use LogicException;
use Throwable;

class TransactionNotFoundException extends LogicException
{
    #[Pure]
    public function __construct(string $message = "", int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct("Transaction could not be found, perhaps it is missing in the database in the database", $code, $previous);
    }
}