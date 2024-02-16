<?php

declare(strict_types=1);

namespace App\Domain\Travel\Discount;

use App\Domain\Travel\Discount\TravelEarlyBookingDiscount\TravelStartingFromAprilNextYearToSeptemberNextYearDiscount;
use App\Domain\Travel\Discount\TravelEarlyBookingDiscount\TravelStartingFromFifteenthJanuaryNextYearDiscount;
use App\Domain\Travel\Discount\TravelEarlyBookingDiscount\TravelStartingFromOctoberThisYearToFourteenthJanuaryNextYearDiscount;
use DateTimeImmutable;

class TravelEarlyBookingDiscountCalculator
{
    private const MAX_EARLY_BOOKING_DISCOUNT = 1500;

    public function __construct(
        private TravelStartingFromAprilNextYearToSeptemberNextYearDiscount $travelStartingFromAprilNextYearToSeptemberNextYearDiscount,
        private TravelStartingFromOctoberThisYearToFourteenthJanuaryNextYearDiscount $travelStartingFromOctoberThisYearToFourteenthJanuaryNextYearDiscount,
        private TravelStartingFromFifteenthJanuaryNextYearDiscount $travelStartingFromFifteenthJanuaryNextYearDiscount,
    ) {
    }

    public function calculate(
        DateTimeImmutable $travelStartDate,
        ?DateTimeImmutable $travelPaymentDate,
        int $travelPrice
    ): int {
        if ($travelPaymentDate === null) {
            return 0;
        }

        $discount = 0;
        if ($this->travelStartingFromAprilNextYearToSeptemberNextYearDiscount->isApplicable($travelStartDate)) {
            $discount = $this->travelStartingFromAprilNextYearToSeptemberNextYearDiscount->calculate(
                $travelPaymentDate,
                $travelPrice
            );
        } elseif (
            $this->travelStartingFromOctoberThisYearToFourteenthJanuaryNextYearDiscount->isApplicable($travelStartDate)
        ) {
            $discount = $this->travelStartingFromOctoberThisYearToFourteenthJanuaryNextYearDiscount->calculate(
                $travelPaymentDate,
                $travelPrice
            );
        } elseif ($this->travelStartingFromFifteenthJanuaryNextYearDiscount->isApplicable($travelStartDate)) {
            $discount = $this->travelStartingFromFifteenthJanuaryNextYearDiscount->calculate(
                $travelPaymentDate,
                $travelPrice
            );
        }

        return ($discount > self::MAX_EARLY_BOOKING_DISCOUNT) ? self::MAX_EARLY_BOOKING_DISCOUNT : $discount;
    }
}
