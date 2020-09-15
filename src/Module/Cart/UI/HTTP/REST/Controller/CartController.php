<?php

declare(strict_types=1);

namespace App\Module\Cart\UI\HTTP\REST\Controller;

use App\Infrastructure\CommandBus\CommandBus;
use App\Module\Cart\Application\AddProductToCart\AddProductToCartCommand;
use App\Module\Cart\Application\CartQuery\CartQuery;
use App\Module\Cart\Application\CreateCart\CreateCartCommand;
use App\Module\Cart\Application\RemoveProductFromCart\RemoveProductFromCartCommand;
use App\Module\Cart\UI\HTTP\REST\Request\AddProductToCartRequest;
use App\Module\Cart\UI\HTTP\REST\Request\CreateCartRequest;
use App\Module\Cart\UI\HTTP\REST\Request\RemoveProductFromCartRequest;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CartController extends AbstractController
{
    private CommandBus $bus;

    private CartQuery $cartQuery;

    public function __construct(CommandBus $bus, CartQuery $cartQuery)
    {
        $this->bus = $bus;
        $this->cartQuery = $cartQuery;
    }

    /**
     * @Route("/api/v1/cart", name="create_cart", methods={"POST"})
     */
    public function createCartAction(CreateCartRequest $request): Response
    {
        $command = new CreateCartCommand(Uuid::fromString($request->getUserId()));

        $this->bus->handle($command);

        return new JsonResponse();
    }

    /**
     * @Route("/api/v1/cart/{cartId}/add", name="add_product_to_cart", methods={"PATCH"})
     */
    public function addProductToCart(AddProductToCartRequest $request, string $cartId): Response
    {
        $command = new AddProductToCartCommand(
            Uuid::fromString($cartId),
            Uuid::fromString($request->getProductId())
        );

        $this->bus->handle($command);

        return new JsonResponse();
    }

    /**
     * @Route("/api/v1/cart/{cartId}/remove", name="remove_product_to_cart", methods={"PATCH"})
     */
    public function removeProductToCart(RemoveProductFromCartRequest $request, string $cartId): Response
    {
        $command = new RemoveProductFromCartCommand(
            Uuid::fromString($cartId),
            Uuid::fromString($request->getProductId())
        );

        $this->bus->handle($command);

        return new JsonResponse();
    }

    /**
     * @Route("/api/v1/cart/{cartId}", name="list_cart", methods={"GET"})
     */
    public function listAction(string $cartId): Response
    {
        $cart = $this->cartQuery->getCart(Uuid::fromString($cartId));

        return new JsonResponse($cart);
    }
}
