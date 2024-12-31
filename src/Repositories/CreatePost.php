<?php 

namespace ITRvB_Khoryakova\Repositories;

use ITRvB_Khoryakova\Article;
use ITRvB_Khoryakova\Repositories\ArticlesRepository;
use ITRvB_Khoryakova\Interface\LoggerInterface;
use PDO;

class CreatePost {
    private PDO $db;
    private ArticlesRepository $repository;
    private LoggerInterface $logger;

    public function __construct(PDO $db, ArticlesRepository $repository, LoggerInterface $logger)
    {
        $this->db = $db;
        $this->repository = $repository;
        $this->logger = $logger;
    }

    public function execute($data) {
        if (!isset($data['uuid'], $data['title'], $data['content'], $data['author_uuid'])) {
            $this->logger->warning("Not all required fields for creating an article");
            return ['status' => 'error', 'message' => 'Не все поля заполнены'];
        }

        if (!preg_match('/^[a-f0-9\-]{36}$/', $data['uuid']) || !preg_match('/^[a-f0-9\-]{36}$/', $data['author_uuid'])) {
            $this->logger->warning("Incorrect UUID format for saving the article");
            return ['status' => 'error', 'message' => 'Неверный формат UUID'];
        }

        $stmt = $this->db->prepare('SELECT COUNT(*) FROM users WHERE uuid = :uuid');
        $stmt->execute(['uuid' => $data['author_uuid']]);
        if ($stmt->fetchColumn() == 0) {
            $this->logger->warning("User not found for saving the article");
            return ['status' => 'error', 'message' => 'Пользователь не найден'];
        }

        $article = new Article($data['uuid'], $data['author_uuid'], $data['title'], $data['content']);
        $this->repository->save($article);

        $this->logger->info("Article saved: " . $article->getUuid());
        return ['status' => 'success', 'message' => 'Статья добавлена'];
    }
}

?>