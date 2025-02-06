<?php
include 'config.php';

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    // Vérifier si le token existe dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM password_resets WHERE token = ?");
    $stmt->execute([$token]);
    $resetRequest = $stmt->fetch();

    if (!$resetRequest) {
        die("<div class='container text-center mt-5'>
                <div class='alert alert-danger' role='alert' style='font-size: 18px; padding: 20px; border-radius: 10px;'>
                    Lien invalide ou expiré !
                </div>
            </div>");
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

        // Mettre à jour le mot de passe de l'utilisateur
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE email = ?");
        $stmt->execute([$new_password, $resetRequest['email']]);

        // Supprimer le token après utilisation
        $stmt = $pdo->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->execute([$resetRequest['email']]);

        echo "<div class='container text-center mt-5'>
                <div class='alert alert-success' role='alert' style='font-size: 18px; padding: 20px; border-radius: 10px;'>
                     Mot de passe réinitialisé avec succès ! <br><br>
                    <a href='index.php' class='btn btn-primary' style='padding: 10px 20px; font-size: 16px; border-radius: 8px;'>Se connecter</a>
                </div>
              </div>";
        exit();
    }
} else {
    die("<div class='container text-center mt-5'>
            <div class='alert alert-danger' role='alert' style='font-size: 18px; padding: 20px; border-radius: 10px;'>
                 Aucun token fourni !
            </div>
        </div>");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Réinitialisation du mot de passe</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2>Réinitialisation du mot de passe</h2>
    <form method="post">
        <div class="mb-3">
            <label>Nouveau mot de passe</label>
            <input type="password" name="new_password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-success">Changer le mot de passe</button>
    </form>
</body>
</html>
