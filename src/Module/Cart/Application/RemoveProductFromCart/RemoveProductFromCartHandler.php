<?php

declare(strict_types=1);

namespace App\Module\Cart\Application\RemoveProductFromCart;

use App\Infrastructure\CommandBus\CommandHandler;
use App\Module\Cart\Domain\Cart;
use App\Module\Cart\Domain\CartId;
use App\Module\Cart\Domain\CartRepositoryInterface;
use App\Module\Cart\Domain\Exception\CartException;
use App\Module\Cart\Domain\Product;
use App\Module\Cart\Domain\ProductId;
use App\Module\Cart\Domain\ProductPrice;
use App\Module\Catalog\Shared\CatalogApi;

final class RemoveProductFromCartHandler extends CommandHandler
{
    private CartRepositoryInterface $cartRepository;

    private CatalogApi $catalogApi;

    public function __construct(CartRepositoryInterface $cartRepository, CatalogApi $catalogApi)
    {
        $this->cartRepository = $cartRepository;
        $this->catalogApi = $catalogApi;
    }

    public function __invoke(RemoveProductFromCartCommand $command): void
    {
        $cart = $this->cartRepository->get(CartId::fromUuid($command->getCartId()));
        $product = $this->catalogApi->getProduct($command->getProductId());

        if (false === $cart instanceof Cart) {
            throw CartException::fromMissingCart();
        }
        $cart->removeProductFromCart(new Product(
            ProductId::fromUuid($product->getProductId()),
            ProductPrice::fromString($product->getPriceAmount(), $product->getCurrencyCode())
        ));
        $this->cartRepository->save($cart);
    }
}
