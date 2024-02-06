<?php

require_once '/app/conf/mysql.php';

function findAllArticles(): array    // permet de recuperer les articles en bdd
{
    global $db;

    $sqlStatement = $db->prepare("SELECT * FROM articles");
    $sqlStatement->execute();

    return $sqlStatement->fetchAll();
}

function convertDateArticle(string $date, string $format): string {     // convertis la date dans un autre format
    return (new DateTime($date))->format($format);
}

/**
 * Undocumented function
 *
 * @param string $title
 * @return boolean|array
 */
function findOneArticleBYTitle(string $title): bool|array
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
 * @param string $title
 * @param string $description
 * @param integer $enable
 * @return boolean
 */
function createArticle(string $title, string $description, int $enable): bool {
    global $db;

    try {
        $sqlStatement = $db->prepare("INSERT INTO articles(title, description, enable) VALUES (:title, :description, :enable)");
        $sqlStatement->execute([
            'title' => $title,
            'description' => $description,
            'enable' => $enable,
        ]);
    } catch(PDOException $error) {
        return false;
    }
    return true;
}

?>