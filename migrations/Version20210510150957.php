<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210510150957 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE flash_records_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE flash_repeats_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE flash_cards (id UUID NOT NULL, deck_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F69B1DBD111948DC ON flash_cards (deck_id)');
        $this->addSql('COMMENT ON COLUMN flash_cards.id IS \'(DC2Type:flash_card_id)\'');
        $this->addSql('COMMENT ON COLUMN flash_cards.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN flash_cards.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE flash_records (id INT NOT NULL, card_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, value TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A5250054ACC9A20 ON flash_records (card_id)');
        $this->addSql('COMMENT ON COLUMN flash_records.card_id IS \'(DC2Type:flash_card_id)\'');
        $this->addSql('COMMENT ON COLUMN flash_records.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN flash_records.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE flash_repeats (id INT NOT NULL, card_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, rating_score INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_760D524B4ACC9A20 ON flash_repeats (card_id)');
        $this->addSql('COMMENT ON COLUMN flash_repeats.card_id IS \'(DC2Type:flash_card_id)\'');
        $this->addSql('COMMENT ON COLUMN flash_repeats.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN flash_repeats.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE flash_cards ADD CONSTRAINT FK_F69B1DBD111948DC FOREIGN KEY (deck_id) REFERENCES flash_decks (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE flash_records ADD CONSTRAINT FK_6A5250054ACC9A20 FOREIGN KEY (card_id) REFERENCES flash_cards (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE flash_repeats ADD CONSTRAINT FK_760D524B4ACC9A20 FOREIGN KEY (card_id) REFERENCES flash_cards (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE flash_records DROP CONSTRAINT FK_6A5250054ACC9A20');
        $this->addSql('ALTER TABLE flash_repeats DROP CONSTRAINT FK_760D524B4ACC9A20');
        $this->addSql('DROP SEQUENCE flash_records_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE flash_repeats_id_seq CASCADE');
        $this->addSql('DROP TABLE flash_cards');
        $this->addSql('DROP TABLE flash_records');
        $this->addSql('DROP TABLE flash_repeats');
    }
}
