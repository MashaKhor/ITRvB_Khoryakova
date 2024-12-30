<?php

use ITRvB_Khoryakova\Repositories\LikeRepository;
use Faker\Core\Uuid;

require_once __DIR__ . '/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = new PDO('sqlite:db.sqlite');
    $repository = new LikeRepository($db);

    $postUuid = $_POST['postUuid'] ?? null;
    $userUuid = $_POST['userUuid'] ?? null;

    if (!$postUuid || !$userUuid) {
        http_response_code(400);
        echo json_encode(['error' => 'Не все поля есть']);
        exit;
    }

    $existingLikes = $repository->getByPostUuid($postUuid);
    $isAlreadyLiked = false;
    foreach ($existingLikes as $like) {
        if ($like->userUuid === $userUuid) {
            $isAlreadyLiked = true;
            break;
        }
    }

    if ($isAlreadyLiked) {
        http_response_code(409);
        echo json_encode(['error' => 'Пользователь уже поставил лайк этой статье']);
        exit;
    }

    $faker = Faker::create();
    $uuid = $faker->uuid;

    $newLike = new Like($uuid, $postUuid, $userUuid);
    try {
        $repository->save($newLike);

        http_response_code(201);
        echo json_encode(['message' => 'Лайк поставлен']);
    } catch (Exception $e){
        http_response_code(500);
        echo json_encode(['error' => 'Ошибка']);
    }
}