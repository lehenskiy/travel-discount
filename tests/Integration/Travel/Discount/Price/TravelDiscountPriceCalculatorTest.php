<?php

declare(strict_types=1);

namespace Integration\Travel\Discount\Price;

use App\Api\Travel\Discount\Price\TravelDiscountPriceDTO;
use App\Domain\Travel\Discount\Price\TravelDiscountPriceCalculator;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TravelDiscountPriceCalculatorTest extends KernelTestCase
{
    private TravelDiscountPriceCalculator $travelDiscountPriceCalculator;

    public function testEarlyBookingDiscountAppliesToDiscountedByChildDiscountPrice(): void
    {
        $inputPrice = 10000;
        $currentYear = (int)(new DateTimeImmutable())->format('Y');

        $inputData = new TravelDiscountPriceDTO(
            $inputPrice,
            ($currentYear - 3) . '-01-01',
            ($currentYear + 1) . '-04-01',
            $currentYear . '-11-30' // 7 percent discount payment date
        );

        $actualPrice = $this->travelDiscountPriceCalculator->calculateTravelWithDiscountPrice($inputData);
        $expectedPrice = $inputPrice - (int)($inputPrice * 0.8);
        $expectedPrice -= (int)($expectedPrice * 0.07);

        $this->assertSame($expectedPrice, $actualPrice);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $container = self::getContainer();

        $this->travelDiscountPriceCalculator = $container->get(TravelDiscountPriceCalculator::class);
    }
}
