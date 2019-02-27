<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190226140308 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rating ADD reviewer_id INT NOT NULL, ADD skill_id INT NOT NULL, ADD person_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D889262270574616 FOREIGN KEY (reviewer_id) REFERENCES reviewer (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926225585C142 FOREIGN KEY (skill_id) REFERENCES skill (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622217BBB47 FOREIGN KEY (person_id) REFERENCES person (id)');
        $this->addSql('CREATE INDEX IDX_D889262270574616 ON rating (reviewer_id)');
        $this->addSql('CREATE INDEX IDX_D88926225585C142 ON rating (skill_id)');
        $this->addSql('CREATE INDEX IDX_D8892622217BBB47 ON rating (person_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D889262270574616');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926225585C142');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622217BBB47');
        $this->addSql('DROP INDEX IDX_D889262270574616 ON rating');
        $this->addSql('DROP INDEX IDX_D88926225585C142 ON rating');
        $this->addSql('DROP INDEX IDX_D8892622217BBB47 ON rating');
        $this->addSql('ALTER TABLE rating DROP reviewer_id, DROP skill_id, DROP person_id');
    }
}
