# Guide d'intégration des fonctionnalités Recruteur dans SecureJob

Ce document détaille les modifications apportées au projet Symfony SecureJob pour implémenter les fonctionnalités recruteur, ainsi que les étapes nécessaires pour intégrer ces changements dans votre environnement de travail.

## 1. Vue d'ensemble des nouvelles fonctionnalités

Les fonctionnalités suivantes ont été ajoutées :

*   **Authentification Recruteur** : Pages d'inscription et de connexion dédiées aux recruteurs, avec un rôle `ROLE_RECRUITER`.
*   **Gestion d'entreprise** : Chaque recruteur est lié à une entité `Entreprise` qu'il peut créer et modifier.
*   **Gestion des offres d'emploi** : Les recruteurs peuvent créer, modifier et supprimer leurs offres d'emploi.
*   **Dashboard Recruteur** : Un tableau de bord affichant le nombre d'offres publiées et le total des candidatures.

## 2. Modifications de la structure du projet

De nouvelles entités, contrôleurs, formulaires et templates ont été ajoutés :

### Entités (`src/Entity/`)

*   `User.php` : Nouvelle entité pour gérer les utilisateurs (recruteurs et potentiellement candidats à l'avenir). Elle implémente `UserInterface` et `PasswordAuthenticatedUserInterface`.
*   `Entreprise.php` : Entité représentant une entreprise, liée à un `User` (recruteur) via une relation `OneToOne`.
*   `OffreEmploi.php` : Entité pour les offres d'emploi, liée à une `Entreprise` via une relation `ManyToOne`.
*   `Candidature.php` : Entité pour les candidatures, liée à un `Candidat` et une `OffreEmploi` via des relations `ManyToOne`.

### Repositories (`src/Repository/`)

*   `UserRepository.php`
*   `EntrepriseRepository.php`
*   `OffreEmploiRepository.php`
*   `CandidatureRepository.php`

### Contrôleurs (`src/Controller/`)

*   `SecurityController.php` : Gère la logique de connexion, déconnexion et d'inscription des recruteurs.
*   `RecruiterController.php` : Gère le tableau de bord du recruteur et la modification des informations de l'entreprise.
*   `OffreEmploiController.php` : Gère les opérations CRUD pour les offres d'emploi.

### Formulaires (`src/Form/`)

*   `EntrepriseType.php` : Formulaire pour l'entité `Entreprise`.
*   `OffreEmploiType.php` : Formulaire pour l'entité `OffreEmploi`.

### Templates (`templates/`)

*   `security/login.html.twig` : Page de connexion pour les recruteurs.
*   `security/register_recruiter.html.twig` : Page d'inscription pour les recruteurs.
*   `recruiter/dashboard.html.twig` : Tableau de bord du recruteur.
*   `recruiter/entreprise_edit.html.twig` : Formulaire d'édition de l'entreprise.
*   `recruiter/offres/index.html.twig` : Liste des offres d'emploi du recruteur.
*   `recruiter/offres/new.html.twig` : Formulaire de création d'une offre d'emploi.
*   `recruiter/offres/edit.html.twig` : Formulaire d'édition d'une offre d'emploi.
*   `base.html.twig` : Mise à jour de la barre de navigation pour inclure les liens recruteur et la gestion de l'authentification.

### Configuration (`config/packages/`)

*   `security.yaml` : Mis à jour pour utiliser l'entité `User` comme fournisseur d'utilisateurs, et pour configurer les `form_login` et `logout` pour les recruteurs.

## 3. Étapes d'intégration

Suivez ces étapes pour intégrer les modifications dans votre projet :

### Étape 1: Mettre à jour la base de données

Les nouvelles entités nécessitent des modifications de la base de données. Exécutez les commandes Doctrine pour générer et exécuter les migrations :

1.  **Générer une nouvelle migration** :
    ```bash
    php bin/console make:migration
    ```
    Cette commande va analyser les nouvelles entités et générer un fichier de migration dans `src/Migrations/`.

2.  **Exécuter la migration** :
    ```bash
    php bin/console doctrine:migrations:migrate
    ```
    Confirmez l'exécution lorsque vous y êtes invité. Cela créera les tables `user`, `entreprise`, `offre_emploi` et `candidature` dans votre base de données `securejob`.

### Étape 2: Mettre à jour les dépendances (si nécessaire)

Assurez-vous que toutes les dépendances sont à jour :

```bash
composer install
composer update
```

### Étape 3: Tester les fonctionnalités recruteur

1.  **Démarrer le serveur Symfony** :
    ```bash
    symfony serve
    ```
    Ou si vous utilisez XAMPP, assurez-vous que Apache et MySQL sont démarrés, puis accédez au projet via votre serveur web.

2.  **Accéder aux pages** :
    *   **Inscription Recruteur** : Naviguez vers `/register/recruiter` (ou utilisez le lien dans la barre de navigation).
    *   **Connexion Recruteur** : Naviguez vers `/login` (ou utilisez le lien dans la barre de navigation).
    *   **Dashboard Recruteur** : Après connexion, vous serez redirigé vers `/recruiter/dashboard`.
    *   **Gestion des offres** : Accédez à `/recruiter/offres/`.

### Étape 4: Intégration avec les candidats (future évolution)

Actuellement, l'entité `Candidat` est séparée de l'entité `User`. Pour une gestion unifiée des utilisateurs, il serait judicieux de :

*   Faire en sorte que l'entité `Candidat` soit également liée à l'entité `User` (par exemple, `OneToOne`).
*   Mettre à jour le `security.yaml` pour gérer les rôles `ROLE_CANDIDAT` et `ROLE_RECRUITER` de manière cohérente.
*   Implémenter la possibilité pour les candidats de postuler aux offres d'emploi via l'entité `Candidature`.

## 4. Sécurité

Les routes recruteur sont protégées par l'attribut `#[IsGranted('ROLE_RECRUITER')]` au niveau du contrôleur, garantissant que seuls les utilisateurs avec ce rôle peuvent y accéder.

## 5. Conclusion

Ces modifications fournissent une base solide pour les fonctionnalités recruteur de SecureJob. En suivant ce guide, vous devriez être en mesure d'intégrer et de tester ces nouvelles parties du projet. N'hésitez pas à explorer le code pour comprendre les implémentations détaillées et l'adapter à vos besoins spécifiques.
