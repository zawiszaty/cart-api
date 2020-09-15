<?php

declare(strict_types=1);

namespace App\Module\Cart\Application\AddProductToCart;

use App\Infrastructure\CommandBus\CommandHandler;
use App\Module\Cart\Domain\Cart;
use App\Module\Cart\Domain\CartId;
use App\Module\Cart\Domain\CartRepositoryInterface;
use App\Module\Cart\Domain\Exception\CartException;
use App\Module\Cart\Domain\Product;
use App\Module\Cart\Domain\ProductAvailabilityFinder;
use App\Module\Cart\Domain\ProductId;
use App\Module\Cart\Domain\ProductPrice;
use App\Module\Catalog\Shared\CatalogApi;

final class AddProductToCartHandler extends CommandHandler
{
    private CartRepositoryInterface $cartRepository;

    private CatalogApi $catalogApi;

    private ProductAvailabilityFinder $availabilityFinder;

    public function __construct(CartRepositoryInterface $cartRepository, CatalogApi $catalogApi, ProductAvailabilityFinder $availabilityFinder)
    {
        $this->cartRepository = $cartRepository;
        $this->catalogApi = $catalogApi;
        $this->availabilityFinder = $availabilityFinder;
    }

    public function __invoke(AddProductToCartCommand $command): void
    {
        $cart = $this->cartRepository->get(CartId::fromString($command->getCartId()->toString()));

        if (false === $cart instanceof Cart) {
            throw CartException::fromMissingCart();
        }

        if ($this->availabilityFinder->isAvailable(ProductId::fromString($command->getProductId()->toString()))) {
            $product = $this->catalogApi->getProduct($command->getProductId());

            $cart->addProductToCart(new Product(
                ProductId::fromString($product->getProductId()->toString()),
                ProductPrice::fromString($product->getPrice()->getAmount(), $product->getPrice()->getCurrency()
                    ->getCode())
            ));
            $this->cartRepository->save($cart);

            return;
        }

        throw CartException::fromMissingProduct();
    }
}
