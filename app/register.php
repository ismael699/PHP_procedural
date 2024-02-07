<?php

session_start();

require_once '/app/env/variables.php';
require_once '/app/request/users.php';

// On vérifie que les données ne sont pas vides
if (
    !empty($_POST['firstName']) &&
    !empty($_POST['lastName']) &&
    !empty($_POST['email']) &&
    !empty($_POST['password'])
) {
    // Nettoyage des données
    $firstName = strip_tags($_POST['firstName']);
    $lastName = strip_tags($_POST['lastName']);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $password = password_hash($_POST['password'], PASSWORD_ARGON2I);

    // gérer les erreurs utilisateurs
    if ($email) {
        // L'email est ok, on vérifie s'il n'existe pas en BDD

        if (!findOneUserByEmail($email)) {
            // On créé l'utilisateur en BDD
            if (createUser($firstName, $lastName, $email, $password)) {
                // On redirige vers la page de connexion
                http_response_code(302);
                header("Location: /login.php");
                exit();
            } else {
                $errorMessage = "Une erreur est survenue, veuillez réessayer";
            }
        } else {
            $errorMessage = "L'email est déjà utilisé par un autre compte";
        }
    } else {
        $errorMessage = "Veuillez rentrer un email valide";
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorMessage = 'Veuillez remplir tous les champs obligatoires';
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <section class="container mt-2">
            <h1 class="text-center">Inscription</h1>
            <form action="<?= $_SERVER['PHP_SELF']; ?>" class="form" method="POST">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger">
                        <?= $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="group-input">
                    <label for="firstName">Prénom:</label>
                    <input type="text" name="firstName" id="firstName" placeholder="John" required>
                </div>
                <div class="group-input">
                    <label for="lastName">Nom:</label>
                    <input type="text" name="lastName" id="lastName" placeholder="Doe" required>
                </div>
                <div class="group-input">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="john@exemple.com" required>
                </div>
                <div class="group-input">
                    <label for="password">Mot de passe:</label>
                    <input type="password" name="password" id="password" placeholder="S3CR3T" required>
                </div>
                <button type="submit" class="btn btn-primary">S'inscrire</button>
            </form>
        </section>
    </main>
</body>

</html>