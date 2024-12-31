<?php 
namespace tests;

use PHPUnit\Framework\TestCase;
use ITRvB_Khoryakova\Repositories\CommentsRepository;
use ITRvB_Khoryakova\Controllers\CommentController;
use ITRvB_Khoryakova\Comment;
use Faker\Factory as Faker;
use ITRvB_Khoryakova\Repositories\TestLogger;
use PDO;

class CommentControllerTest extends TestCase {
    public PDO $db;
    public CommentController $controller;
    private TestLogger $logger;

    protected function setUp(): void
    {
        $this->db = new PDO('sqlite:db.sqlite');
        $this->db->exec('DELETE FROM comments WHERE uuid = "test_uuid"');
        $this->logger = new TestLogger();
        $this->controller = new CommentController($this->db, $this->logger);
    }

    public function testCreateCommentSuccess(): void
    {
        $inputData = json_encode([
            'author_uuid' => 'author-uuid-test',
            'post_uuid' => 'success-article-test-222',
            'text' => 'Тестовый текст комментария'
        ]);

        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_SERVER['REQUEST_URI'] = '/posts/comment';
        $_SERVER['CONTENT_TYPE'] = 'application/json';
        file_put_contents('php://input', $inputData);

        ob_start();
        $this->controller->createComment();
        $output = ob_get_clean();

        $response = json_decode($output, true);
        $this->assertEquals(201, http_response_code());
        $this->assertEquals('Комментарий добавлен.', $response['message']);
    }
}

?>