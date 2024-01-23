<?php

session_start();

$_SESSION['test'] = 'Ceci est un test';
$_SESSION['LOGGED_USER'] = [];

require_once '/app/env/variables.php';

/* login de connexion */
$users = [
    [
        'email' => 'pierre@test.com',
        'password' => 'Test1234!',
    ],
    [
        'email' => 'isma@test.com',
        'password' => 'Test1234!',
    ]
];

/* premiere partie, connection */
if (!empty($_POST['email']) && !empty($_POST['password'])) { /* verifie si l'email et le mdp ne sont pas vide */

    foreach ($users as $user) {
        if (
            in_array($_POST['email'], $user) &&
            $_POST['password'] === $user['password'] /* 'in_array' veut dire 'si une valeur existe dans un tableau, a chercher dans 'users' */
        ) { 
            $_SESSION['LOGGED_USER'] = [
                'email' => $user['email'],
            ];

            http_response_code(302);
            header("Location: /");
            exit();
        }
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') { /* si les informations ne sont pas les bonnes */
    $errorMessage = "Veuillez renseigner les champs obligatoires"; /* alors tu affiche ce message */
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connection My first app</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <section class="container">
            <h1 class="text-center mt-2">Connexion</h1>
            <form action="/login.php" method="POST" class="form">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger">
                        <?= $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="group-input">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="@" required>
                </div>
                <div class="group-input">
                    <label for="password">Mot de passe:</label>
                    <input type="password" name="password" id="password" placeholder="***" required>
                </div>
                <button type="submit" class="btn btn-primary">Connexion</button>
            </form>
        </section>
    </main>
</body>

</html>