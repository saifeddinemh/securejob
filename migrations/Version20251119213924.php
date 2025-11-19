<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251119213924 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, job_title VARCHAR(255) NOT NULL, company VARCHAR(255) NOT NULL, start_date DATE NOT NULL, end_date DATE DEFAULT NULL, current_job TINYINT(1) NOT NULL, description LONGTEXT DEFAULT NULL, candidate_id INT NOT NULL, INDEX IDX_590C10391BD8781 (candidate_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C10391BD8781 FOREIGN KEY (candidate_id) REFERENCES candidate (id)');
        $this->addSql('ALTER TABLE candidate ADD first_name VARCHAR(100) NOT NULL, ADD last_name VARCHAR(100) NOT NULL, ADD phone VARCHAR(20) DEFAULT NULL, ADD address VARCHAR(255) DEFAULT NULL, ADD city VARCHAR(100) DEFAULT NULL, ADD postal_code VARCHAR(10) DEFAULT NULL, ADD country VARCHAR(100) DEFAULT NULL, ADD cv_file VARCHAR(255) DEFAULT NULL, ADD portfolio_file VARCHAR(255) DEFAULT NULL, ADD is_visible TINYINT(1) NOT NULL, DROP location, DROP profile_pictur, DROP linkedin_url, DROP portfolio_url, DROP skills, CHANGE bio bio LONGTEXT DEFAULT NULL, CHANGE experience_years user_id INT NOT NULL');
        $this->addSql('ALTER TABLE candidate ADD CONSTRAINT FK_C8B28E44A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C8B28E44A76ED395 ON candidate (user_id)');
        $this->addSql('ALTER TABLE skill ADD description VARCHAR(255) DEFAULT NULL, CHANGE name name VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C10391BD8781');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE language');
        $this->addSql('ALTER TABLE candidate DROP FOREIGN KEY FK_C8B28E44A76ED395');
        $this->addSql('DROP INDEX UNIQ_C8B28E44A76ED395 ON candidate');
        $this->addSql('ALTER TABLE candidate ADD location VARCHAR(50) NOT NULL, ADD profile_pictur VARCHAR(50) NOT NULL, ADD linkedin_url VARCHAR(50) DEFAULT NULL, ADD portfolio_url VARCHAR(50) DEFAULT NULL, ADD skills VARCHAR(50) NOT NULL, DROP first_name, DROP last_name, DROP phone, DROP address, DROP city, DROP postal_code, DROP country, DROP cv_file, DROP portfolio_file, DROP is_visible, CHANGE bio bio LONGTEXT NOT NULL, CHANGE user_id experience_years INT NOT NULL');
        $this->addSql('ALTER TABLE skill DROP description, CHANGE name name VARCHAR(50) NOT NULL');
    }
}
