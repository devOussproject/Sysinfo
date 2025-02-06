<?php
include 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];

        // Enregistrement de la connexion réussie
        $stmt = $pdo->prepare("INSERT INTO login_logs (username, status, ip_address, user_agent) VALUES (?, 'success', ?, ?)");
        $stmt->execute([$username, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']]);

        header("Location: dashboard.php");
        exit();
    } else {
        $stmt = $pdo->prepare("INSERT INTO login_logs (username, status, ip_address, user_agent) VALUES (?, 'failed', ?, ?)");
        $stmt->execute([$username, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']]);
        $error = "Identifiant ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">

    <!-- Logo -->
    <div class="text-center mb-4">
        <img src="logo.png" alt="Logo" width="150">
    </div>

    <h2>Connexion</h2>

    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>

    <form method="post">
        <div class="mb-3">
            <label>Identifiant</label>
            <input type="text" name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Mot de passe</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Se connecter</button>
        <button type="reset" class="btn btn-primary">Réinitialiser</button>
        <a href="register.php" class="btn btn-secondary">Créer un compte</a>
        <a href="forgot_password.php" class="btn btn-link">Mot de passe oublié ?</a>
    </form>

</body>
</html>
