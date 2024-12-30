<?php 

use ITRvB_Khoryakova\Repositories\CommentsRepository;
use ITRvB_Khoryakova\Controllers\CommentController;

require_once __DIR__ . '/vendor/autoload.php';

$requestUri = $_SERVER['REQUEST_URI'];
$requestMethod = $_SERVER['REQUEST_METHOD'];

if ($requestUri === '/posts/comment' && $requestMethod === 'POST') {
    $db = new PDO('sqlite:db.sqlite');
    $controller = new CommentController($db);

    $controller->createComment();
} else if ($requestUri === '/posts' && $requestMethod === 'DELETE') {
    include 'delete_post.php';
    exit;
}
else {
    http_response_code(404);
    echo json_encode(['message' => 'Страница не найдена']);
}
?>