<?php

declare(strict_types=1);

namespace App\Domain\Travel\Discount;

use DateTimeImmutable;

class TravelChildDiscount
{
    private const CLIENT_3_YEARS_OLD_DISCOUNT_MULTIPLIER = 0.8;
    private const CLIENT_6_YEARS_OLD_DISCOUNT_MULTIPLIER = 0.3;
    private const MAX_CLIENT_6_YEARS_OLD_DISCOUNT = 4500;
    private const CLIENT_12_YEARS_OLD_DISCOUNT_MULTIPLIER = 0.1;

    public function calculate(DateTimeImmutable $clientBirthDate, int $travelPrice): int
    {
        $clientAge = $clientBirthDate->diff(new DateTimeImmutable('now'))->y;

        return match (true) {
            ($clientAge >= 3 && $clientAge < 6) => $this->calculateClient3YearsOldDiscount($travelPrice),
            ($clientAge >= 6 && $clientAge < 12) => $this->calculateClient6YearsOldDiscount($travelPrice),
            ($clientAge >= 12 && $clientAge < 18) => $this->calculateClient12YearsOldDiscount($travelPrice),
            default => 0,
        };
    }

    private function calculateClient3YearsOldDiscount(int $travelPrice): int
    {
        return (int)($travelPrice * self::CLIENT_3_YEARS_OLD_DISCOUNT_MULTIPLIER);
    }

    private function calculateClient6YearsOldDiscount(int $travelPrice): int
    {
        $discount = (int)($travelPrice * self::CLIENT_6_YEARS_OLD_DISCOUNT_MULTIPLIER);

        return ($discount > self::MAX_CLIENT_6_YEARS_OLD_DISCOUNT) ? self::MAX_CLIENT_6_YEARS_OLD_DISCOUNT : $discount;
    }

    private function calculateClient12YearsOldDiscount(int $travelPrice): int
    {
        return (int)($travelPrice * self::CLIENT_12_YEARS_OLD_DISCOUNT_MULTIPLIER);
    }
}
