<?php

declare(strict_types=1);

namespace App\Domain\Travel\Discount;

use App\Api\Travel\Discount\Price\TravelDiscountPriceDTO;
use App\Domain\Travel\Discount\EarlyBookingDiscount\StartingFromAprilNextYearToSeptemberNextYearDiscount;
use App\Domain\Travel\Discount\EarlyBookingDiscount\StartingFromFifteenthJanuaryNextYearDiscount;
use App\Domain\Travel\Discount\EarlyBookingDiscount\StartingFromOctoberThisYearToFourteenthJanuaryNextYearDiscount;

class EarlyBookingDiscountCalculator implements DiscountCalculatorInterface
{
    private const MAX_EARLY_BOOKING_DISCOUNT = 1500;

    public function __construct(
        private StartingFromAprilNextYearToSeptemberNextYearDiscount $travelStartingFromAprilNextYearToSeptemberNextYearDiscount,
        private StartingFromOctoberThisYearToFourteenthJanuaryNextYearDiscount $travelStartingFromOctoberThisYearToFourteenthJanuaryNextYearDiscount,
        private StartingFromFifteenthJanuaryNextYearDiscount $travelStartingFromFifteenthJanuaryNextYearDiscount,
    ) {
    }

    public function calculate(TravelDiscountPriceDTO $travelDiscountPriceDTO): int
    {
        if ($travelDiscountPriceDTO->travelPaymentDate === null) {
            return 0;
        }

        $discount = 0;
        if ($this->travelStartingFromAprilNextYearToSeptemberNextYearDiscount->isApplicable(
            $travelDiscountPriceDTO->travelStartDate
        )) {
            $discount = $this->travelStartingFromAprilNextYearToSeptemberNextYearDiscount->calculate(
                $travelDiscountPriceDTO->travelPaymentDate,
                $travelDiscountPriceDTO->travelPrice
            );
        } elseif (
            $this->travelStartingFromOctoberThisYearToFourteenthJanuaryNextYearDiscount->isApplicable(
                $travelDiscountPriceDTO->travelStartDate
            )
        ) {
            $discount = $this->travelStartingFromOctoberThisYearToFourteenthJanuaryNextYearDiscount->calculate(
                $travelDiscountPriceDTO->travelPaymentDate,
                $travelDiscountPriceDTO->travelPrice
            );
        } elseif ($this->travelStartingFromFifteenthJanuaryNextYearDiscount->isApplicable(
            $travelDiscountPriceDTO->travelStartDate
        )) {
            $discount = $this->travelStartingFromFifteenthJanuaryNextYearDiscount->calculate(
                $travelDiscountPriceDTO->travelPaymentDate,
                $travelDiscountPriceDTO->travelPrice
            );
        }

        return ($discount > self::MAX_EARLY_BOOKING_DISCOUNT) ? self::MAX_EARLY_BOOKING_DISCOUNT : $discount;
    }
}
