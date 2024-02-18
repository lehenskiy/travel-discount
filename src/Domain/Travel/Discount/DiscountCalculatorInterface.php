<?php

declare(strict_types=1);

namespace App\Domain\Travel\Discount;

use App\Api\Travel\Discount\Price\TravelDiscountPriceDTO;

interface DiscountCalculatorInterface
{
    public function calculate(TravelDiscountPriceDTO $travelDiscountPriceDTO);
}
