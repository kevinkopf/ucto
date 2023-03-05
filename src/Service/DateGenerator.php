<?php

namespace App\Service;

class DateGenerator
{
    public function generateDateTime(string $date): \DateTime
    {
        $dt = \DateTime::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $date);
        $dt->setTimezone(new \DateTimeZone(date_default_timezone_get()));
        $dt->setTime(12, 0);

        return $dt;
    }
}