<?php

declare(strict_types=1);

namespace App\Domain\Travel\Discount\TravelEarlyBookingDiscount;

use DateTimeImmutable;

class TravelStartingFromOctoberThisYearToFourteenthJanuaryNextYearDiscount extends AbstractTravelEarlyBookingDiscount
{
    private const DATE_TIME_IMMUTABLE_FORMAT = 'Y-m-d';
    private const FIRST_DAY_OF_OCTOBER_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT = '-10-01';
    private const FOURTEENTH_DAY_OF_JANUARY_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT = '-01-14';
    private const LAST_DAY_OF_MARCH_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT = '-03-31';
    private const LAST_DAY_OF_APRIL_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT = '-04-30';
    private const LAST_DAY_OF_MAY_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT = '-05-31';

    public function isApplicable(DateTimeImmutable $travelStartDate): bool
    {
        $firstDayOfOctoberThisYear = DateTimeImmutable::createFromFormat(
            self::DATE_TIME_IMMUTABLE_FORMAT,
            $this->currentYear . self::FIRST_DAY_OF_OCTOBER_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT
        );
        $fourteenthDayOfJanuaryNextYear = DateTimeImmutable::createFromFormat(
            self::DATE_TIME_IMMUTABLE_FORMAT,
            $this->nextYear . self::FOURTEENTH_DAY_OF_JANUARY_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT
        );

        return
            (int)$travelStartDate->diff($firstDayOfOctoberThisYear)->format('%r%a') <= 0
            && (int)$travelStartDate->diff($fourteenthDayOfJanuaryNextYear)->format('%r%a') >= 0;
    }

    protected function getSevenPercentDiscountLastDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(
            self::DATE_TIME_IMMUTABLE_FORMAT,
            $this->currentYear . self::LAST_DAY_OF_MARCH_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT
        );
    }

    protected function getFivePercentDiscountLastDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(
            self::DATE_TIME_IMMUTABLE_FORMAT,
            $this->currentYear . self::LAST_DAY_OF_APRIL_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT
        );
    }

    protected function getThreePercentDiscountLastDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(
            self::DATE_TIME_IMMUTABLE_FORMAT,
            $this->currentYear . self::LAST_DAY_OF_MAY_DATE_STRING_IN_DATE_TIME_IMMUTABLE_FORMAT
        );
    }
}