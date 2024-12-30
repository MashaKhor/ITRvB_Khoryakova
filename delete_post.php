<?php
require_once __DIR__ . '/vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    $db = new PDO('sqlite:db.sqlite');
    
    parse_str($_SERVER['QUERY_STRING'], $queryParams);
    $postUuid = $queryParams['uuid'] ?? null;

    if (!$postUuid) {
        http_response_code(400);
        echo json_encode(["error" => "Не передан UUID"]);
        exit;
    }

    if (!preg_match('/^[a-f0-9\-]{36}$/', $postUuid)) {
        http_response_code(400);
        echo json_encode(["error" => "Неверный формат UUID"]);
        exit;
    }

    try {
        $stmt = $db->prepare('DELETE FROM posts WHERE uuid = :uuid');
        $stmt->execute(['uuid' => $postUuid]);

        if ($stmt->rowCount() > 0) {
            http_response_code(200);
            echo json_encode(["message" => "Статья удалена"]);
        } else {
            http_response_code(404);
            echo json_encode(["error" => "Не найдена статья для удаления"]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["error" => $e->getMessage()]);
    }
} else {
    http_response_code(405);
    echo json_encode(["error" => "Метод не найден"]);
}


?>