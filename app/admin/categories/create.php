<?php

session_start();

// import fichier function article et variable global
require_once '/app/request/categories.php';
require_once '/app/env/variables.php';

// verifier les droits d'accès a utilisateurs, connecter ou non
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
    !empty($_POST['titre'])
) {

    $titre = strip_tags($_POST['titre']);

    // verifier si le titre n'existe pas deja 
    if (!findOneCategorieBYTitle($titre)) {
        // et s'il y a une image
        if ($_FILES['image']['size'] > 0 && $_FILES['image']['error'] === 0) {
            $imageName = uploadCategorieImage($_FILES['image']);
        }

        // on renvoie les données en bdd
        if (createCategorie($titre, isset($imageName) ? $imageName : null)) {
            $_SESSION['messages']['success'] = "Catégorie crée avec succès";

            http_response_code(302);
            header("Location: /admin/categories");
            exit();
        } else {
            $errorMessage = "Une erreur est survenue";
        }
    } else {
        $errorMessage = "Titre déjà utilisé par une autre catégorie";
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
    <title>Catégorie</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <?php require_once '/app/layout/messages.php'; ?>
        <section class="container mt-2">
            <h1 class="text-center">Création d'une catégorie</h1>
            <form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" class="form mt-2" enctype="multipart/form-data">
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
                    <label for="image">Image:</label>
                    <input type="file" name="image" id="image">
                </div>
                <button type="submit" class="btn btn-primary">Créer</button>
            </form>
        </section>
    </main>
</body>

</html>