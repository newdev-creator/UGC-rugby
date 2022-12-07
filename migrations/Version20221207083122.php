<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221207083122 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_user_child (event_id INT NOT NULL, user_child_id INT NOT NULL, INDEX IDX_989F765E71F7E88B (event_id), INDEX IDX_989F765E3DEF261B (user_child_id), PRIMARY KEY(event_id, user_child_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE event_user_child ADD CONSTRAINT FK_989F765E71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_user_child ADD CONSTRAINT FK_989F765E3DEF261B FOREIGN KEY (user_child_id) REFERENCES user_child (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_user_child DROP FOREIGN KEY FK_989F765E71F7E88B');
        $this->addSql('ALTER TABLE event_user_child DROP FOREIGN KEY FK_989F765E3DEF261B');
        $this->addSql('DROP TABLE event_user_child');
    }
}
