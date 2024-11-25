<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241124214149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_entry ADD Task_Id CHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE time_entry ADD CONSTRAINT FK_6E537C0CD78F1CB8 FOREIGN KEY (Task_Id) REFERENCES task (Task_Id)');
        $this->addSql('CREATE INDEX IDX_6E537C0CD78F1CB8 ON time_entry (Task_Id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE time_entry DROP FOREIGN KEY FK_6E537C0CD78F1CB8');
        $this->addSql('DROP INDEX IDX_6E537C0CD78F1CB8 ON time_entry');
        $this->addSql('ALTER TABLE time_entry DROP Task_Id');
    }
}
