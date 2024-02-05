<?php

session_start();

// import fichier function article et variable global
require_once '/app/request/articles.php';
require_once '/app/env/variables.php';

// verifier les droits utilisateurs connecter
if (
    empty($_SESSION['LOGGED_USER']) || // si l'utilisateur n'est pas connecter 
    !in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles']) // ou s'il na pas le role admin
) {
    $_SESSION['messages']['error'] = 'Vous n\'avez pas les droits pour cette page';

    http_response_code(302);
    header("Location: /login.php");
    exit();
}

// verification si les champs obligatoire sont remplis
if (
    !empty($_POST['titre']) &&
    !empty($_POST['description'])
) {
    // nettoyage des données 
    $titre = strip_tags($_POST['titre']);
    $description = strip_tags($_POST['description']);
    $enable = isset($_POST['enable']) ? 1 : 0;

    // verifier si le titre n'existe pas deja 
    if (!findOneArticleBYTitle($titre)) {
        // on renvoie les données en bdd
        if (createArticle($titre, $description, $enable)) {
            $_SESSION['messages']['success'] = "Article crée avec succès";

            http_response_code(302);
            header("Location: /admin/articles");
            exit();
        } else {
            $errorMessage = "Une erreur est survenue";
        }
    } else {
        $errorMessage = "Titre déjà utilisé par un autre article";
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorMessage = "Veuillez remplir les champs obligatoires";
}




?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Creation d'un article</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <?php require_once '/app/layout/messages.php'; ?>
        <section class="container mt-2">
            <h1 class="text-center">Création d'un article</h1>
            <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" class="form mt-2">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger">
                        <?= $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="group-input">
                    <label for="">Titre:</label>
                    <input type="text" name="titre" id="titre" placeholder="Titre" required>
                </div>
                <div class="group-input">
                    <label for="">Description:</label>
                    <textarea name="description" id="description" cols="30" rows="10" placeholder="Description" required></textarea>
                </div>
                <div class="group-input checkbox">
                    <input type="checkbox" name="enable" id="enable">
                    <label for="enable">Actif</label>
                </div>
                <button type="submit" class="btn btn-primary">Créer</button>
            </form>
        </section>
    </main>
</body>

</html>