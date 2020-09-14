<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony;

use App\Module\Cart\Application\AddProductToCart\AddProductToCartCommand;
use App\Module\Cart\Application\AddProductToCart\AddProductToCartHandler;
use App\Module\Cart\Application\CreateCart\CreateCardHandler;
use App\Module\Cart\Application\CreateCart\CreateCartCommand;
use App\Module\Cart\Domain\CartId;
use App\Module\Cart\Domain\CartRepositoryInterface;
use App\Module\Catalog\Application\CreateProduct\CreateProductCommand;
use App\Module\Catalog\Application\CreateProduct\CreateProductHandler;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DupaCommand extends Command
{
    protected static $defaultName = 'app:dupa';

    private CreateCardHandler $createCardHandler;

    private CartRepositoryInterface $cartRepository;

    private CreateProductHandler $createProductHandler;

    private AddProductToCartHandler $addProductToCartHandler;

    public function __construct(
        string $name = null,
        CreateCardHandler $createCardHandler,
        CartRepositoryInterface $cartRepository,
        CreateProductHandler $createProductHandler,
        AddProductToCartHandler $addProductToCartHandler
    )
    {
        parent::__construct($name);
        $this->createCardHandler = $createCardHandler;
        $this->cartRepository = $cartRepository;
        $this->createProductHandler = $createProductHandler;
        $this->addProductToCartHandler = $addProductToCartHandler;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        Uuid::fromString('625fb116-2efa-481b-a240-69124eee8f70')
//        $userId = Uuid::uuid4();
//        ($this->createCardHandler)(new CreateCartCommand($userId));
//        ($this->createProductHandler)(new CreateProductCommand(
//            'test',
//            20.0,
//            'PLN'
//        ));

//        $cart = $this->cartRepository->get(CartId::fromString('2f8c29d6-02b1-4e7f-a838-2d3181510601'));
//        dump($cart);

        ($this->addProductToCartHandler)(new AddProductToCartCommand(
            Uuid::fromString('2f8c29d6-02b1-4e7f-a838-2d3181510601'),
            Uuid::fromString('7bbc1554-d645-4169-9ec6-99eafd1e81d5')
        ));

        return Command::SUCCESS;
    }
}
