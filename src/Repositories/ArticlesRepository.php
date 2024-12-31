<?php

namespace ITRvB_Khoryakova\Repositories;

use ITRvB_Khoryakova\Article;
use ITRvB_Khoryakova\Interface\ArticlesRepositoryInterface;
use Faker\Core\Uuid;
use ITRvB_Khoryakova\Interface\LoggerInterface;
use PDO;

class ArticlesRepository implements ArticlesRepositoryInterface
{
    private PDO $db;
    private LoggerInterface $logger;

    public function __construct(PDO $db, LoggerInterface $logger)
    {
        $this->db = $db;
        $this->logger = $logger;
    }

    public function get(string $uuid): Article
    {
        $sql = "SELECT * FROM posts WHERE `uuid` = '$uuid'";
        $data = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            $this->logger->warning("Article not found: $uuid");
            throw new \Exception('Статья не найдена.');
        }
        $article = new Article($data['uuid'], $data['authorUuid'], $data['title'], $data['text']);
        $this->logger->info("Article found: $uuid");
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

        $this->logger->info("Article saved: " . $article->getUuid());
    }
}

?>