<?php

declare(strict_types=1);

namespace App\Domain\Travel\Discount\TravelEarlyBookingDiscount;

use DateTimeImmutable;

class TravelStartingFromFifteenthJanuaryNextYearDiscount extends AbstractTravelEarlyBookingDiscount
{
    private const DATE_TIME_IMMUTABLE_FORMAT = 'Y-m-d';
    private const FIFTEENTH_DAY_OF_JANUARY_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT = '-01-15';
    private const LAST_DAY_OF_AUGUST_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT = '-08-31';
    private const LAST_DAY_OF_SEPTEMBER_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT = '-09-30';
    private const LAST_DAY_OF_OCTOBER_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT = '-10-31';

    public function isApplicable(DateTimeImmutable $travelStartDate): bool
    {
        $fifteenthDayOfJanuaryNextYear = DateTimeImmutable::createFromFormat(
            self::DATE_TIME_IMMUTABLE_FORMAT,
            $this->nextYear . self::FIFTEENTH_DAY_OF_JANUARY_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT
        );

        return (int)$travelStartDate->diff($fifteenthDayOfJanuaryNextYear)->format('%r%a') <= 0;
    }

    protected function getSevenPercentDiscountLastDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(
            self::DATE_TIME_IMMUTABLE_FORMAT,
            $this->currentYear . self::LAST_DAY_OF_AUGUST_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT
        );
    }

    protected function getFivePercentDiscountLastDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(
            self::DATE_TIME_IMMUTABLE_FORMAT,
            $this->currentYear . self::LAST_DAY_OF_SEPTEMBER_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT
        );
    }

    protected function getThreePercentDiscountLastDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(
            self::DATE_TIME_IMMUTABLE_FORMAT,
            $this->currentYear . self::LAST_DAY_OF_OCTOBER_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT
        );
    }
}
