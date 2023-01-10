<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230110184328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE carpool (id INT AUTO_INCREMENT NOT NULL, event_id INT DEFAULT NULL, status INT NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', address VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, comment LONGTEXT DEFAULT NULL, nb_place INT NOT NULL, added_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_active INT NOT NULL, INDEX IDX_E95D90CC71F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE carpool_user_child (carpool_id INT NOT NULL, user_child_id INT NOT NULL, INDEX IDX_D6A0F13C9A6F0DAE (carpool_id), INDEX IDX_D6A0F13C3DEF261B (user_child_id), PRIMARY KEY(carpool_id, user_child_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, added_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_active INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_event (category_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_D39D45EE12469DE2 (category_id), INDEX IDX_D39D45EE71F7E88B (event_id), PRIMARY KEY(category_id, event_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, status INT NOT NULL, title VARCHAR(255) NOT NULL, date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', address VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, nb_minus INT NOT NULL, nb_registrant INT DEFAULT NULL, added_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_active INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_user_child (event_id INT NOT NULL, user_child_id INT NOT NULL, INDEX IDX_989F765E71F7E88B (event_id), INDEX IDX_989F765E3DEF261B (user_child_id), PRIMARY KEY(event_id, user_child_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, postal_code VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, added_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_active INT NOT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_carpool (user_id INT NOT NULL, carpool_id INT NOT NULL, INDEX IDX_B1508EC6A76ED395 (user_id), INDEX IDX_B1508EC69A6F0DAE (carpool_id), PRIMARY KEY(user_id, carpool_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_child (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, category_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, birthday DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_active INT NOT NULL, added_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C071AF71A76ED395 (user_id), INDEX IDX_C071AF7112469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE carpool ADD CONSTRAINT FK_E95D90CC71F7E88B FOREIGN KEY (event_id) REFERENCES event (id)');
        $this->addSql('ALTER TABLE carpool_user_child ADD CONSTRAINT FK_D6A0F13C9A6F0DAE FOREIGN KEY (carpool_id) REFERENCES carpool (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE carpool_user_child ADD CONSTRAINT FK_D6A0F13C3DEF261B FOREIGN KEY (user_child_id) REFERENCES user_child (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_event ADD CONSTRAINT FK_D39D45EE12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_event ADD CONSTRAINT FK_D39D45EE71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_user_child ADD CONSTRAINT FK_989F765E71F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_user_child ADD CONSTRAINT FK_989F765E3DEF261B FOREIGN KEY (user_child_id) REFERENCES user_child (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_carpool ADD CONSTRAINT FK_B1508EC6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_carpool ADD CONSTRAINT FK_B1508EC69A6F0DAE FOREIGN KEY (carpool_id) REFERENCES carpool (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_child ADD CONSTRAINT FK_C071AF71A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_child ADD CONSTRAINT FK_C071AF7112469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE carpool DROP FOREIGN KEY FK_E95D90CC71F7E88B');
        $this->addSql('ALTER TABLE carpool_user_child DROP FOREIGN KEY FK_D6A0F13C9A6F0DAE');
        $this->addSql('ALTER TABLE carpool_user_child DROP FOREIGN KEY FK_D6A0F13C3DEF261B');
        $this->addSql('ALTER TABLE category_event DROP FOREIGN KEY FK_D39D45EE12469DE2');
        $this->addSql('ALTER TABLE category_event DROP FOREIGN KEY FK_D39D45EE71F7E88B');
        $this->addSql('ALTER TABLE event_user_child DROP FOREIGN KEY FK_989F765E71F7E88B');
        $this->addSql('ALTER TABLE event_user_child DROP FOREIGN KEY FK_989F765E3DEF261B');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE user_carpool DROP FOREIGN KEY FK_B1508EC6A76ED395');
        $this->addSql('ALTER TABLE user_carpool DROP FOREIGN KEY FK_B1508EC69A6F0DAE');
        $this->addSql('ALTER TABLE user_child DROP FOREIGN KEY FK_C071AF71A76ED395');
        $this->addSql('ALTER TABLE user_child DROP FOREIGN KEY FK_C071AF7112469DE2');
        $this->addSql('DROP TABLE carpool');
        $this->addSql('DROP TABLE carpool_user_child');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE category_event');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_user_child');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_carpool');
        $this->addSql('DROP TABLE user_child');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
