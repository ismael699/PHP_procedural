<?php

session_start();

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

$user = findOneUserById(!empty($_POST['id']) ? $_POST['id'] : 0);

if ($user) {
    if (hash_equals($_SESSION['token'], $_POST['token'])) {
        if (deleteUser($user['id'])) {
            $_SESSION['messages']['success'] = "User supprimé avec succès";

            http_response_code(302);
            header('Location: /admin/users');
            exit();
        } else {
            $_SESSION['messages']['error'] = "Une erreur est survenue, veuillez réessayer";
        } 
    } else {
        $_SESSION['messages']['error'] = "Token CSRF invalide";
    }
} else {
    $_SESSION['messages']['error'] = 'User non trouvé';
}

http_response_code(302);
header("Location: /admin/users");
exit();

var_dump($_POST);
