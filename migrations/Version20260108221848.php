<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260108221848 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE badge (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, competence_id INT NOT NULL, INDEX IDX_FEF0481D15761DAB (competence_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE candidat (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(20) NOT NULL, date_naissance DATE DEFAULT NULL, adresse VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, cv_path VARCHAR(255) DEFAULT NULL, photo_path VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE candidat_langue (candidat_id INT NOT NULL, langue_id INT NOT NULL, INDEX IDX_2D9C88F28D0EB82 (candidat_id), INDEX IDX_2D9C88F22AADBACD (langue_id), PRIMARY KEY (candidat_id, langue_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE candidat_badge (candidat_id INT NOT NULL, badge_id INT NOT NULL, INDEX IDX_3E1D246F8D0EB82 (candidat_id), INDEX IDX_3E1D246FF7A2C2FC (badge_id), PRIMARY KEY (candidat_id, badge_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE candidat_competence (candidat_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_CF607D68D0EB82 (candidat_id), INDEX IDX_CF607D615761DAB (competence_id), PRIMARY KEY (candidat_id, competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE candidature (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, statut VARCHAR(50) NOT NULL, candidat_id INT NOT NULL, offre_emploi_id INT NOT NULL, INDEX IDX_E33BD3B88D0EB82 (candidat_id), INDEX IDX_E33BD3B8B08996ED (offre_emploi_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE certification (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(50) NOT NULL, issuer VARCHAR(100) NOT NULL, issued_at DATETIME NOT NULL, proof_url VARCHAR(255) DEFAULT NULL, badge_id INT DEFAULT NULL, candidat_id INT DEFAULT NULL, INDEX IDX_6C3C6D75F7A2C2FC (badge_id), INDEX IDX_6C3C6D758D0EB82 (candidat_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE competence (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, site_web VARCHAR(255) DEFAULT NULL, user_id INT NOT NULL, UNIQUE INDEX UNIQ_D19FA60A76ED395 (user_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, poste VARCHAR(255) NOT NULL, entreprise VARCHAR(255) NOT NULL, date_debut DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, description LONGTEXT DEFAULT NULL, candidat_id INT NOT NULL, INDEX IDX_590C1038D0EB82 (candidat_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, etablissement VARCHAR(255) NOT NULL, date_debut DATE DEFAULT NULL, date_fin DATE DEFAULT NULL, description LONGTEXT DEFAULT NULL, candidat_id INT NOT NULL, INDEX IDX_404021BF8D0EB82 (candidat_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE langue (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, contenu LONGTEXT NOT NULL, created_at DATETIME NOT NULL, piece_jointe_path VARCHAR(255) DEFAULT NULL, auteur_id INT NOT NULL, mission_id INT DEFAULT NULL, projet_id INT DEFAULT NULL, INDEX IDX_B6BD307F60BB6FE6 (auteur_id), INDEX IDX_B6BD307FBE6CAE90 (mission_id), INDEX IDX_B6BD307FC18272 (projet_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE mission (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, duree VARCHAR(255) NOT NULL, budget DOUBLE PRECISION NOT NULL, statut VARCHAR(255) NOT NULL, entreprise_id INT NOT NULL, candidat_assigne_id INT DEFAULT NULL, INDEX IDX_9067F23CA4AEAFEA (entreprise_id), INDEX IDX_9067F23C1659E519 (candidat_assigne_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE mission_competence (mission_id INT NOT NULL, competence_id INT NOT NULL, INDEX IDX_D6D445E4BE6CAE90 (mission_id), INDEX IDX_D6D445E415761DAB (competence_id), PRIMARY KEY (mission_id, competence_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE offre_emploi (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, lieu VARCHAR(255) NOT NULL, type_contrat VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, entreprise_id INT NOT NULL, INDEX IDX_132AD0D1A4AEAFEA (entreprise_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, objectifs LONGTEXT NOT NULL, nombre_participants_max INT NOT NULL, statut VARCHAR(255) NOT NULL, entreprise_id INT NOT NULL, INDEX IDX_50159CA9A4AEAFEA (entreprise_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE projet_candidat (projet_id INT NOT NULL, candidat_id INT NOT NULL, INDEX IDX_4D35DF86C18272 (projet_id), INDEX IDX_4D35DF868D0EB82 (candidat_id), PRIMARY KEY (projet_id, candidat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE badge ADD CONSTRAINT FK_FEF0481D15761DAB FOREIGN KEY (competence_id) REFERENCES competence (id)');
        $this->addSql('ALTER TABLE candidat_langue ADD CONSTRAINT FK_2D9C88F28D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidat_langue ADD CONSTRAINT FK_2D9C88F22AADBACD FOREIGN KEY (langue_id) REFERENCES langue (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidat_badge ADD CONSTRAINT FK_3E1D246F8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidat_badge ADD CONSTRAINT FK_3E1D246FF7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidat_competence ADD CONSTRAINT FK_CF607D68D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidat_competence ADD CONSTRAINT FK_CF607D615761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B88D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE candidature ADD CONSTRAINT FK_E33BD3B8B08996ED FOREIGN KEY (offre_emploi_id) REFERENCES offre_emploi (id)');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D75F7A2C2FC FOREIGN KEY (badge_id) REFERENCES badge (id)');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D758D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE entreprise ADD CONSTRAINT FK_D19FA60A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE experience ADD CONSTRAINT FK_590C1038D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF8D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307F60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FBE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FC18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23CA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE mission ADD CONSTRAINT FK_9067F23C1659E519 FOREIGN KEY (candidat_assigne_id) REFERENCES candidat (id)');
        $this->addSql('ALTER TABLE mission_competence ADD CONSTRAINT FK_D6D445E4BE6CAE90 FOREIGN KEY (mission_id) REFERENCES mission (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mission_competence ADD CONSTRAINT FK_D6D445E415761DAB FOREIGN KEY (competence_id) REFERENCES competence (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offre_emploi ADD CONSTRAINT FK_132AD0D1A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE projet_candidat ADD CONSTRAINT FK_4D35DF86C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projet_candidat ADD CONSTRAINT FK_4D35DF868D0EB82 FOREIGN KEY (candidat_id) REFERENCES candidat (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE badge DROP FOREIGN KEY FK_FEF0481D15761DAB');
        $this->addSql('ALTER TABLE candidat_langue DROP FOREIGN KEY FK_2D9C88F28D0EB82');
        $this->addSql('ALTER TABLE candidat_langue DROP FOREIGN KEY FK_2D9C88F22AADBACD');
        $this->addSql('ALTER TABLE candidat_badge DROP FOREIGN KEY FK_3E1D246F8D0EB82');
        $this->addSql('ALTER TABLE candidat_badge DROP FOREIGN KEY FK_3E1D246FF7A2C2FC');
        $this->addSql('ALTER TABLE candidat_competence DROP FOREIGN KEY FK_CF607D68D0EB82');
        $this->addSql('ALTER TABLE candidat_competence DROP FOREIGN KEY FK_CF607D615761DAB');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B88D0EB82');
        $this->addSql('ALTER TABLE candidature DROP FOREIGN KEY FK_E33BD3B8B08996ED');
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D75F7A2C2FC');
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D758D0EB82');
        $this->addSql('ALTER TABLE entreprise DROP FOREIGN KEY FK_D19FA60A76ED395');
        $this->addSql('ALTER TABLE experience DROP FOREIGN KEY FK_590C1038D0EB82');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF8D0EB82');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F60BB6FE6');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FBE6CAE90');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FC18272');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23CA4AEAFEA');
        $this->addSql('ALTER TABLE mission DROP FOREIGN KEY FK_9067F23C1659E519');
        $this->addSql('ALTER TABLE mission_competence DROP FOREIGN KEY FK_D6D445E4BE6CAE90');
        $this->addSql('ALTER TABLE mission_competence DROP FOREIGN KEY FK_D6D445E415761DAB');
        $this->addSql('ALTER TABLE offre_emploi DROP FOREIGN KEY FK_132AD0D1A4AEAFEA');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9A4AEAFEA');
        $this->addSql('ALTER TABLE projet_candidat DROP FOREIGN KEY FK_4D35DF86C18272');
        $this->addSql('ALTER TABLE projet_candidat DROP FOREIGN KEY FK_4D35DF868D0EB82');
        $this->addSql('DROP TABLE badge');
        $this->addSql('DROP TABLE candidat');
        $this->addSql('DROP TABLE candidat_langue');
        $this->addSql('DROP TABLE candidat_badge');
        $this->addSql('DROP TABLE candidat_competence');
        $this->addSql('DROP TABLE candidature');
        $this->addSql('DROP TABLE certification');
        $this->addSql('DROP TABLE competence');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE langue');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE mission');
        $this->addSql('DROP TABLE mission_competence');
        $this->addSql('DROP TABLE offre_emploi');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE projet_candidat');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
