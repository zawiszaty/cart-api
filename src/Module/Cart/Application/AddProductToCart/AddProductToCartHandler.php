<?php

declare(strict_types=1);


namespace App\Module\Cart\Application\AddProductToCart;


use App\Module\Cart\Domain\CartId;
use App\Module\Cart\Domain\CartRepositoryInterface;
use App\Module\Cart\Domain\Product;
use App\Module\Cart\Domain\ProductId;
use App\Module\Cart\Domain\ProductPrice;
use App\Module\Catalog\Shared\CatalogApi;

final class AddProductToCartHandler
{
    private CartRepositoryInterface $cartRepository;

    private CatalogApi $catalogApi;

    public function __construct(CartRepositoryInterface $cartRepository, CatalogApi $catalogApi)
    {
        $this->cartRepository = $cartRepository;
        $this->catalogApi     = $catalogApi;
    }

    public function __invoke(AddProductToCartCommand $command): void
    {
        $cart = $this->cartRepository->get(CartId::fromString($command->getProductId()->toString()));

        if ($this->catalogApi->isAvailable($command->getProductId()))
        {
            $product = $this->catalogApi->getProduct($command->getProductId());

            $cart->addProductToCart(new Product(
                ProductId::fromString($product->getProductId()->toString()),
                ProductPrice::fromString($product->getPrice()->getAmount(), $product->getPrice()->getCurrency()
                    ->getCode())
            ));
            $this->cartRepository->save($cart);
        }
    }
}
