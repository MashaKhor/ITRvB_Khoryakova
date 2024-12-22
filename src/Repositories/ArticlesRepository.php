<?php

namespace ITRvB_Khoryakova\Repositories;

use ITRvB_Khoryakova\Article;
use ITRvB_Khoryakova\Interface\ArticlesRepositoryInterface;
use Faker\Core\Uuid;
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
        if (!$data) {
            throw new \Exception('Статья не найдена.');
        }
        $article = new Article($data['uuid'], $data['authorUuid'], $data['title'], $data['text']);
        return $article;
    }

    public function save(Article $article): void
    {
        $sql = "INSERT INTO posts (uuid, authorUuid, title, text) VALUES (:uuid, :authorUuid, :title, :text)";
        $prp = $this->db->prepare($sql);
        $params = [
            'uuid' => $article->uuid,
            'authorUuid' => $article->authorUuid,
            'title' => $article->title,
            'text' => $article->content
        ];
        $prp->execute($params);
    }
}

?>