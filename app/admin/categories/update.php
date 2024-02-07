<?php

session_start();

require_once '/app/env/variables.php'; // lie les documents
require_once '/app/request/categories.php';

// verifie si il est autorisé a acceder a cette page, si il est Admin
if (
    empty($_SESSION['LOGGED_USER']) ||
    !in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])
) {
    $_SESSION['messages']['error'] = 'Vous n\'avez pas les droits pour cette page';

    http_response_code(302);
    header("Location: /login.php");
    exit();
}

// declaration d'une variable + 
$categories = findOneCategorieById(isset($_GET['id']) ? $_GET['id'] : 0);

if (!$categories) {
    $_SESSION['messages']['error'] = 'Article non trouvé';

    http_response_code(302);
    header('Location: /admin/categories');
    exit();
}

// vérification de soumission de formulaire
if (
    !empty($_POST['title']) 
) {
    // Nettoyage des données - verifie si c'est une chaine de caractère simple
    $title = strip_tags($_POST['title']); // strip = pour une chaine de caractere 

    $oldTitle = $categories['title'];

    if ($oldTitle === $title || !findOneCategorieBYTitle($title)) {
        if ($_FILES['image']['size'] > 0 && $_FILES['image']['error'] === 0) {
            $imageName = uploadCategorieImage($_FILES['image'], $categories['imageName']);
        }

        if (updateCategorie($title, isset($imageName) ? $imageName : null)) {
            $_SESSION['messages']['success'] = "Categorie mis a jour avec succes";

            http_response_code(302);
            header('Location: /admin/categories');
            exit();
        } else {
            $errorMessage = 'une erreur est survenue';
        }
    } else {
        $errorMessage = 'Le titre est deja utiliser';
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errorMessage = "Veuullez remplir touts les champs";
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modification categorie | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <?php require_once '/app/layout/messages.php'; ?>
        <section class="container mt-2">
            <h1 class="text-center">Modifier une categorie</h1>
            <form action="<?= $_SERVER['PHP_SELF'] . '?id=' . $_GET['id']; ?>" method="POST" class="form mt-2" enctype="multipart/form-data">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger">
                        <?= $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="group-input">
                    <label for="">Titre:</label>
                    <input type="text" name="title" id="titre" placeholder="Titre" value="<?= $categories['title']; ?>" required>
                </div>
                <div class="group-input">
                    <label for="image">Image:</label>
                    <input type="file" name="image" id="image">
                    <?php if($categories['imageName']) : ?>
                        <img src="/uploads/categories/<?= $categories['imageName']; ?>" alt="" loading="lazy">
                    <?php endif; ?>
                </div>
                <button type="submit" class="btn btn-primary">Modifier</button>
            </form>
        </section>
    </main>
</body>

</html>
