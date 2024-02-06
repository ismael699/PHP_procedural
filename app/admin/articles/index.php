<?php

session_start();

require_once '/app/request/articles.php'; // relie les doc
require_once '/app/env/variables.php';

if (
    empty($_SESSION['LOGGED_USER']) || // si l'utilisateur n'est pas connecter 
    !in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles']) // ou s'il na pas le role admin
) {
    $_SESSION['messages']['error'] = 'Vous n\'avez pas les droits pour cette page';

    http_response_code(302);
    header("Location: /login.php");
    exit();
}

$_SESSION['token'] = bin2hex(random_bytes(50)); // pour proteger les infos avant la suppression a cause des hacker

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin article index | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <?php require_once '/app/layout/messages.php'; ?>
        <section class="container mt-2">
            <h1 class="text-center">Articles</h1>
            <div class="card-list mt-2">
                <?php foreach (findAllArticles() as $articles) : ?>
                    <div class="card">
                        <h2 class="card-header"><?= "$articles[title]"; ?></h2>
                        <em><strong>Date:</strong><?= convertDateArticle($articles['createdAt'], 'd/m/y'); ?></em> 
                        <p><strong>Description</strong> <?= substr($articles['description'], 0, 150) . '...'; ?></p> 
                        <div class="card-btn">
                            <a href="/admin/articles/update.php?id=<?= $articles['id']; ?>" class="btn btn-primary">Editer</a>
                            <form action="/admin/articles/delete.php" method="POST" onsubmit="return confirm('Etes-vous sur de vouloir supprimer ce titre ?')">
                                <input type="hidden" name="id" value="<?= $articles['id']; ?>">
                                <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>
</body>

</html>