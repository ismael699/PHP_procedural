<?php

session_start();

require_once '/app/request/articles.php';

if (
    empty($_SESSION['LOGGED_USER']) ||
    !in_array('ROLE_ADMIN', $_SESSION['LOGGED_USER']['roles'])
) {
    $_SESSION['messages']['error'] = 'Vous n\'avez pas les droits pour cette page';

    http_response_code(302);
    header("Location: /login.php");
    exit();
}

// recupere l'article a supprimer, depuis la bdd
$articles = findOneArticleById(!empty($_POST['id']) ? $_POST['id'] : 0);

if ($article) {
    if (hash_equals($_SESSION['token'], $_POST['token'])) {
        if (deleteArticle($article['id'])) {
            if (
                $article['imageName'] &&
                file_exists("/app/uploads/articles/$article[imageName]")
            ) {
                unlink("/app/uploads/articles/$article[imageName]");
            }

            $_SESSION['message']['success'] = "Article supprimé avec succès";
        } else {
            $_SESSION['message']['error'] = "Une erreur est survenue";
        }
    } else {
        $_SESSION['messages']['error'] = "Token CSRF invalide";
    }
} else {
    $_SESSION['messages']['error'] =  "Article non trouvé";
}

http_response_code(302);
header("Location: /admin/articles");
exit();



// DEUXIEME FACON DE FAIRE

// if ($articles) {
//     if (hash_equals($_SESSION['token'], $_POST['token'])) { // prévien des attaque CSRF
//         if (deleteArticle($articles['id'])) { // suppression des articles dans la bdd
//             $_SESSION['messages']['success'] = "Article supprimé avec succès"; // si la suppression est réussi alors redirige ...

//             http_response_code(302);
//             header('Location: /admin/articles'); // ... vers la page 'articles' avec le message succès
//             exit();
//         } else { // si une erreur apparait lors de la suppression
//             $_SESSION['messages']['error'] = "Une erreur est survenue, veuillez réessayer";
//         } 
//     } else { // si le token n'est pas valide
//         $_SESSION['messages']['error'] = "Token CSRF invalide";
//     }
// } else { // si l'article n'est pas trouver
//     $_SESSION['messages']['error'] = 'Article non trouvé';
// }
