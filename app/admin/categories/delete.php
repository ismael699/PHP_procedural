<?php

session_start();

require_once '/app/request/categories.php';

if (
    empty($_SESSION['LOGGED_USER']) ||
    !in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])
) {
    $_SESSION['messages']['error'] = 'Vous n\'avez pas les droits pour cette page';

    http_response_code(302);
    header("Location: /login.php");
    exit();
}

// recupere la categorie a supprimer, depuis la bdd
$categories = findOneCategorieById(!empty($_POST['id']) ? $_POST['id'] : 0);

if ($categories) {
    if (hash_equals($_SESSION['token'], $_POST['token'])) {
        if (deleteCategorie($categories['id'])) {
            if (
                $categories['imageName'] &&
                file_exists("/app/uploads/categories/$categories[imageName]")
            ) {
                unlink("/app/uploads/categories/$categories[imageName]");
            }

            $_SESSION['message']['success'] = "Catégorie supprimé avec succès";
        } else {
            $_SESSION['message']['error'] = "Une erreur est survenue";
        }
    } else {
        $_SESSION['messages']['error'] = "Token CSRF invalide";
    }
} else {
    $_SESSION['messages']['error'] =  "Catégorie non trouvé";
}

http_response_code(302);
header("Location: /admin/categories");
exit();