<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231127192237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_entry DROP FOREIGN KEY FK_A8BFE98DDE18E50B');
        $this->addSql('ALTER TABLE order_entry DROP FOREIGN KEY FK_A8BFE98DFCDAEAAA');
        $this->addSql('DROP INDEX IDX_A8BFE98DFCDAEAAA ON order_entry');
        $this->addSql('DROP INDEX IDX_A8BFE98DDE18E50B ON order_entry');
        $this->addSql('ALTER TABLE order_entry ADD order_id INT NOT NULL, ADD product_id INT NOT NULL, DROP order_id_id, DROP product_id_id');
        $this->addSql('ALTER TABLE order_entry ADD CONSTRAINT FK_A8BFE98D8D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE order_entry ADD CONSTRAINT FK_A8BFE98D4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('CREATE INDEX IDX_A8BFE98D8D9F6D38 ON order_entry (order_id)');
        $this->addSql('CREATE INDEX IDX_A8BFE98D4584665A ON order_entry (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE order_entry DROP FOREIGN KEY FK_A8BFE98D8D9F6D38');
        $this->addSql('ALTER TABLE order_entry DROP FOREIGN KEY FK_A8BFE98D4584665A');
        $this->addSql('DROP INDEX IDX_A8BFE98D8D9F6D38 ON order_entry');
        $this->addSql('DROP INDEX IDX_A8BFE98D4584665A ON order_entry');
        $this->addSql('ALTER TABLE order_entry ADD order_id_id INT NOT NULL, ADD product_id_id INT NOT NULL, DROP order_id, DROP product_id');
        $this->addSql('ALTER TABLE order_entry ADD CONSTRAINT FK_A8BFE98DDE18E50B FOREIGN KEY (product_id_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE order_entry ADD CONSTRAINT FK_A8BFE98DFCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_A8BFE98DFCDAEAAA ON order_entry (order_id_id)');
        $this->addSql('CREATE INDEX IDX_A8BFE98DDE18E50B ON order_entry (product_id_id)');
    }
}
