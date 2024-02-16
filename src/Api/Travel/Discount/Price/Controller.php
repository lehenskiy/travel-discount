<?php

declare(strict_types=1);

namespace App\Api\Travel\Discount\Price;

use App\Domain\Travel\Discount\Price\TravelDiscountPriceDTO;
use OpenApi\Attributes\JsonContent;
use OpenApi\Attributes\RequestBody;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use App\Domain\Travel\Discount\Price\TravelDiscountPriceCalculator;

class Controller extends AbstractController
{
    #[RequestBody(
        required: true,
        content: new JsonContent(
            example: [
                'price' => 10000,
                'birthDate' => '2002-05-22',
                'startDate' => '2025-04-15',
                'paymentDate' => '2024-11-10',
            ]
        ),
    )]
    #[Route(path: '/api/travel/discount/calculate', name: 'travel_discount_calculate', methods: ['POST'])]
    public function calculateTravelPrice(
        #[MapRequestPayload] TravelDiscountPriceDTO $travelDiscountDTO,
        TravelDiscountPriceCalculator $travelDiscountCalculator
    ): Response {
        $travelPriceWithDiscount = $travelDiscountCalculator->calculateTravelWithDiscountPrice($travelDiscountDTO);

        return $this->json(['price' => $travelPriceWithDiscount]);
    }
}
