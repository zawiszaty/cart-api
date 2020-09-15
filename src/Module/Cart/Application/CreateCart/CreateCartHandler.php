<?php

declare(strict_types=1);

namespace App\Module\Cart\Application\CreateCart;

use App\Infrastructure\CommandBus\CommandHandler;
use App\Module\Cart\Domain\Cart;
use App\Module\Cart\Domain\CartRepositoryInterface;

final class CreateCartHandler extends CommandHandler
{
    private CartRepositoryInterface $cartRepository;

    public function __construct(CartRepositoryInterface $cartRepository)
    {
        $this->cartRepository = $cartRepository;
    }

    public function __invoke(CreateCartCommand $cartCommand): void
    {
        $cart = Cart::create($cartCommand->getUserId());

        $this->cartRepository->save($cart);
    }
}
