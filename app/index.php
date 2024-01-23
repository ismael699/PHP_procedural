<?php
require_once '/app/env/variables.php';
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My first app PHP</title>
    <link rel="stylesheet" href="<?= $cssPath; ?>structure.css">
    <link rel="stylesheet" href="./assets/css/index.css">
</head>

<body>
    <?php require_once './layout/header.php' ?>
    <main>
        <h1>Hello world</h1>
        <form action="/contact.php" method="POST" enctype="multipart/form-data">
            <label for="prenom">Prenom:</label>
            <input type="text" name="prenom" id="prenom" placeholder="John">
            <label for="nom">Nom:</label>
            <input type="text" name="nom" id="nom" placeholder="Doe">
            <label for="password">Message:</label>
            <textarea name="message" id="message" cols="30" rows="10"></textarea>
            <label for="image">Image:</label>
            <input type="file" name="image" id="image">
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </form>
        <?php var_dump($_POST); ?>
    </main>
</body>

</html>