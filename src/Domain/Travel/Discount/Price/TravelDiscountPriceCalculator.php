<?php

declare(strict_types=1);

namespace App\Domain\Travel\Discount\Price;

use App\Api\Travel\Discount\Price\TravelDiscountPriceDTO;
use App\Domain\Travel\Discount\ChildDiscountCalculator;
use App\Domain\Travel\Discount\EarlyBookingDiscountCalculator;

class TravelDiscountPriceCalculator
{
    public function __construct(
        private ChildDiscountCalculator $childDiscount,
        private EarlyBookingDiscountCalculator $earlyBookingDiscount
    ) {
    }

    public function calculateTravelWithDiscountPrice(TravelDiscountPriceDTO $travelDiscountPriceDTO): int
    {
        $travelPriceToCalculate = $travelDiscountPriceDTO->travelPrice;
        $travelPriceToCalculate -= $this->childDiscount->calculate($travelDiscountPriceDTO);
        $travelDiscountPriceDTOWithUpdatedPrice = new TravelDiscountPriceDTO(
            $travelPriceToCalculate,
            $travelDiscountPriceDTO->clientBirthDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $travelDiscountPriceDTO->travelStartDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $travelDiscountPriceDTO->travelPaymentDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
        );
        $travelPriceToCalculate -= $this->earlyBookingDiscount->calculate($travelDiscountPriceDTOWithUpdatedPrice);

        return $travelPriceToCalculate;
    }
}
