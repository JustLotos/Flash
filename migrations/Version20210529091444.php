<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210529091444 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE flash_decks_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE flash_records_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE flash_repeats_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE refresh_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE flash_cards (id UUID NOT NULL, deck_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, state VARCHAR(255) NOT NULL, current_repeat_interval INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F69B1DBD111948DC ON flash_cards (deck_id)');
        $this->addSql('COMMENT ON COLUMN flash_cards.id IS \'(DC2Type:flash_card_id)\'');
        $this->addSql('COMMENT ON COLUMN flash_cards.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN flash_cards.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE flash_decks (id INT NOT NULL, learner_id UUID NOT NULL, name VARCHAR(255) NOT NULL, description TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, settings_limit_repeat INT NOT NULL, settings_limit_learning INT NOT NULL, settings_difficulty_index DOUBLE PRECISION NOT NULL, settings_base_interval INT NOT NULL, settings_min_time_interval INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_51A583726209CB66 ON flash_decks (learner_id)');
        $this->addSql('COMMENT ON COLUMN flash_decks.learner_id IS \'(DC2Type:flash_learner_id)\'');
        $this->addSql('COMMENT ON COLUMN flash_decks.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN flash_decks.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE flash_learners (id UUID NOT NULL, name_first VARCHAR(255) DEFAULT NULL, name_last VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN flash_learners.id IS \'(DC2Type:flash_learner_id)\'');
        $this->addSql('CREATE TABLE flash_records (id INT NOT NULL, card_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, value TEXT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6A5250054ACC9A20 ON flash_records (card_id)');
        $this->addSql('COMMENT ON COLUMN flash_records.card_id IS \'(DC2Type:flash_card_id)\'');
        $this->addSql('COMMENT ON COLUMN flash_records.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN flash_records.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE flash_repeats (id INT NOT NULL, card_id UUID NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, time INT NOT NULL, rating_score DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_760D524B4ACC9A20 ON flash_repeats (card_id)');
        $this->addSql('COMMENT ON COLUMN flash_repeats.card_id IS \'(DC2Type:flash_card_id)\'');
        $this->addSql('COMMENT ON COLUMN flash_repeats.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN flash_repeats.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE refresh_tokens (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON refresh_tokens (refresh_token)');
        $this->addSql('CREATE TABLE user_users (id UUID NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(16) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, status_value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6415EB1E7927C74 ON user_users (email)');
        $this->addSql('COMMENT ON COLUMN user_users.id IS \'(DC2Type:users_user_id)\'');
        $this->addSql('COMMENT ON COLUMN user_users.email IS \'(DC2Type:users_user_email)\'');
        $this->addSql('COMMENT ON COLUMN user_users.password IS \'(DC2Type:users_user_password)\'');
        $this->addSql('COMMENT ON COLUMN user_users.role IS \'(DC2Type:users_user_role)\'');
        $this->addSql('COMMENT ON COLUMN user_users.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_users.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE flash_cards ADD CONSTRAINT FK_F69B1DBD111948DC FOREIGN KEY (deck_id) REFERENCES flash_decks (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE flash_decks ADD CONSTRAINT FK_51A583726209CB66 FOREIGN KEY (learner_id) REFERENCES flash_learners (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
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
        $this->addSql('ALTER TABLE flash_cards DROP CONSTRAINT FK_F69B1DBD111948DC');
        $this->addSql('ALTER TABLE flash_decks DROP CONSTRAINT FK_51A583726209CB66');
        $this->addSql('DROP SEQUENCE flash_decks_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE flash_records_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE flash_repeats_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE refresh_tokens_id_seq CASCADE');
        $this->addSql('DROP TABLE flash_cards');
        $this->addSql('DROP TABLE flash_decks');
        $this->addSql('DROP TABLE flash_learners');
        $this->addSql('DROP TABLE flash_records');
        $this->addSql('DROP TABLE flash_repeats');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE user_users');
    }
}