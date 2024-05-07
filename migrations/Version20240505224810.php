<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240505224810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE codepromo (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(255) NOT NULL, date_expiration DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (id_utilisateur INT DEFAULT NULL, id_Commande INT AUTO_INCREMENT NOT NULL, Prix_total DOUBLE PRECISION NOT NULL, date_creation_commande MEDIUMTEXT NOT NULL, id_Panier INT DEFAULT NULL, id_Payment INT DEFAULT NULL, INDEX fk_panier (id_Panier), INDEX id_utilisateur (id_utilisateur), INDEX fk_payment (id_Payment), PRIMARY KEY(id_Commande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE conversation (conversation_id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) DEFAULT NULL, sujet VARCHAR(255) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, date_creation DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, conversation_type VARCHAR(255) DEFAULT NULL, visibilite VARCHAR(255) DEFAULT NULL, PRIMARY KEY(conversation_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE enchers (id_enchers INT AUTO_INCREMENT NOT NULL, id_utilisateur INT DEFAULT NULL, type_oeuvre VARCHAR(255) NOT NULL, min_montant DOUBLE PRECISION NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, image VARCHAR(255) NOT NULL, INDEX fk_enchers (id_utilisateur), PRIMARY KEY(id_enchers)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (Id INT AUTO_INCREMENT NOT NULL, Nom VARCHAR(255) NOT NULL, Date_debut DATE NOT NULL, Date_fin DATE NOT NULL, Description VARCHAR(255) NOT NULL, Type VARCHAR(255) NOT NULL, PRIMARY KEY(Id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exposition (id INT AUTO_INCREMENT NOT NULL, portfolios_id INT DEFAULT NULL, image_affiche VARCHAR(255) NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(1000) NOT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, type_expo VARCHAR(255) NOT NULL, lacalisation VARCHAR(255) NOT NULL, INDEX IDX_BC31FD1381DC659 (portfolios_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (message_id INT AUTO_INCREMENT NOT NULL, id_utilisateur INT DEFAULT NULL, conversation_id INT DEFAULT NULL, date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, contenu VARCHAR(255) NOT NULL, INDEX fk_conversation (conversation_id), INDEX fk_utilisateur (id_utilisateur), PRIMARY KEY(message_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mise (id_mise INT AUTO_INCREMENT NOT NULL, id_enchers INT DEFAULT NULL, id_utilisateur INT DEFAULT NULL, max_montant DOUBLE PRECISION NOT NULL, INDEX fk_encher (id_enchers), INDEX fk_user (id_utilisateur), PRIMARY KEY(id_mise)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oeuvre_art (id INT AUTO_INCREMENT NOT NULL, portfolios_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, image_oeuvre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date_creation DATE NOT NULL, dimension VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, categorie VARCHAR(255) NOT NULL, INDEX IDX_39B8CDC081DC659 (portfolios_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id_Panier INT AUTO_INCREMENT NOT NULL, Nbr_Commande INT NOT NULL, PRIMARY KEY(id_Panier)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant (id_participant INT AUTO_INCREMENT NOT NULL, id_evenement INT DEFAULT NULL, id_utilisateur INT DEFAULT NULL, INDEX fk_participant (id_utilisateur), INDEX fk_event (id_evenement), PRIMARY KEY(id_participant)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant_chat (participant_id INT AUTO_INCREMENT NOT NULL, conversation_id INT DEFAULT NULL, id_utilisateur INT DEFAULT NULL, INDEX fk_usera (id_utilisateur), INDEX fk_conversationa (conversation_id), PRIMARY KEY(participant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment (id_Payment INT AUTO_INCREMENT NOT NULL, Montant DOUBLE PRECISION NOT NULL, type_Payment VARCHAR(255) NOT NULL, PRIMARY KEY(id_Payment)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE portfolio (id INT AUTO_INCREMENT NOT NULL, id_user_id INT DEFAULT NULL, nom_artistique VARCHAR(255) NOT NULL, image_url VARCHAR(255) DEFAULT NULL, biographie VARCHAR(1000) NOT NULL, debut_carriere DATE DEFAULT NULL, reseau_sociaux VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_A9ED106279F37AE5 (id_user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rate (id_rate INT AUTO_INCREMENT NOT NULL, id_oeuvre INT DEFAULT NULL, rateNote DOUBLE PRECISION NOT NULL, id_user INT NOT NULL, INDEX rk_oeuvre (id_oeuvre), PRIMARY KEY(id_rate)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, role VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE exposition ADD CONSTRAINT FK_BC31FD1381DC659 FOREIGN KEY (portfolios_id) REFERENCES portfolio (id)');
        $this->addSql('ALTER TABLE oeuvre_art ADD CONSTRAINT FK_39B8CDC081DC659 FOREIGN KEY (portfolios_id) REFERENCES portfolio (id)');
        $this->addSql('ALTER TABLE portfolio ADD CONSTRAINT FK_A9ED106279F37AE5 FOREIGN KEY (id_user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE utilisateur');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE exposition DROP FOREIGN KEY FK_BC31FD1381DC659');
        $this->addSql('ALTER TABLE oeuvre_art DROP FOREIGN KEY FK_39B8CDC081DC659');
        $this->addSql('ALTER TABLE portfolio DROP FOREIGN KEY FK_A9ED106279F37AE5');
        $this->addSql('DROP TABLE codepromo');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE conversation');
        $this->addSql('DROP TABLE enchers');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE exposition');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE mise');
        $this->addSql('DROP TABLE oeuvre_art');
        $this->addSql('DROP TABLE panier');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE participant_chat');
        $this->addSql('DROP TABLE payment');
        $this->addSql('DROP TABLE portfolio');
        $this->addSql('DROP TABLE rate');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
