<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony;

use App\Infrastructure\EventStore\EventStoreEvent;
use App\Infrastructure\EventStore\EventStoreEventRepository;
use App\Infrastructure\EventStore\RabbitProjector;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class OutboxProjectorCommand extends Command
{
    protected static $defaultName = 'outbox:projection';

    private RabbitProjector $projector;

    private EventStoreEventRepository $eventRepository;

    public function __construct($name = null, RabbitProjector $projector, EventStoreEventRepository $eventRepository)
    {
        parent::__construct($name);
        $this->projector       = $projector;
        $this->eventRepository = $eventRepository;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write("Outbox \n");

        while (true)
        {
            $eventStoreEvent = $this->eventRepository->findOneBy(['relayed' => false]);

            if ($eventStoreEvent)
            {
                /** @var EventStoreEvent $eventStoreEvent */
                $this->projector->apply(call_user_func($eventStoreEvent->getEventName() . '::fromArray', json_decode($eventStoreEvent->getEvent(), true, 512, JSON_THROW_ON_ERROR)));
                $eventStoreEvent->relay();
                $this->eventRepository->save();
            }
            sleep(10);
        }
    }
}
