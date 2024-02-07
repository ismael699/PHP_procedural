<?php
session_start();

require_once '/app/env/variables.php';

// On vérifie qu'on a uploadé une image et qu'il  n'y a pas d'erreurs
if (!empty($_FILES['image']) && $_FILES['image']['error'] === 0) {
    // On vérifie la taille du fichier
    if ($_FILES['image']['size'] < 16000000) {
        // On vérifie l'extension du fichier
        $fileInfo = pathinfo($_FILES['image']['name']);

        // On récupère l'extension du fichier uploadé
        $extension =  $fileInfo['extension'];

        // On définit les extensions autorisées
        $extensionAllowed = ['jpg', 'png', 'jpeg', 'svg', 'webp', 'gif'];

        // On vérifie que l'extension du fichier est autorisée
        if (in_array($extension, $extensionAllowed)) {
            $fileName = $fileInfo['filename']
                . '_' .
                (new DateTime())->format('d-m-Y_H:i:s')
                . '.' . $extension;

            move_uploaded_file($_FILES['image']['tmp_name'], "/app/uploads/$fileName");
        }
    }
}

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
</head>

<body>
    <?php require_once '/app/layout/header.php'; ?>
    <main>
        <h1>Votre demande de contact</h1>
        <?php var_dump($_FILES); ?>
        <?php if (!empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['message'])) : ?>
            <div class="card">
                <p>Prenom: <?= strip_tags($_POST['prenom']); ?></p>
                <p>Nom: <?= htmlspecialchars($_POST['nom']); ?></p>
                <p>Message: <?= $_POST['message']; ?></p>
            </div>
        <?php else : ?>
            <div class="alert alert-danger">Veuillez soumettre le formulaire</div>
        <?php endif; ?>
    </main>
</body>

</html>