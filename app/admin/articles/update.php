<?php

session_start();

require_once '/app/env/variables.php'; // lie les documents
require_once '/app/request/articles.php';

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
$articles = findOneArticleById(isset($_GET['id']) ? $_GET['id'] : 0);

// 
if (!$articles) {
    $_SESSION['messages']['error'] = 'Article non trouvé';

    http_response_code(302);
    header('Location: /admin/articles');
    exit();
}

// vérification de soumission de formulaire
if (
    !empty($_POST['title']) &&
    !empty($_POST['description'])
) {
    // Nettoyage des données - verifie si c'est une chaine de caractère simple
    $title = strip_tags($_POST['title']); // strip = pour une chaine de caractere 
    $description = strip_tags($_POST['description']);
    $enable = isset($_POST['enable']) ? 1 : 0;

    $oldTitle = $articles['title'];

    if ($oldTitle === $title || !findOneArticleByTitle($title)) {
        if (updateArticle($articles['id'], $title, $description, $enable)) {
            $_SESSION['messages']['success'] = "Article mis a jour avec succes";

            http_response_code(302);
            header('Location: /admin/articles');
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
    <title>Modification article | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <?php require_once '/app/layout/messages.php'; ?>
        <section class="container mt-2">
            <h1 class="text-center">Modifier un article</h1>
            <form action="<?= $_SERVER['PHP_SELF'] . '?id=' . $_GET['id']; ?>" method="POST" class="form mt-2">
                <?php if (isset($errorMessage)) : ?>
                    <div class="alert alert-danger">
                        <?= $errorMessage; ?>
                    </div>
                <?php endif; ?>
                <div class="group-input">
                    <label for="">Titre:</label>
                    <input type="text" name="title" id="titre" placeholder="Titre" value="<?= $articles['title']; ?>" required>
                </div>
                <div class="group-input">
                    <label for="">Description:</label>
                    <textarea name="description" id="description" cols="30" rows="10" placeholder="Description" required><?= $articles['description']; ?></textarea>
                </div>
                <div class="group-input checkbox">
                    <input type="checkbox" name="enable" id="enable" <?= $articles['enable'] ? 'checked' : null; ?>>
                    <label for="enable">Actif</label>
                </div>
                <button type="submit" class="btn btn-primary">Modifier</button>
            </form>
        </section>
    </main>
</body>

</html>