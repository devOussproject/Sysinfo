<?php
// Inclure le fichier de configuration pour la connexion à la base de données
include 'config.php';

// Activer l'affichage des erreurs PHP pour le débogage
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Variable pour stocker les erreurs
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Validation des champs
    if (!empty($username) && !empty($email) && !empty($password)) {
        // Hacher le mot de passe avant de l'enregistrer
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        try {
            // Vérifier si l'email ou le nom d'utilisateur existent déjà dans la base de données
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            $user = $stmt->fetch();

            if ($user) {
                // Si l'utilisateur ou l'email existe déjà, afficher un message d'erreur
                $error = "Erreur : Identifiant ou email déjà pris.";
            } else {
                // Sinon, insérer les données dans la base de données
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hashedPassword]);

                // Redirection après l'inscription réussie
                header("Location: index.php"); // Redirige vers la page d'accueil (ou autre)
                exit();
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de la base de données
            $error = "Erreur lors de l'inscription : " . $e->getMessage();
        }
    } else {
        // Si des champs sont vides
        $error = "Tous les champs doivent être remplis.";
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <style>
        /* Ajout de styles pour améliorer l'affichage */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        input[type="text"], input[type="email"], input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0 20px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
        }

        input[type="text"]:focus, input[type="email"]:focus, input[type="password"]:focus {
            border-color: #007bff;
            outline: none;
        }

        button {
            width: 100%;
            padding: 12px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }

        .error {
            color: #d9534f;
            font-size: 14px;
            margin-bottom: 20px;
        }

        .success {
            color: #5bc0de;
            font-size: 14px;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>
    <div class="container">
        <h2>Inscription</h2>

        <?php
        // Affichage du message d'erreur s'il y en a
        if ($error) {
            echo "<p class='error'>$error</p>";
        }
        ?>

        <!-- Formulaire d'inscription -->
        <form method="POST" action="">
            <label for="username">Nom d'utilisateur :</label>
            <input type="text" name="username" id="username" required><br><br>

            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required><br><br>

            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" required><br><br>

            <button type="submit">S'inscrire</button>
            <a href="index.php" class="btn btn-secondary mt-3">Retour</a>
            </form>
    </div>
</body>
</html>
