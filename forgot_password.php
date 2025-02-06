<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];

    // Vérifier si l'email existe
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user) {
        // Générer un token sécurisé
        $token = bin2hex(random_bytes(50));

        // Insérer ou mettre à jour le token dans la base de données
        $stmt = $pdo->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?) ON DUPLICATE KEY UPDATE token = ?");
        $stmt->execute([$email, $token, $token]);

        // Lien de réinitialisation
        $reset_link = "http://localhost/sysinfo/reset_password.php?token=$token";

        // Envoi de l'email avec PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.mailtrap.io'; // Serveur Mailtrap
            $mail->SMTPAuth = true;
            $mail->Username = ''; // Remplace avec ton identifiant Mailtrap
            $mail->Password = ''; // Remplace avec ton mot de passe Mailtrap
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 2525;
        
            // Configurer l'e-mail
            $mail->setFrom('noreply@test.com', 'Mon Application');
            $mail->addAddress($email); // Adresse du destinataire
            $mail->Subject = 'Réinitialisation de mot de passe';
            $mail->Body = "Cliquez ici pour réinitialiser votre mot de passe : http://localhost/sysinfo/reset_password.php?token=$token";
        
            // Envoyer l'e-mail
            $mail->send();
            echo 'Email envoyé. Vérifiez Mailtrap !';
        } catch (Exception $e) {
            echo "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
        }
    
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Mot de passe oublié</h2>
    <form method="post">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
</body>
</html>
