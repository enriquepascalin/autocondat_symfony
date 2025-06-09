<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250608215827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE user_role DROP CONSTRAINT fk_2de8c6a3a76ed395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_role DROP CONSTRAINT fk_2de8c6a3d60322ac
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE user_role
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" ADD roles JSON NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE user_role (user_id INT NOT NULL, role_id INT NOT NULL, PRIMARY KEY(user_id, role_id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_2de8c6a3d60322ac ON user_role (role_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX idx_2de8c6a3a76ed395 ON user_role (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_role ADD CONSTRAINT fk_2de8c6a3a76ed395 FOREIGN KEY (user_id) REFERENCES "user" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user_role ADD CONSTRAINT fk_2de8c6a3d60322ac FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE "user" DROP roles
        SQL);
    }
}
