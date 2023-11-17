<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231117202218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE shopping_cart (id INT AUTO_INCREMENT NOT NULL, dateadded DATETIME NOT NULL, itemsum DOUBLE PRECISION NOT NULL, deliverysum DOUBLE PRECISION NOT NULL, note LONGTEXT DEFAULT NULL, idaddress INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE shopping_cart_entry (id INT AUTO_INCREMENT NOT NULL, shopping_cart_id INT NOT NULL, quantity SMALLINT NOT NULL, INDEX IDX_6B04172E45F80CD (shopping_cart_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F6BF396750 FOREIGN KEY (id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE shopping_cart_entry ADD CONSTRAINT FK_6B04172E45F80CD FOREIGN KEY (shopping_cart_id) REFERENCES shopping_cart (id)');
        $this->addSql('ALTER TABLE shopping_cart_entry ADD CONSTRAINT FK_6B04172EBF396750 FOREIGN KEY (id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE shopping_cart DROP FOREIGN KEY FK_72AAD4F6BF396750');
        $this->addSql('ALTER TABLE shopping_cart_entry DROP FOREIGN KEY FK_6B04172E45F80CD');
        $this->addSql('ALTER TABLE shopping_cart_entry DROP FOREIGN KEY FK_6B04172EBF396750');
        $this->addSql('DROP TABLE shopping_cart');
        $this->addSql('DROP TABLE shopping_cart_entry');
    }
}
