<?php

declare(strict_types=1);

namespace App\Infrastructure\Symfony;

use App\Infrastructure\EventStore\InMemoryProjector;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ProjectorCommand extends Command
{
    private const PROJECTION = 'projection';
    protected static $defaultName = 'worker:projection';

    private InMemoryProjector $projector;

    private AMQPChannel $channel;

    public function __construct($name = null, InMemoryProjector $projector, AMQPStreamConnection $connection)
    {
        parent::__construct($name);

        $this->projector = $projector;
        $this->channel = $connection->channel();
        $this->channel->exchange_declare(self::PROJECTION, 'fanout', false, false, false);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write("Consuming \n");
        $this->channel->exchange_declare(self::PROJECTION, 'fanout', false, false, false);
        $this->channel->queue_declare('projection', false, false, false, false);
        $this->channel->queue_bind('projection', self::PROJECTION, '#');
        $this->channel->basic_consume('projection', '', false, true, false, false, function (AMQPMessage $msg) use ($output) {
            $decodesMessage = json_decode($msg->getBody(), true, 512, JSON_THROW_ON_ERROR);
            $event = call_user_func($msg->getRoutingKey().'::fromArray', $decodesMessage);
            $this->projector->apply($event);
            $output->write("Message consume\n");
        });

        while ($this->channel->is_consuming()) {
            $this->channel->wait();
        }
    }
}
