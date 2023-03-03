<?php

namespace App\Contacts\Repository;

class ContactSearchCriteria
{
    public function __construct(
        public readonly int $limit,
        public readonly int $offset,
        public readonly ?string $name,
        public readonly ?string $address,
        public readonly ?string $registrationNumber,
        public readonly ?string $vatNumber,
    )
    {
    }
}