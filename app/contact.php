<?php

session_start();


require_once '/app/env/variables.php';
if (!empty($_FILES['image']) && $_FILES['image']['error'] === 0) { /* on verifie si ont upload une image et qu'elle n'a pas d'erreur */
    if ($_FILES['image']['size'] < 16000000) { /* on verifie la taille du fichier */
        $fileInfo = pathinfo($_FILES['image']['name']); /*on verifie l'extension du fichier */

        $extension = $fileInfo['extension']; /* on recupere l'extension du fichier uploadé */

        $extensionAllowed = ['jpg', 'png', 'jpeg', 'svg', 'webp', 'gif']; /* extension autorisé */

        if (in_array($extension, $extensionAllowed)) { /* on verifie que l'extension du fichier est autorise */
            $fileName = $fileInfo['filename'] . '_' . (new DateTime())->format('Y-m-d_H:i:s') . '.' . $extension;

            move_uploaded_file($_FILES['image']['tmp_name'], "/app/uploads/$fileName");
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?= require_once '/app/layout/header.php'; ?>
    <main>
        <h1>Votre demande de contact</h1>
        <?php var_dump($_FILES); ?>
        <?php if (!empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['message'])) : ?>
            <div>
                <p>Prenom: <?= $_POST['prenom']; ?></p>
                <p>Nom: <?= $_POST['nom']; ?></p>
                <p>Message: <?= $_POST['message']; ?></p>
            </div>
        <?php else : ?>
            <div class="alert alert-danger">Veuillez soumettre le formulaire</div>
        <?php endif; ?>
        <?php var_dump($_POST); ?>
    </main>
</body>

</html>