<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220928075448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Mise à jour des relations';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE message ADD receiver_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_B6BD307FCD53EDB6 ON message (receiver_id)');
        $this->addSql('ALTER TABLE user ADD sended_message_id INT DEFAULT NULL, ADD room_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E4DB05F8 FOREIGN KEY (sended_message_id) REFERENCES message (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64954177093 FOREIGN KEY (room_id) REFERENCES classroom (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E4DB05F8 ON user (sended_message_id)');
        $this->addSql('CREATE INDEX IDX_8D93D64954177093 ON user (room_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E4DB05F8');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64954177093');
        $this->addSql('DROP INDEX UNIQ_8D93D649E4DB05F8 ON user');
        $this->addSql('DROP INDEX IDX_8D93D64954177093 ON user');
        $this->addSql('ALTER TABLE user DROP sended_message_id, DROP room_id');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FCD53EDB6');
        $this->addSql('DROP INDEX IDX_B6BD307FCD53EDB6 ON message');
        $this->addSql('ALTER TABLE message DROP receiver_id');
    }
}
