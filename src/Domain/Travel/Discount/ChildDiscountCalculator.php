<?php

declare(strict_types=1);

namespace App\Domain\Travel\Discount;

use App\Api\Travel\Discount\Price\TravelDiscountPriceDTO;

class ChildDiscountCalculator  implements DiscountCalculatorInterface
{
    private const CLIENT_3_YEARS_OLD_DISCOUNT_MULTIPLIER = 0.8;
    private const CLIENT_6_YEARS_OLD_DISCOUNT_MULTIPLIER = 0.3;
    private const MAX_CLIENT_6_YEARS_OLD_DISCOUNT = 4500;
    private const CLIENT_12_YEARS_OLD_DISCOUNT_MULTIPLIER = 0.1;

    public function calculate(TravelDiscountPriceDTO $travelDiscountPriceDTO): int {
        $clientAge = $travelDiscountPriceDTO->clientBirthDate->diff($travelDiscountPriceDTO->travelStartDate)->y;

        return match (true) {
            ($clientAge >= 3 && $clientAge < 6)
                => $this->calculateClient3YearsOldDiscount($travelDiscountPriceDTO->travelPrice),
            ($clientAge >= 6 && $clientAge < 12)
                => $this->calculateClient6YearsOldDiscount($travelDiscountPriceDTO->travelPrice),
            ($clientAge >= 12 && $clientAge < 18)
                => $this->calculateClient12YearsOldDiscount($travelDiscountPriceDTO->travelPrice),
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
