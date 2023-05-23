<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230520145339 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD4A7E4868');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD4A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD4A7E4868');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD4A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
