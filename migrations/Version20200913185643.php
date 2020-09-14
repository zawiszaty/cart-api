<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200913185643 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_read_model (id UUID NOT NULL, name VARCHAR(255) NOT NULL, price VARCHAR(255) NOT NULL, currency VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN product_read_model.id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE event_store_event (id UUID NOT NULL, aggregate_root_id UUID NOT NULL, aggregate_name VARCHAR(255) NOT NULL, event TEXT NOT NULL, event_name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN event_store_event.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN event_store_event.aggregate_root_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE cart_read_model (id UUID NOT NULL, user_id UUID NOT NULL, products JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN cart_read_model.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN cart_read_model.user_id IS \'(DC2Type:uuid)\'');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE cart_read_model');
        $this->addSql('DROP TABLE event_store_event');
        $this->addSql('DROP TABLE product_read_model');
    }
}
