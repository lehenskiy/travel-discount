<?php

declare(strict_types=1);

namespace App\Api\Travel\Discount\Price;

use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;

readonly class TravelDiscountPriceDTO
{
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
        invalidDateTimeMessage: 'Invalid date(format: dd-mm-YYYY)',
        min: 'first day of January',
        max: 'last day of December next year',
    )]
    public DateTimeImmutable|false $travelStartDate;

    #[Assert\AtLeastOneOf(constraints: [
        new Assert\Range(
            notInRangeMessage: 'Payment date value should be from current year or not later than next year',
            invalidDateTimeMessage: 'Invalid date(format: dd-mm-YYYY)',
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
        $this->clientBirthDate = DateTimeImmutable::createFromFormat('Y-m-d', $birthDate);
        $this->travelStartDate = ($startDate === null)
            ? new DateTimeImmutable()
            : DateTimeImmutable::createFromFormat('Y-m-d', $startDate);
        $this->travelPaymentDate = ($paymentDate === null)
            ? null
            : DateTimeImmutable::createFromFormat('Y-m-d', $paymentDate);
    }
}
