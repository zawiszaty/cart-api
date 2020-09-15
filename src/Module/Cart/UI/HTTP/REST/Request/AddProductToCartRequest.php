<?php

declare(strict_types=1);

namespace App\Module\Cart\UI\HTTP\REST\Request;

use App\Infrastructure\Symfony\RequestDTOInterface;
use Symfony\Component\HttpFoundation\Request;

final class AddProductToCartRequest implements RequestDTOInterface
{
    private string $productId;

    public function __construct(Request $request)
    {
        $this->productId = $request->request->get('product_id');
    }

    public function getProductId(): string
    {
        return $this->productId;
    }
}
