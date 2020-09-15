<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200915192406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $eventStore = <<<sql
insert into public.event_store_event (id, aggregate_root_id, aggregate_name, event, event_name) values ('797c01e2-0f8c-4bbc-b344-2026c03b2893', 'd96ff1e7-9414-4e6c-a09d-b38ad3f8f147', 'App\Module\Catalog\Domain\Product', '{"event_id":"b3a1443d-86cf-4e18-9295-15647b270da7","id":"d96ff1e7-9414-4e6c-a09d-b38ad3f8f147","price":"5999","currency":"PLN","name":"The Godfather"}', 'App\Module\Catalog\Domain\Event\ProductCreatedEvent');
insert into public.event_store_event (id, aggregate_root_id, aggregate_name, event, event_name) values ('d18bcdee-8eac-45fd-929b-d18789265aa2', '5c470d5b-dde6-4320-ba5d-208aee8fa042', 'App\Module\Catalog\Domain\Product', '{"event_id":"8cdadcaf-60b2-458a-8671-31305a9488b6","id":"5c470d5b-dde6-4320-ba5d-208aee8fa042","price":"999","currency":"PLN","name":"The Trial!"}', 'App\Module\Catalog\Domain\Event\ProductCreatedEvent');
insert into public.event_store_event (id, aggregate_root_id, aggregate_name, event, event_name) values ('be1c59c1-c732-498b-b1c8-7e128c73aa17', '92745a3e-0f3a-4155-a141-0027dac593a2', 'App\Module\Cart\Domain\Cart', '{"event_id":"6e7ad70e-8fd2-4da6-85de-c40fa6cd4ed4","cart_id":"92745a3e-0f3a-4155-a141-0027dac593a2","user_id":"dc454b4c-f785-11ea-adc1-0242ac120002"}', 'App\Module\Cart\Domain\Event\CartCreatedEvent');
insert into public.event_store_event (id, aggregate_root_id, aggregate_name, event, event_name) values ('a96eefbe-66ab-4d15-8a56-99769670ec9f', '8db9dbc8-49fb-423d-b5e8-02ad5f934783', 'App\Module\Catalog\Domain\Product', '{"event_id":"3af057a9-e0e7-4a20-b7ef-ac86b11dd33c","id":"8db9dbc8-49fb-423d-b5e8-02ad5f934783","price":"4995","currency":"PLN","name":"Steve Jobs"}', 'App\Module\Catalog\Domain\Event\ProductCreatedEvent');
insert into public.event_store_event (id, aggregate_root_id, aggregate_name, event, event_name) values ('de624ab9-0aa0-4279-9ba6-cd819a2d243e', '5119074d-0522-44d8-894e-be374eee4078', 'App\Module\Catalog\Domain\Product', '{"event_id":"2dfe4838-26ca-4465-87af-7336b3456607","id":"5119074d-0522-44d8-894e-be374eee4078","price":"2999","currency":"PLN","name":"The Little Prince"}', 'App\Module\Catalog\Domain\Event\ProductCreatedEvent');
insert into public.event_store_event (id, aggregate_root_id, aggregate_name, event, event_name) values ('e184f9e5-1a12-48f1-9cec-8790f74958f9', '70f34e59-7170-47cd-9701-00e263cbdd51', 'App\Module\Catalog\Domain\Product', '{"event_id":"c0dac7a2-3d01-4e15-b61d-fe964f0607a0","id":"70f34e59-7170-47cd-9701-00e263cbdd51","price":"1999","currency":"PLN","name":"I Hate Myselfie!"}', 'App\Module\Catalog\Domain\Event\ProductCreatedEvent');
sql;
        $this->addSql($eventStore);
        $cart = <<<cart
insert into public.cart_read_model (id, user_id, products) values ('92745a3e-0f3a-4155-a141-0027dac593a2', 'dc454b4c-f785-11ea-adc1-0242ac120002', '"[]"');
cart;

        $this->addSql($cart);

        $products = <<<products
insert into public.product_read_model (id, name, price, currency) values ('d96ff1e7-9414-4e6c-a09d-b38ad3f8f147', 'The Godfather', 5999, 'PLN');
insert into public.product_read_model (id, name, price, currency) values ('5c470d5b-dde6-4320-ba5d-208aee8fa042', 'The Trial!', 999, 'PLN');
insert into public.product_read_model (id, name, price, currency) values ('8db9dbc8-49fb-423d-b5e8-02ad5f934783', 'Steve Jobs', 4995, 'PLN');
insert into public.product_read_model (id, name, price, currency) values ('5119074d-0522-44d8-894e-be374eee4078', 'The Little Prince', 2999, 'PLN');
insert into public.product_read_model (id, name, price, currency) values ('70f34e59-7170-47cd-9701-00e263cbdd51', 'I Hate Myselfie!', 1999, 'PLN');
products;
        $this->addSql($products);


    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
