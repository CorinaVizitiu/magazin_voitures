<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230628073413 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Like';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_voiture_Like (voiture_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_23D7E970181A8BA (voiture_id), INDEX IDX_23D7E970A76ED395 (user_id), PRIMARY KEY(voiture_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_voiture_Like ADD CONSTRAINT FK_23D7E970181A8BA FOREIGN KEY (voiture_id) REFERENCES voitures (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_voiture_Like ADD CONSTRAINT FK_23D7E970A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_voiture_Like DROP FOREIGN KEY FK_23D7E970181A8BA');
        $this->addSql('ALTER TABLE user_voiture_Like DROP FOREIGN KEY FK_23D7E970A76ED395');
        $this->addSql('DROP TABLE user_voiture_Like');
    }
}
