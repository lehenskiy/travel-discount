<?php

declare(strict_types=1);

namespace App\Api\Travel\Discount\Price;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

readonly class TravelDiscountPriceDTO
{
    public const DATE_TIME_INPUT_FORMAT = 'Y-m-d';

    #[Assert\GreaterThanOrEqual(1000, message: 'Price should be more than 1000')]
    public int $travelPrice;

    #[Assert\LessThanOrEqual('today', message: 'Birth date value should be less than today')]
    #[Assert\LessThanOrEqual(
        propertyPath: 'travelPaymentDate',
        message: 'Birth date should be less than or equal travel payment date',
    )]
    public DateTimeImmutable|false $clientBirthDate;

    #[Assert\Range(
        notInRangeMessage: 'Start date value should be from current year or not later than next year',
        invalidDateTimeMessage: 'Invalid date(format: yyyy-mm-dd)',
        min: 'first day of January',
        max: 'last day of December next year',
    )]
    public DateTimeImmutable|false $travelStartDate;

    #[Assert\AtLeastOneOf(constraints: [
        new Assert\Range(
            notInRangeMessage: 'Payment date value should be from current year or not later than next year',
            invalidDateTimeMessage: 'Invalid date(format: yyyy-mm-dd)',
            min: 'first day of January',
            max: 'last day of December next year',
        ),
        new Assert\IsNull(),
    ])]
    #[Assert\AtLeastOneOf(constraints: [
        new Assert\LessThanOrEqual(
            propertyPath: 'travelStartDate',
            message: 'Payment date should be less than or equal travel start date',
        ),
        new Assert\IsNull(),
    ])]
    public DateTimeImmutable|false|null $travelPaymentDate;

    public function __construct(
        int $price,
        string $birthDate,
        ?string $startDate,
        ?string $paymentDate,
    ) {
        $this->travelPrice = $price;
        $this->clientBirthDate = DateTimeImmutable::createFromFormat(self::DATE_TIME_INPUT_FORMAT, $birthDate);
        $this->travelStartDate = ($startDate === null)
            ? new DateTimeImmutable()
            : DateTimeImmutable::createFromFormat(self::DATE_TIME_INPUT_FORMAT, $startDate);
        $this->travelPaymentDate = ($paymentDate === null)
            ? null
            : DateTimeImmutable::createFromFormat(self::DATE_TIME_INPUT_FORMAT, $paymentDate);
    }
}
