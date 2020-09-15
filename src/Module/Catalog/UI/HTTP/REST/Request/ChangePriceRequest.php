<?php

declare(strict_types=1);

namespace App\Module\Catalog\UI\HTTP\REST\Request;

use App\Infrastructure\Symfony\RequestDTOInterface;
use Symfony\Component\HttpFoundation\Request;

final class ChangePriceRequest implements RequestDTOInterface
{
    private string $price;

    private string $currency;

    public function __construct(Request $request)
    {
        $this->price = $request->request->get('price');
        $this->currency = $request->request->get('currency');
    }

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
