<?php

declare(strict_types=1);

namespace Integration\Travel\Discount;

use App\Domain\Travel\Discount\TravelEarlyBookingDiscountCalculator;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TravelEarlyBookingDiscountTest extends KernelTestCase
{
    private int $currentYear;
    private int $nextYear;
    private TravelEarlyBookingDiscountCalculator $travelEarlyBookingDiscountCalculator;

    private const MAX_EARLY_BOOKING_DISCOUNT = 1500;

    public function testTravelWithLatePaymentReturnsZeroDiscount(): void
    {
        $aprilNextYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->nextYear . '-04-01');
        $initialTravelPrice = 1000;

        $februaryNextYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->nextYear . '-02-01');
        $actualDiscount = $this->travelEarlyBookingDiscountCalculator->calculate(
            $aprilNextYearDate,
            $februaryNextYearDate,
            $initialTravelPrice
        );

        self::assertSame(0, $actualDiscount);
    }

    public function testTravelDiscountNotMoreThanMaxDiscount(): void
    {
        $aprilNextYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->nextYear . '-04-01');
        $veryBigTravelPrice = 10000000;

        $novemberThisYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->currentYear . '-11-30');
        $actualDiscount = $this->travelEarlyBookingDiscountCalculator->calculate(
            $aprilNextYearDate,
            $novemberThisYearDate,
            $veryBigTravelPrice
        );

        self::assertSame(self::MAX_EARLY_BOOKING_DISCOUNT, $actualDiscount);
    }

    public function testTravelStartingInAprilNextYearSevenPercentDiscount(): void
    {
        $aprilNextYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->nextYear . '-04-01');
        $initialTravelPrice = 1000;

        $novemberThisYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->currentYear . '-11-30');
        $actualDiscount = $this->travelEarlyBookingDiscountCalculator->calculate(
            $aprilNextYearDate,
            $novemberThisYearDate,
            $initialTravelPrice
        );

        $expectedSevenPercentDiscount = (int)($initialTravelPrice * 0.07);
        self::assertSame($expectedSevenPercentDiscount, $actualDiscount);
    }

    public function testTravelStartingInOctoberThisYearFivePercentDiscount(): void
    {
        $octoberThisYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->currentYear . '-10-01');
        $initialTravelPrice = 1000;

        $aprilThisYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->currentYear . '-04-30');
        $actualDiscount = $this->travelEarlyBookingDiscountCalculator->calculate(
            $octoberThisYearDate,
            $aprilThisYearDate,
            $initialTravelPrice
        );

        $expectedFivePercentDiscount = (int)($initialTravelPrice * 0.05);
        self::assertSame($expectedFivePercentDiscount, $actualDiscount);
    }

    public function testTravelStartingFifteenthJanuaryNextYearThreePercentDiscount(): void
    {
        $fifteenthJanuaryNextYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->nextYear . '-01-15');
        $initialTravelPrice = 1000;

        $octoberThisYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->currentYear . '-10-31');
        $actualDiscount = $this->travelEarlyBookingDiscountCalculator->calculate(
            $fifteenthJanuaryNextYearDate,
            $octoberThisYearDate,
            $initialTravelPrice
        );

        $expectedThreePercentDiscount = (int)($initialTravelPrice * 0.03);
        self::assertSame($expectedThreePercentDiscount, $actualDiscount);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $container = self::getContainer();

        $this->currentYear = (int)(new DateTimeImmutable())->format('Y');
        $this->nextYear = $this->currentYear + 1;

        $this->travelEarlyBookingDiscountCalculator = $container->get(TravelEarlyBookingDiscountCalculator::class);
    }
}
