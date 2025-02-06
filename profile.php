<?php
include 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
}

// Message de succès ou erreur
$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification de la mise à jour du mot de passe
    if (isset($_POST['new_password']) && !empty($_POST['new_password'])) {
        $new_password = password_hash($_POST['new_password'], PASSWORD_BCRYPT);

        // Mettre à jour le mot de passe dans la base de données
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE username = ?");
        $stmt->execute([$new_password, $_SESSION['username']]);

        $message = "Mot de passe mis à jour avec succès.";
    }

    // Vérification de la mise à jour du nom d'utilisateur
    if (isset($_POST['new_username']) && !empty($_POST['new_username'])) {
        $new_username = $_POST['new_username'];

        // Mettre à jour le nom d'utilisateur dans la base de données
        $stmt = $pdo->prepare("UPDATE users SET username = ? WHERE username = ?");
        $stmt->execute([$new_username, $_SESSION['username']]);

        // Mettre à jour la session
        $_SESSION['username'] = $new_username;

        $message = "Nom d'utilisateur mis à jour avec succès.";
    }

    // Vérification de la mise à jour de l'email
    if (isset($_POST['new_email']) && !empty($_POST['new_email'])) {
        $new_email = $_POST['new_email'];

        // Mettre à jour l'email dans la base de données
        $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE username = ?");
        $stmt->execute([$new_email, $_SESSION['username']]);

        $message = "Email mis à jour avec succès.";
    }
}

// Récupérer les infos de l'utilisateur connecté
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$_SESSION['username']]);
$user = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier le Profil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

    <!-- Logo -->
    <div class="text-center mb-4">
        <img src="logo.png" alt="Logo" width="150">
    </div>

    
<body class="container mt-5">

    <!-- Message de succès ou erreur -->
    <?php if ($message): ?>
        <div class="alert alert-info" role="alert">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <h2>Modifier mon profil</h2>

    <!-- Formulaire de mise à jour du mot de passe -->
    <form method="post">
        <div class="mb-3">
            <label for="new_username" class="form-label">Nouveau nom d'utilisateur</label>
            <input type="text" name="new_username" class="form-control" id="new_username" value="<?php echo htmlspecialchars($user['username']); ?>">
        </div>

        <div class="mb-3">
            <label for="new_email" class="form-label">Nouvel email</label>
            <input type="email" name="new_email" class="form-control" id="new_email" value="<?php echo htmlspecialchars($user['email']); ?>">
        </div>

        <div class="mb-3">
            <label for="new_password" class="form-label">Nouveau mot de passe</label>
            <input type="password" name="new_password" class="form-control" id="new_password">
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>

    <p class="mt-3">Nom d'utilisateur actuel : <?php echo htmlspecialchars($user['username']); ?></p>
    <p>Email actuel : <?php echo htmlspecialchars($user['email']); ?></p>
    <p>Rôle : <strong><?php echo htmlspecialchars($user['role']); ?></strong></p>

    <a href="dashboard.php" class="btn btn-danger">Retour au tableau de bord</a>
    
</body>
</html>
