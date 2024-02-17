<?php

declare(strict_types=1);

namespace Tests\Unit\Travel\Discount;

use App\Domain\Travel\Discount\TravelChildDiscount;
use DateInterval;
use PHPUnit\Framework\TestCase;

class TravelChildDiscountTest extends TestCase
{
    private TravelChildDiscount $travelChildDiscount;
    private const ADULT_AGE = 18;
    private const MAX_6_YEARS_OLD_CLIENT_DISCOUNT = 4500;

    public function testCalculateWithClientAgeLessThanThreeReturnsZero(): void
    {
        $paymentDate = new \DateTimeImmutable();
        // see https://en.wikipedia.org/wiki/ISO_8601#Durations
        $clientBirthDateWithLessThanThreeYearsOldAge = $paymentDate->sub(new DateInterval('P2Y'));

        $result = $this->travelChildDiscount->calculate(
            $clientBirthDateWithLessThanThreeYearsOldAge,
            $paymentDate,
            1000
        );

        $this->assertSame(0, $result);
    }

    public function testCalculateWithAdultClientReturnsZero(): void
    {
        $paymentDate = new \DateTimeImmutable();
        $adultClient = $paymentDate->sub(new DateInterval('P' . self::ADULT_AGE . 'Y'));

        $result = $this->travelChildDiscount->calculate(
            $adultClient,
            $paymentDate,
            1000
        );

        $this->assertSame(0, $result);
    }

    public function testCalculateWithClientSixYearsOldAgeNotReturnsMoreThanMaxDiscount(): void
    {
        $paymentDate = new \DateTimeImmutable();
        $sixYearsOldClient = $paymentDate->sub(new DateInterval('P6Y'));

        $veryBigTravelPrice = 100000000;
        $result = $this->travelChildDiscount->calculate(
            $sixYearsOldClient,
            $paymentDate,
            $veryBigTravelPrice
        );

        $this->assertSame(self::MAX_6_YEARS_OLD_CLIENT_DISCOUNT, $result);
    }

    protected function setUp(): void
    {
        $this->travelChildDiscount = new TravelChildDiscount();
    }
}
