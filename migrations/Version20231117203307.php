<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231117203307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_cart CHANGE itemsum productsum DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE shopping_cart_entry DROP FOREIGN KEY FK_6B04172EBF396750');
        $this->addSql('ALTER TABLE shopping_cart_entry ADD product_id INT NOT NULL');
        $this->addSql('ALTER TABLE shopping_cart_entry ADD CONSTRAINT FK_6B04172E4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_6B04172E4584665A ON shopping_cart_entry (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_cart CHANGE productsum itemsum DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE shopping_cart_entry DROP FOREIGN KEY FK_6B04172E4584665A');
        $this->addSql('DROP INDEX IDX_6B04172E4584665A ON shopping_cart_entry');
        $this->addSql('ALTER TABLE shopping_cart_entry DROP product_id');
        $this->addSql('ALTER TABLE shopping_cart_entry ADD CONSTRAINT FK_6B04172EBF396750 FOREIGN KEY (id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
