# Documentation du Projet : Système d'authenfication utilisateur

## 1. Introduction
Ce projet est un système d'authentification utilisateur complet avec une fonctionnalité de réinitialisation de mot de passe. Il permet aux utilisateurs de récupérer leur accès en cas d'oubli en envoyant un e-mail contenant un lien de réinitialisation.

## 2. Fonctionnalités Principales
- Inscription et Connexion des utilisateurs
- Réinitialisation de mot de passe via e-mail
- Hachage des mots de passe avec bcrypt
- Journalisation des tentatives de connexion
- Tableau de bord utilisateur
- Interface responsive avec Bootstrap 5

## 3. Technologies Utilisées
- Langages : PHP, HTML, CSS, JavaScript
- Base de données : MySQL
- Frameworks et bibliothèques : Bootstrap 5, PHPMailer
- Serveur local : WAMP/XAMPP

## 4. Installation et Configuration
### Prérequis
- Serveur local (WAMP/XAMPP)
- PHP 8+
- MySQL
- PHPMailer (pour l'envoi d'e-mails)

## 5. Utilisation
- Un utilisateur peut s'inscrire et se connecter.
- Une fois inscrit, ces informations s'enregistre directement sur la base de données.
- Après, peut se connecter avec ses identifiants : 
        - Si il est inscrit, il se derige vers le tableau de bord ou s'est afficher un message qu'il est bien connecté.
        - Si il est pas inscrit, s'affiche un message "Idenfiant ou mot de passe incorrect".
- En cas de mot de passe oublié, il peut demander un e-mail de réinitialisation.
- Après avoir reçu l'e-mail, il clique sur le lien et saisit un nouveau mot de passe.
- Une fois réinitialisé, il peut se reconnecter avec son nouveau mot de passe.

### Étapes d'installation
1. **Cloner le projet**
   ```sh
   git clone https://github.com/votre-repo.git
   ```
2. **Importer la base de données**
   - Ouvrir phpMyAdmin
   - Créer une base de données `sysinfo`
   - Créer 3 tables :
        - Users
        - Reset mot de passe
        - Journalisation de mot de passe

3. **Configurer la connexion à la base de données**
   - Modifier `config.php` avec vos paramètres MySQL :
   ```php
   define('DB_HOST', 'localhost');
   define('DB_USER', 'root');
   define('DB_PASS', '');
   define('DB_NAME', 'sysinfo');
   ```

4. **Démarrer le serveur local**
   - Lancer Apache et MySQL depuis WAMP/XAMPP
   - Accéder au projet via : `http://localhost/sysinfo`

## 5. Structure du Projet
```
/sysinfo/
│── config.php          # Configuration de la base de données
│── index.php           # Page de connexion
│── register.php        # Page d'inscription
│── dashboard.php       # Tableau de bord
│── forgot_password.php # Formulaire de récupération
│── reset_password.php  # Formulaire de réinitialisation
│── login_logs.php      # Journal des connexions
│── README.md           # Documentation du projet

```

## 6. Fonctionnement du Système
1. **Inscription et Connexion**
   - Un utilisateur crée un compte avec un mot de passe haché.
   - Il peut se connecter et accéder à son tableau de bord.

2. **Réinitialisation du mot de passe**
   - L'utilisateur saisit son e-mail sur `forgot_password.php`.
   - Un token unique est généré et stocké en base de données.
   - Un e-mail avec un lien de réinitialisation est envoyé.
   - En cliquant sur le lien, il accède à `reset_password.php` pour entrer un nouveau mot de passe.
   - Une fois le mot de passe mis à jour, le token est supprimé.

## 7. Configuration de l'Envoi d'E-mails (PHPMailer)
Si l'envoi d'e-mails ne fonctionne pas avec `mail()`, utilisez PHPMailer :
```php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'votre-email@gmail.com';
    $mail->Password = 'votre-mot-de-passe';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('votre-email@gmail.com', 'Support');
    $mail->addAddress($email);
    $mail->Subject = 'Réinitialisation de votre mot de passe';
    $mail->Body = "Cliquez ici pour réinitialiser votre mot de passe : http://localhost/sysinfo/reset_password.php?token=$token";

    $mail->send();
} catch (Exception $e) {
    echo "Erreur lors de l'envoi : {$mail->ErrorInfo}";
}
```

## 8. Sécurité
- Utilisation de `password_hash()` pour hacher les mots de passe.
- Protection contre les injections SQL avec `PDO::prepare()`.
- Tokens de réinitialisation aléatoires pour une sécurité accrue.
- Journalisation des tentatives de connexion pour suivi des activités suspectes.

## 9. Auteur
SAHM Oussama - Etudiant en M1 Informatique et Big Data
