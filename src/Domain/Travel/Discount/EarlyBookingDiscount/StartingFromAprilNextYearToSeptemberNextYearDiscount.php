<?php

declare(strict_types=1);

namespace App\Domain\Travel\Discount\EarlyBookingDiscount;

use DateTimeImmutable;

class StartingFromAprilNextYearToSeptemberNextYearDiscount extends AbstractEarlyBookingDiscount
{
    private const DATE_TIME_FORMAT = 'Y-m-d';
    private const FIRST_DAY_OF_APRIL_DATE_STRING_IN_DATE_TIME_FORMAT = '-04-01';
    private const LAST_DAY_OF_SEPTEMBER_DATE_STRING_IN_DATE_TIME_FORMAT = '-09-30';
    private const LAST_DAY_OF_NOVEMBER_DATE_STRING_IN_DATE_TIME_FORMAT = '-11-30';
    private const LAST_DAY_OF_DECEMBER_DATE_STRING_IN_DATE_TIME_FORMAT = '-12-31';
    private const LAST_DAY_OF_JANUARY_DATE_STRING_IN_DATE_TIME_FORMAT = '-01-31';

    public function isApplicable(DateTimeImmutable $travelStartDate): bool
    {
        $firstDayOfAprilNextYear = DateTimeImmutable::createFromFormat(
            self::DATE_TIME_FORMAT,
            $this->nextYear . self::FIRST_DAY_OF_APRIL_DATE_STRING_IN_DATE_TIME_FORMAT
        );
        $lastDayOfSeptemberDayNextYear = DateTimeImmutable::createFromFormat(
            self::DATE_TIME_FORMAT,
            $this->nextYear . self::LAST_DAY_OF_SEPTEMBER_DATE_STRING_IN_DATE_TIME_FORMAT
        );

        return
            (int)$travelStartDate->diff($firstDayOfAprilNextYear)->format('%r%a') <= 0
            && (int)$travelStartDate->diff($lastDayOfSeptemberDayNextYear)->format('%r%a') >= 0;
    }

    protected function getSevenPercentDiscountLastDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(
            self::DATE_TIME_FORMAT,
            $this->currentYear . self::LAST_DAY_OF_NOVEMBER_DATE_STRING_IN_DATE_TIME_FORMAT
        );
    }

    protected function getFivePercentDiscountLastDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(
            self::DATE_TIME_FORMAT,
            $this->currentYear . self::LAST_DAY_OF_DECEMBER_DATE_STRING_IN_DATE_TIME_FORMAT
        );
    }

    protected function getThreePercentDiscountLastDate(): DateTimeImmutable
    {
        return DateTimeImmutable::createFromFormat(
            self::DATE_TIME_FORMAT,
            $this->nextYear . self::LAST_DAY_OF_JANUARY_DATE_STRING_IN_DATE_TIME_FORMAT
        );
    }
}
