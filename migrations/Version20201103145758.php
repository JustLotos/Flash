<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201103145758 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SEQUENCE refresh_tokens_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_users (id UUID NOT NULL, email VARCHAR(255) NOT NULL, temporary_email VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, role VARCHAR(16) NOT NULL, confirm_token_token VARCHAR(255) DEFAULT NULL, confirm_token_expires TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, confirm_token_value VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6415EB1E7927C74 ON user_users (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6415EB1F464BC96 ON user_users (confirm_token_token)');
        $this->addSql('COMMENT ON COLUMN user_users.id IS \'(DC2Type:users_user_id)\'');
        $this->addSql('COMMENT ON COLUMN user_users.email IS \'(DC2Type:users_user_email)\'');
        $this->addSql('COMMENT ON COLUMN user_users.temporary_email IS \'(DC2Type:users_user_email)\'');
        $this->addSql('COMMENT ON COLUMN user_users.password IS \'(DC2Type:users_user_password)\'');
        $this->addSql('COMMENT ON COLUMN user_users.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_users.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_users.role IS \'(DC2Type:users_user_role)\'');
        $this->addSql('COMMENT ON COLUMN user_users.confirm_token_expires IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE refresh_tokens (id INT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9BACE7E1C74F2195 ON refresh_tokens (refresh_token)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE refresh_tokens_id_seq CASCADE');
        $this->addSql('DROP TABLE user_users');
        $this->addSql('DROP TABLE refresh_tokens');
    }
}
