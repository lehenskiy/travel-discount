<?php

declare(strict_types=1);

namespace Unit\Travel\Discount;

use App\Domain\Travel\Discount\TravelChildDiscount;
use PHPUnit\Framework\TestCase;

class TravelChildDiscountTest extends TestCase
{
    private TravelChildDiscount $travelChildDiscount;
    private const ADULT_AGE = 18;
    private const MAX_6_YEARS_OLD_CLIENT_DISCOUNT = 4500;

    public function testCalculateWithClientAgeLessThanThreeReturnsZero(): void
    {
        $currentYear = (int)(new \DateTimeImmutable())->format('Y');
        $clienBirthDateWithLessThanThreeYearsOldAge = \DateTimeImmutable::createFromFormat(
            'Y',
            (string)($currentYear - 2)
        );

        $result = $this->travelChildDiscount->calculate(
            $clienBirthDateWithLessThanThreeYearsOldAge,
            1000
        );

        $this->assertSame(0, $result);
    }

    public function testCalculateWithAdultClientReturnsZero(): void
    {
        $currentYear = (int)(new \DateTimeImmutable())->format('Y');
        $adultClient = \DateTimeImmutable::createFromFormat(
            'Y',
            (string)($currentYear - self::ADULT_AGE)
        );

        $result = $this->travelChildDiscount->calculate(
            $adultClient,
            1000
        );

        $this->assertSame(0, $result);
    }

    public function testCalculateWithClientSixYearsOldAgeNotReturnsMoreThanMaxDiscount(): void
    {
        $currentYear = (int)(new \DateTimeImmutable())->format('Y');
        $sixYearsOldClient = \DateTimeImmutable::createFromFormat(
            'Y',
            (string)($currentYear - 6)
        );

        $veryBigTravelPrice = 100000000;
        $result = $this->travelChildDiscount->calculate(
            $sixYearsOldClient,
            $veryBigTravelPrice
        );

        $this->assertSame(self::MAX_6_YEARS_OLD_CLIENT_DISCOUNT, $result);
    }

    protected function setUp(): void
    {
        $this->travelChildDiscount = new TravelChildDiscount();
    }
}
