<?php

declare(strict_types=1);

namespace App\Domain\Travel\Discount\Price;

use App\Api\Travel\Discount\Price\TravelDiscountPriceDTO;
use App\Domain\Travel\Discount\TravelChildDiscount;
use App\Domain\Travel\Discount\TravelEarlyBookingDiscountCalculator;

class TravelDiscountPriceCalculator
{
    public function __construct(
        private TravelChildDiscount $childDiscount,
        private TravelEarlyBookingDiscountCalculator $earlyBookingDiscount
    ) {
    }

    public function calculateTravelWithDiscountPrice(TravelDiscountPriceDTO $travelDiscountPriceDTO): int
    {
        $travelPrice = $travelDiscountPriceDTO->travelPrice;
        $travelPrice -= $this->childDiscount->calculate(
            $travelDiscountPriceDTO->clientBirthDate,
            $travelDiscountPriceDTO->travelPaymentDate,
            $travelPrice
        );
        $travelPrice -= $this->earlyBookingDiscount->calculate(
            $travelDiscountPriceDTO->travelStartDate,
            $travelDiscountPriceDTO->travelPaymentDate,
            $travelPrice
        );

        return $travelPrice;
    }
}
