<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230607093209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'is_verified';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users ADD is_verified TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE voitures ADD CONSTRAINT FK_8B58301BA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_8B58301BA76ED395 ON voitures (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE users DROP is_verified');
        $this->addSql('ALTER TABLE voitures DROP FOREIGN KEY FK_8B58301BA76ED395');
        $this->addSql('DROP INDEX IDX_8B58301BA76ED395 ON voitures');
    }
}
