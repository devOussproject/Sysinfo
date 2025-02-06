<?php
include 'config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit();
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
    <title>Tableau de Bord</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-5">

    <!-- Logo -->
    <div class="text-center mb-4">
        <img src="logo.png" alt="Logo" width="150">
    </div>

<!-- Message de succès -->
    <div class="alert alert-success text-center" role="alert">
        <strong>Vous êtes bien connecté !</strong>
    </div>

    <h2>Bienvenue, <?php echo htmlspecialchars($user['username']); ?> !</h2>
    <p>Email : <?php echo htmlspecialchars($user['email']); ?></p>
    <p>Rôle : <strong><?php echo htmlspecialchars($user['role']); ?></strong></p>

    <a href="profile.php" class="btn btn-info">Modifier mon profil</a>
    
    <?php if ($user['role'] == 'admin') { ?>
        <a href="logs.php" class="btn btn-warning">Voir les logs</a>
    <?php } ?>

    <a href="logout.php" class="btn btn-danger">Se déconnecter</a>
</body>
</html>
