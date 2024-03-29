<?php

declare(strict_types=1);

namespace App\Tests\Unit\Travel\Discount;

use App\Api\Travel\Discount\Price\TravelDiscountPriceDTO;
use App\Domain\Travel\Discount\ChildDiscountCalculator;
use DateInterval;
use PHPUnit\Framework\TestCase;

class TravelChildDiscountTest extends TestCase
{
    private const ADULT_AGE = 18;
    private const MAX_6_YEARS_OLD_CLIENT_DISCOUNT = 4500;

    private ChildDiscountCalculator $travelChildDiscount;

    public function testCalculateWithClientAgeLessThanThreeReturnsZero(): void
    {
        $startDate = new \DateTimeImmutable();
        // see https://en.wikipedia.org/wiki/ISO_8601#Durations
        $clientBirthDateWithLessThanThreeYearsOldAge = $startDate->sub(new DateInterval('P2Y'));
        $inputDTO = new TravelDiscountPriceDTO(
            1000,
            $clientBirthDateWithLessThanThreeYearsOldAge->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $startDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $startDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
        );

        $result = $this->travelChildDiscount->calculate($inputDTO);

        $this->assertSame(0, $result);
    }

    public function testCalculateWithAdultClientReturnsZero(): void
    {
        $startDate = new \DateTimeImmutable();
        $adultClientBirthDate = $startDate->sub(new DateInterval('P' . self::ADULT_AGE . 'Y'));
        $inputDTO = new TravelDiscountPriceDTO(
            1000,
            $adultClientBirthDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $startDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $startDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
        );

        $result = $this->travelChildDiscount->calculate($inputDTO);

        $this->assertSame(0, $result);
    }

    public function testCalculateWithClientSixYearsOldAgeNotReturnsMoreThanMaxDiscount(): void
    {
        $startDate = new \DateTimeImmutable();
        $sixYearsOldClient = $startDate->sub(new DateInterval('P6Y'));
        $veryBigTravelPrice = 100000000;
        $inputDTO = new TravelDiscountPriceDTO(
            $veryBigTravelPrice,
            $sixYearsOldClient->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $startDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $startDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
        );

        $result = $this->travelChildDiscount->calculate($inputDTO);

        $this->assertSame(self::MAX_6_YEARS_OLD_CLIENT_DISCOUNT, $result);
    }

    protected function setUp(): void
    {
        $this->travelChildDiscount = new ChildDiscountCalculator();
    }
}
