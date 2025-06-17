<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250617215504 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE contact (id SERIAL NOT NULL, customer_id INT NOT NULL, full_name VARCHAR(255) NOT NULL, job_title VARCHAR(100) DEFAULT NULL, email VARCHAR(100) DEFAULT NULL, mobile VARCHAR(15) DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_4C62E6389395C3F3 ON contact (customer_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE customer (id SERIAL NOT NULL, tenant_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, legal_name VARCHAR(255) NOT NULL, trading_name VARCHAR(255) DEFAULT NULL, customer_type INT NOT NULL, default_currency VARCHAR(3) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, deleted_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_81398E099033212A ON customer (tenant_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_81398E09DE12AB56 ON customer (created_by)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_81398E0916FE72E1 ON customer (updated_by)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN customer.created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN customer.updated_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN customer.deleted_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "order" (id SERIAL NOT NULL, quote_id INT DEFAULT NULL, customer_id INT NOT NULL, order_number VARCHAR(255) DEFAULT NULL, status INT NOT NULL, placed_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, total_amount DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F5299398DB805178 ON "order" (quote_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_F52993989395C3F3 ON "order" (customer_id)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "order".placed_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE order_item (id SERIAL NOT NULL, sales_order_id INT NOT NULL, product_id INT DEFAULT NULL, description TEXT DEFAULT NULL, unit_price DOUBLE PRECISION DEFAULT NULL, quantity INT DEFAULT NULL, discount DOUBLE PRECISION DEFAULT NULL, subtotal DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_52EA1F09C023F51C ON order_item (sales_order_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_52EA1F094584665A ON order_item (product_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE price_list (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, currency VARCHAR(3) DEFAULT NULL, valid_from DATE DEFAULT NULL, valid_to DATE DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE price_list_item (id SERIAL NOT NULL, price_list_id INT NOT NULL, product_id INT NOT NULL, unit_price DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D964C90B5688DED7 ON price_list_item (price_list_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_D964C90B4584665A ON price_list_item (product_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product (id SERIAL NOT NULL, sku VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, product_type INT NOT NULL, unit_price DOUBLE PRECISION DEFAULT NULL, tax_rate NUMERIC(10, 2) DEFAULT NULL, loyalty_points INT DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product_product_category (product_id INT NOT NULL, product_category_id INT NOT NULL, PRIMARY KEY(product_id, product_category_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_437017AA4584665A ON product_product_category (product_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_437017AABE6903FD ON product_product_category (product_category_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE product_category (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, category_type INT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE quote (id SERIAL NOT NULL, customer_id INT NOT NULL, quote_number VARCHAR(255) DEFAULT NULL, status INT NOT NULL, valid_until DATE DEFAULT NULL, total_amount DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_6B71CBF49395C3F3 ON quote (customer_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE quote_item (id SERIAL NOT NULL, quote_id INT NOT NULL, product_id INT DEFAULT NULL, description TEXT DEFAULT NULL, unit_price DOUBLE PRECISION DEFAULT NULL, discount DOUBLE PRECISION DEFAULT NULL, quantity INT DEFAULT NULL, sub_total DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8DFC7A94DB805178 ON quote_item (quote_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_8DFC7A944584665A ON quote_item (product_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contact ADD CONSTRAINT FK_4C62E6389395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer ADD CONSTRAINT FK_81398E099033212A FOREIGN KEY (tenant_id) REFERENCES tenant (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer ADD CONSTRAINT FK_81398E09DE12AB56 FOREIGN KEY (created_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer ADD CONSTRAINT FK_81398E0916FE72E1 FOREIGN KEY (updated_by) REFERENCES "user" (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" ADD CONSTRAINT FK_F5299398DB805178 FOREIGN KEY (quote_id) REFERENCES quote (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" ADD CONSTRAINT FK_F52993989395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F09C023F51C FOREIGN KEY (sales_order_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item ADD CONSTRAINT FK_52EA1F094584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE price_list_item ADD CONSTRAINT FK_D964C90B5688DED7 FOREIGN KEY (price_list_id) REFERENCES price_list (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE price_list_item ADD CONSTRAINT FK_D964C90B4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_product_category ADD CONSTRAINT FK_437017AA4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_product_category ADD CONSTRAINT FK_437017AABE6903FD FOREIGN KEY (product_category_id) REFERENCES product_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quote ADD CONSTRAINT FK_6B71CBF49395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quote_item ADD CONSTRAINT FK_8DFC7A94DB805178 FOREIGN KEY (quote_id) REFERENCES quote (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quote_item ADD CONSTRAINT FK_8DFC7A944584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE contact DROP CONSTRAINT FK_4C62E6389395C3F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer DROP CONSTRAINT FK_81398E099033212A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer DROP CONSTRAINT FK_81398E09DE12AB56
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE customer DROP CONSTRAINT FK_81398E0916FE72E1
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" DROP CONSTRAINT FK_F5299398DB805178
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "order" DROP CONSTRAINT FK_F52993989395C3F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F09C023F51C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE order_item DROP CONSTRAINT FK_52EA1F094584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE price_list_item DROP CONSTRAINT FK_D964C90B5688DED7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE price_list_item DROP CONSTRAINT FK_D964C90B4584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_product_category DROP CONSTRAINT FK_437017AA4584665A
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE product_product_category DROP CONSTRAINT FK_437017AABE6903FD
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quote DROP CONSTRAINT FK_6B71CBF49395C3F3
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quote_item DROP CONSTRAINT FK_8DFC7A94DB805178
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE quote_item DROP CONSTRAINT FK_8DFC7A944584665A
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE contact
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE customer
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "order"
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE order_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE price_list
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE price_list_item
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product_product_category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product_category
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE quote
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE quote_item
        SQL);
    }
}
