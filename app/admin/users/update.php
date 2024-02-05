<?php

session_start();

require_once '/app/env/variables.php';
require_once '/app/request/users.php';

if (
    empty($_SESSION['LOGGED_USER']) ||
    !in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])
) {
    $_SESSION['messages']['error'] = 'Vous n\'avez pas les droits pour cette page';

    http_response_code(302);
    header("Location: /login.php");
    exit();
}

$user = findOneUserById(isset($_GET['id']) ? $_GET['id'] : 0);

if (!$user) {
    $_SESSION['messages']['error'] = 'Utilisateur non trouvé';

    header('Location: /admin/users');
    exit();
}

// vérification de soumission de formulaire
if (
    !empty($_POST['firstName']) &&
    !empty($_POST['lastName']) &&
    !empty($_POST['email'])
) {


    // Nettoyage des données
    $firstName = strip_tags($_POST['firstName']); // strip = pour une chaine de caractere 
    $lastName = strip_tags($_POST['lastName']);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL); //filter = 
    $roles = $_POST['roles'];

    if ($email) {
        $oldEmail = $user['email'];

        if ($oldEmail === $email || !findOneUserByEmail($email)) {
            if (updateUser($user['id'], $firstName, $lastName, $email, $roles)) {
                $_SESSION['messages']['success'] = 'Utilisateur modifié avec succes';

                http_response_code(302);
                header("Location: /admin/users");
                exit();
            } else {
                $errorMessage = 'Une erreur est survenue';
            }
        } else {
            $errorMessage = 'Adrese email deja utilisée';
        }
    } else {
        $errorMessage = 'Veuillez rentrer un email valide';
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorMessage = 'Veuillez remplir touqs les champs obligatoires';
}


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification user | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <section class="container mt-2">
            <h1 class="text-center">Editer un utilisateur</h1>
            <form action="<?= $_SERVER['PHP_SELF'] . '?id=' . $_GET['id']; ?>" class="form" method="POST">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger">
                        <?= $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="group-input">
                    <label for="firstName">Prénom:</label>
                    <input type="text" name="firstName" id="firstName" placeholder="John" value="<?= $user['firstName']; ?>" required>
                </div>
                <div class="group-input">
                    <label for="lastName">Nom:</label>
                    <input type="text" name="lastName" id="lastName" placeholder="Doe" value="<?= $user['lastName']; ?>" required>
                </div>
                <div class="group-input">
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" placeholder="john@exemple.com" value="<?= $user['email']; ?>" required>
                </div>
                <div class="group-input checkbox">
                    <input type="checkbox" name="roles[]" id="role-user" value="ROLE_USER" checked>
                    <label for="role-user">Utilisateur</label>
                </div>
                <div class="group-input checkbox">
                    <input type="checkbox" name="roles[]" id="role-editor" value="ROLE_EDITOR" <?= $user['roles'] ? (in_array('ROLE_EDITOR', json_decode($user['roles'])) ? 'checked' :null) :null;?>>
                    <label for="role-editor">Editeur</label>
                </div>
                <div class="group-input checkbox">
                    <input type="checkbox" name="roles[]" id="role-admin" value="ROLE_ADMIN" <?= $user['roles'] ? (in_array('ROLE_ADMIN', json_decode($user['roles'])) ? 'checked' :null) :null;?>>
                    <label for="role-admin">Administrateur</label>
                </div>
                <button type="submit" class="btn btn-primary">Modifier</button>
            </form>
        </section>
    </main>
</body>

</html>