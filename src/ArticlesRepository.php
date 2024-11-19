<?php

namespace ITRvB_Khoryakova\lesson4;

use ITRvB_Khoryakova\lesson4\Article;
use ITRvB_Khoryakova\lesson4\ArticlesRepositoryInterface;
use PDO;

class ArticlesRepository implements ArticlesRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function get(string $uuid): Article
    {
        $sql = "SELECT * FROM posts WHERE `uuid` = '$uuid'";
        $data = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        $article = new Article();
        $article->uuid = $data['uuid'];
        $article->title = $data['title'];
        $article->text = $data['text'];
        $article->authorUuid = $data['authorUuid'];
        return $article;
    }

    public function save(Article $article): void
    {
        $sql = "INSERT INTO `posts` (`uuid`, `authorUuid`, `title`, `text`) VALUES (:uuid, :authorUuid, :title, :text)";
        $prp = $this->db->prepare($sql);
        $params = [
            'uuid' => $article->uuid,
            'authorUuid' => $article->authorUuid,
            'title' => $article->title,
            'text' => $article->text
        ];
        $prp->execute($params);
    }
}

?>