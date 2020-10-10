<?php

declare(strict_types=1);

namespace App\Module\Catalog\UI\HTTP\REST\Controller;

use App\Infrastructure\CommandBus\CommandBus;
use App\Module\Catalog\Application\ChangeProductName\ChangeProductNameCommand;
use App\Module\Catalog\Application\ChangeProductPrice\ChangeProductPriceCommand;
use App\Module\Catalog\Application\CreateProduct\CreateProductCommand;
use App\Module\Catalog\Application\ListProductQuery\ListProductQuery;
use App\Module\Catalog\Application\RemoveProduct\RemoveProductCommand;
use App\Module\Catalog\UI\HTTP\REST\Request\ChangeNameRequest;
use App\Module\Catalog\UI\HTTP\REST\Request\ChangePriceRequest;
use App\Module\Catalog\UI\HTTP\REST\Request\CreateProductRequest;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ProductController extends AbstractController
{
    private CommandBus $bus;

    private ListProductQuery $listProductQuery;

    public function __construct(CommandBus $bus, ListProductQuery $listProductQuery)
    {
        $this->bus = $bus;
        $this->listProductQuery = $listProductQuery;
    }

    /**
     * @Route("/api/v1/product", name="create_product", methods={"POST"})
     */
    public function createAction(CreateProductRequest $request): Response
    {
        $command = new CreateProductCommand(
            $request->getName(),
            $request->getPrice(),
            $request->getCurrency()
        );

        $this->bus->handle($command);

        return new JsonResponse();
    }

    /**
     * @Route("/api/v1/product/{productId}/name", name="change_product_name", methods={"PATCH"})
     */
    public function changeNameAction(ChangeNameRequest $request, string $productId): Response
    {
        $command = new ChangeProductNameCommand(
            Uuid::fromString($productId),
            $request->getName(),
        );

        $this->bus->handle($command);

        return new JsonResponse();
    }

    /**
     * @Route("/api/v1/product/{productId}/price", name="change_product_price", methods={"PATCH"})
     */
    public function changePriceAction(ChangePriceRequest $request, string $productId): Response
    {
        $command = new ChangeProductPriceCommand(
            Uuid::fromString($productId),
            $request->getPrice(),
            $request->getCurrency(),
        );

        $this->bus->handle($command);

        return new JsonResponse();
    }

    /**
     * @Route("/api/v1/product/{productId}", name="remove_product", methods={"DELETE"})
     */
    public function removeAction(string $productId): Response
    {
        $command = new RemoveProductCommand(
            Uuid::fromString($productId),
        );

        $this->bus->handle($command);

        return new JsonResponse();
    }

    /**
     * @Route("/api/v1/products", name="list_product", methods={"GET"})
     */
    public function listAction(Request $request): Response
    {
        $page = $request->query->get('page', 1);
        $limit = $request->query->get('limit', 3);
        $products = $this->listProductQuery->list($page, $limit);

        return new JsonResponse($products);
    }
}
