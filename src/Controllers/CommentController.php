<?php 

namespace ITRvB_Khoryakova\Controllers;

use PDO;
use ITRvB_Khoryakova\Repositories\CommentsRepository;
use Faker\Factory as Faker;

class CommentController {
    private CommentsRepository $repository;
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
        $this->repository = new CommentsRepository($this->db);
    }

    public function createComment(): void
    {
        $inputData = json_decode(file_get_contents('php://input'), true);
        error_log(print_r($inputData, true));
        if (empty($inputData['author_uuid']) || empty($inputData['post_uuid']) || empty($inputData['text'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Недостаточно данных для добавления комментария.']);
            return;
        }

        $faker = Faker::create();
        $uuid = $faker->uuid;
        $authorUuid = $inputData['author_uuid'];
        $postUuid = $inputData['post_uuid'];
        $text = $inputData['text'];

        $comment = new Comment($uuid, $authorUuid, $postUuid, $text);

        try {
            $this->repository->save($comment);

            http_response_code(201);
            echo json_encode(['message' => 'Комментарий добавлен.']);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Не удалось добавить комментарий.']);
        }
    }
}

?>