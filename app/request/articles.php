<?php

require_once '/app/conf/mysql.php';

function findAllArticles(): array    // permet de recuperer les articles en bdd
{
    global $db;

    $sqlStatement = $db->prepare("SELECT * FROM articles");
    $sqlStatement->execute();

    return $sqlStatement->fetchAll();
}

function convertDateArticle(string $date, string $format): string
{     // convertis la date dans un autre format
    return (new DateTime($date))->format($format);
}


/**
 * Undocumented function
 *
 * @param string $title
 * @return boolean|array
 */
function findOneArticleByTitle(string $title): bool|array
{
    global $db;

    $sqlStatement = $db->prepare("SELECT * FROM articles WHERE title = :title");
    $sqlStatement->execute([
        'title' => $title,
    ]);
    return $sqlStatement->fetch();
}


/**
 * Undocumented function
 *
 * @param integer $id
 * @return boolean|array
 */
function findOneArticleById(int $id): bool|array
{
    global $db;

    $sqlStatement = $db->prepare("SELECT * FROM articles WHERE id = :id");
    $sqlStatement->execute([
        'id' => $id,
    ]);
    return $sqlStatement->fetch();
}


/**
 * Undocumented function
 *
 * @param integer $id
 * @param string $title
 * @param string $description
 * @param integer $enable
 * @param string|null $imageName
 * @return boolean
 */
function updateArticle(int $id, string $title, string $description, int $enable, ?string $imageName): bool
{
    global $db;

    try {
        $query = "UPDATE articles SET title = :title, description = :description, enable = :enable";
        $params = [
            'id' => $id,
            'title' => $title,
            'description' => $description,
            'enable' => $enable,
        ];

        if($imageName) {
            $query .=", imageName = :imageName";
            $params['imageName'] = $imageName;
        }

        $query .= " WHERE id = :id";

        $sqlStatement = $db->prepare($query);
        $sqlStatement->execute($params);
    } catch (PDOException $error) {
        return false;
    }
    return true;
}


/**
 * Undocumented function
 *
 * @param string $title
 * @param string $description
 * @param integer $enable
 * @param string|null $imageName
 * @return boolean
 */
function createArticle(string $title, string $description, int $enable, ?string $imageName): bool
{
    global $db;

    try {
        $params = [
            'title' => $title,
            'description' => $description,
            'enable' => $enable,
        ];

        if ($imageName) {
            $query = "INSERT INTO articles(title, description, enable, imageName) VALUES (:title, :description, :enable, :imageName)";
            $params['imageName'] = $imageName;
        } else {
            $query = "INSERT INTO articles(title, description, enable) VALUES (:title, :description, :enable)";
        }

        $sqlStatement = $db->prepare($query);
        $sqlStatement->execute($params);
    } catch (PDOException $error) {
        return false;
    }
    return true;
}


function uploadArticleImage(array $image, ?string $oldImageName = null): bool|string // verifie la taille, les extension d'une image
{
    if ($image['size'] < 16000000) { // taille
        $fileInfo = pathinfo($image['name']);

        $extension = $fileInfo['extension']; // extension 
        $extensionAllowed = ['png', 'jpg', 'jpeg', 'webp', 'svg', 'gif'];

        if (in_array($extension, $extensionAllowed)) { // verifie l'extension
            $fileName = $fileInfo['filename'] . (new DateTime())->format('_Y-m-d_H:i:s') . '.' . $extension;

            move_uploaded_file($image['tmp_name'], "/app/uploads/articles/$fileName");

            if ($oldImageName && file_exists("/app/uploads/articles/$oldImageName")) {
                unlink("/app/uploads/articles/$oldImageName");
            }
            return $fileName;
        }
    }

    return false;
}
