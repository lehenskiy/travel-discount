<?php

declare(strict_types=1);

namespace App\Domain\Travel\Discount\TravelEarlyBookingDiscount;

use DateTimeImmutable;

abstract class AbstractTravelEarlyBookingDiscount
{
    protected readonly int $currentYear;
    protected readonly int $nextYear;

    private const SEVEN_PERCENTS_DISCOUNT_MULTIPLIER = 0.07;
    private const FIVE_PERCENTS_DISCOUNT_MULTIPLIER = 0.05;
    private const THREE_PERCENTS_DISCOUNT_MULTIPLIER = 0.03;

    public function __construct()
    {
        $this->currentYear = (int)(new DateTimeImmutable())->format('Y');
        $this->nextYear = $this->currentYear + 1;
    }

    abstract public function isApplicable(DateTimeImmutable $travelStartDate): bool;

    public function calculate(DateTimeImmutable $travelPaymentDate, int $travelPrice): int
    {
        if ((int)$travelPaymentDate->diff($this->getSevenPercentDiscountLastDate())->format('%r%a') >= 0) {
            return $this->calculateSevenPercentDiscount($travelPrice);
        }
        if ((int)$travelPaymentDate->diff($this->getFivePercentDiscountLastDate())->format('%r%a') >= 0) {
            return $this->calculateFivePercentDiscount($travelPrice);
        }
        if ((int)$travelPaymentDate->diff($this->getThreePercentDiscountLastDate())->format('%r%a') >= 0) {
            return $this->calculateThreePercentDiscount($travelPrice);
        }

        return 0;
    }

    abstract protected function getSevenPercentDiscountLastDate(): DateTimeImmutable;

    abstract protected function getFivePercentDiscountLastDate(): DateTimeImmutable;

    abstract protected function getThreePercentDiscountLastDate(): DateTimeImmutable;

    private function calculateThreePercentDiscount(int $price): int
    {
        return (int)($price * self::THREE_PERCENTS_DISCOUNT_MULTIPLIER);
    }

    private function calculateFivePercentDiscount(int $price): int
    {
        return (int)($price * self::FIVE_PERCENTS_DISCOUNT_MULTIPLIER);
    }

    private function calculateSevenPercentDiscount(int $price): int
    {
        return (int)($price * self::SEVEN_PERCENTS_DISCOUNT_MULTIPLIER);
    }
}
