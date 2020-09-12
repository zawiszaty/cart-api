<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony;

use App\Module\Cart\Application\CreateCart\CreateCardHandler;
use App\Module\Cart\Domain\CartId;
use App\Module\Cart\Domain\CartRepositoryInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class DupaCommand extends Command
{
    protected static $defaultName = 'app:dupa';

    private CreateCardHandler $createCardHandler;

    private CartRepositoryInterface $cartRepository;

    public function __construct(string $name = null, CreateCardHandler $createCardHandler, CartRepositoryInterface $cartRepository)
    {
        parent::__construct($name);
        $this->createCardHandler = $createCardHandler;
        $this->cartRepository = $cartRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
//        $userId = Uuid::uuid4();
//        ($this->createCardHandler)(new CreateCartCommand($userId));

        $cart = $this->cartRepository->get(CartId::fromString('018bdf6b-b822-430a-a3bf-45cd65e319ad'));
        dump($cart);

        return Command::SUCCESS;
    }
}
