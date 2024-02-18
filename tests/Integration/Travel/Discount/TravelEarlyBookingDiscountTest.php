<?php

declare(strict_types=1);

namespace Tests\Integration\Travel\Discount;

use App\Api\Travel\Discount\Price\TravelDiscountPriceDTO;
use App\Domain\Travel\Discount\EarlyBookingDiscountCalculator;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TravelEarlyBookingDiscountTest extends KernelTestCase
{
    private const MAX_EARLY_BOOKING_DISCOUNT = 1500;

    private int $currentYear;
    private int $nextYear;
    private EarlyBookingDiscountCalculator $travelEarlyBookingDiscountCalculator;

    public function testTravelWithLatePaymentReturnsZeroDiscount(): void
    {
        $initialTravelPrice = 1000;
        $aprilNextYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->nextYear . '-04-01');
        $februaryNextYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->nextYear . '-02-01');
        $inputDTO = new TravelDiscountPriceDTO(
            $initialTravelPrice,
            $aprilNextYearDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $aprilNextYearDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $februaryNextYearDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
        );

        $actualDiscount = $this->travelEarlyBookingDiscountCalculator->calculate($inputDTO);

        self::assertSame(0, $actualDiscount);
    }

    public function testTravelDiscountNotMoreThanMaxDiscount(): void
    {
        $veryBigTravelPrice = 10000000;
        $aprilNextYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->nextYear . '-04-01');
        $novemberThisYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->currentYear . '-11-30');
        $inputDTO = new TravelDiscountPriceDTO(
            $veryBigTravelPrice,
            $aprilNextYearDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $aprilNextYearDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $novemberThisYearDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
        );

        $actualDiscount = $this->travelEarlyBookingDiscountCalculator->calculate($inputDTO);

        self::assertSame(self::MAX_EARLY_BOOKING_DISCOUNT, $actualDiscount);
    }

    public function testTravelStartingInAprilNextYearSevenPercentDiscount(): void
    {
        $initialTravelPrice = 1000;
        $aprilNextYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->nextYear . '-04-01');
        $novemberThisYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->currentYear . '-11-30');
        $inputDTO = new TravelDiscountPriceDTO(
            $initialTravelPrice,
            $aprilNextYearDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $aprilNextYearDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $novemberThisYearDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
        );

        $actualDiscount = $this->travelEarlyBookingDiscountCalculator->calculate($inputDTO);

        $expectedSevenPercentDiscount = (int)($initialTravelPrice * 0.07);
        self::assertSame($expectedSevenPercentDiscount, $actualDiscount);
    }

    public function testTravelStartingInOctoberThisYearFivePercentDiscount(): void
    {
        $initialTravelPrice = 1000;
        $octoberThisYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->currentYear . '-10-01');
        $aprilThisYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->currentYear . '-04-30');
        $inputDTO = new TravelDiscountPriceDTO(
            $initialTravelPrice,
            $octoberThisYearDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $octoberThisYearDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $aprilThisYearDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
        );

        $actualDiscount = $this->travelEarlyBookingDiscountCalculator->calculate($inputDTO);

        $expectedFivePercentDiscount = (int)($initialTravelPrice * 0.05);
        self::assertSame($expectedFivePercentDiscount, $actualDiscount);
    }

    public function testTravelStartingFifteenthJanuaryNextYearThreePercentDiscount(): void
    {
        $initialTravelPrice = 1000;
        $fifteenthJanuaryNextYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->nextYear . '-01-15');
        $octoberThisYearDate = DateTimeImmutable::createFromFormat('Y-m-d', $this->currentYear . '-10-31');
        $inputDTO = new TravelDiscountPriceDTO(
            $initialTravelPrice,
            $fifteenthJanuaryNextYearDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $fifteenthJanuaryNextYearDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
            $octoberThisYearDate->format(TravelDiscountPriceDTO::DATE_TIME_INPUT_FORMAT),
        );

        $actualDiscount = $this->travelEarlyBookingDiscountCalculator->calculate($inputDTO);

        $expectedThreePercentDiscount = (int)($initialTravelPrice * 0.03);
        self::assertSame($expectedThreePercentDiscount, $actualDiscount);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $container = self::getContainer();

        $this->currentYear = (int)(new DateTimeImmutable())->format('Y');
        $this->nextYear = $this->currentYear + 1;

        $this->travelEarlyBookingDiscountCalculator = $container->get(EarlyBookingDiscountCalculator::class);
    }
}
