<?php

session_start();

require_once '/app/request/categories.php'; // relie les doc
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
    <title>Admin categorie index | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <?php require_once '/app/layout/messages.php'; ?>
        <section class="container mt-2">
            <h1 class="text-center">Catégories</h1>
            <a href="/admin/categories/create.php" class="btn btn-primary">Ajouter une catégorie</a>
            <div class="card-list mt-2">
                <?php foreach (findAllCategorie() as $categorie) : ?> 
                    <div class="card">
                        <?php if ($categorie['imageName']) : ?>
                            <img src="/uploads/categories/<?= $categorie['imageName']; ?>" alt="" loading="lazy">
                        <?php endif; ?>
                        <h2 class="card-header"><?= "$categorie[title]"; ?></h2>
                        <em><strong>Auteur:</strong><?= "$categorie[id]"; ?></em>
                        <div class="card-btn">
                            <a href="/admin/categories/update.php?id=<?= $categorie['id']; ?>" class="btn btn-primary">Editer</a> 
                            <form action="/admin/categories/delete.php" method="POST" onsubmit="return confirm('Etes-vous sur de vouloir supprimer cette catégorie ?')">
                                <input type="hidden" name="id" value="<?= $categorie['id']; ?>">
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