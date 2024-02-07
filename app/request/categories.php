<?php

require_once '/app/conf/mysql.php';


function findOneCategorieById(int $id): bool|array
{
    global $db;

    $sqlStatement = $db->prepare("SELECT * FROM categorie WHERE id = :id");
    $sqlStatement->execute([
        'id' => $id,
    ]);
    return $sqlStatement->fetch();
}


function findAllCategorie(): array    // permet de recuperer les categories en bdd
{
    global $db;

    $sqlStatement = $db->prepare("SELECT * FROM categorie");
    $sqlStatement->execute();

    return $sqlStatement->fetchAll();
}


function updateCategorie(string $title, ?string $imageName): bool
{
    global $db;

    try {
        $query = "UPDATE categorie SET title = :title";
        $params = [
            'title' => $title,
        ];

        if ($imageName) {
            $query .= ", imageName = :imageName";
            $params['imageName'] = $imageName;
        }

        $sqlStatement = $db->prepare($query);
        $sqlStatement->execute($params);
    } catch (PDOException $error) {
        return false;
    }
    return true;
}


function findOneCategorieByTitle(string $title): bool|array
{
    global $db;

    $sqlStatement = $db->prepare("SELECT * FROM categorie WHERE title = :title");
    $sqlStatement->execute([
        'title' => $title,
    ]);
    return $sqlStatement->fetch();
}


function createCategorie(string $title, ?string $imageName): bool
{
    global $db;

    try {
        $params = [
            'title' => $title,
        ];

        if ($imageName) {
            $query = "INSERT INTO categorie(title, imageName) VALUES(:title, :imageName)";
            $params['imageName'] = $imageName;
        } else {
            $query = "INSERT INTO categorie(title, imageName) VALUES (:title, :imageName)";
        }
        $sqlStatement = $db->prepare($query);
        $sqlStatement->execute($params);
    } catch (PDOException $error) {
        die($error->getMessage());
        return false;
    }
    return true;
}


function uploadCategorieImage(array $image, ?string $oldImageName = null): bool|string // verifie la taille, les extension d'une image
{
    if ($image['size'] < 16000000) { // taille
        $fileInfo = pathinfo($image['name']);

        $extension = $fileInfo['extension']; // extension 
        $extensionAllowed = ['png', 'jpg', 'jpeg', 'webp', 'svg', 'gif'];

        if (in_array($extension, $extensionAllowed)) { // verifie l'extension
            $fileName = $fileInfo['filename'] . (new DateTime())->format('_Y-m-d_H:i:s') . '.' . $extension;

            move_uploaded_file($image['tmp_name'], "/app/uploads/categories/$fileName");

            if ($oldImageName && file_exists("/app/uploads/categories/$oldImageName")) {
                unlink("/app/uploads/categories/$oldImageName");
            }

            return $fileName;
        }
    }

    return false;
}


function deleteCategorie(int $id): bool
{
    global $db; // permet de "connecter" avec la bdd

    try {
        $sqlStatement = $db->prepare("DELETE FROM categorie WHERE id = :id"); // prepare la requette en format sql
        $sqlStatement->execute([
            'id' => $id,
        ]); // execute la requette dans la bdd
    } catch (PDOException $error) {
        return false;
    } // sinon si y'a une erreur renvoie false - 'PDOException' est une fonction propre a PHP donc obliger de le mettre
    return true; // ou renvoie true
}
