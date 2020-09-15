<?php

declare(strict_types=1);

namespace App\Module\Catalog\UI\HTTP\REST\Request;

use App\Infrastructure\Symfony\RequestDTOInterface;
use Symfony\Component\HttpFoundation\Request;

final class CreateProductRequest implements RequestDTOInterface
{
    private string $name;

    private float $price;

    private string $currency;

    public function __construct(Request $request)
    {
        $this->name = $request->request->get('name');
        $this->price = $request->request->get('price');
        $this->currency = $request->request->get('currency');
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
