<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use ITRvB_Khoryakova\Repositories\ArticlesRepository;
use ITRvB_Khoryakova\Article;
use ITRvB_Khoryakova\Repositories\CreatePost;
use Faker\Factory as Faker;
use ITRvB_Khoryakova\Repositories\TestLogger;
use PDO;

class CreatePostTest extends TestCase {
    public PDO $db;
    private CreatePost $createPost;
    private ArticlesRepository $repository;
    private TestLogger $logger;

    protected function setUp(): void {
        $this->db = new PDO('sqlite:db.sqlite');
        $this->logger = new TestLogger();
        $this->repository = new ArticlesRepository($this->db, $this->logger);
        $this->createPost = new CreatePost($this->db, $this->repository, $this->logger);
    }

    public function testSuccessfulResponse() : void {
        $faker = Faker::create();
        $uuid = $faker->uuid;
        $authorUuid = $faker->uuid;

        $sql = "INSERT INTO users (uuid, firstName, lastName) VALUES (:uuid, :firstName, :lastName)";
        $prp = $this->db->prepare($sql);
        $params = [
            'uuid' => $authorUuid,
            'firstName' => 'Тест',
            'lastName' => 'Тестович'
        ];
        $prp->execute($params);

        $data = [
            'uuid' => $uuid,
            'title' => 'Тестовый заголовок',
            'content' => 'Тестовый контент',
            'author_uuid' => $authorUuid
        ];

        $result = $this->createPost->execute($data);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals('Статья добавлена', $result['message']);

        $logs = $this->logger->getLogs();
        $this->assertCount(2, $logs);
        $this->assertEquals('INFO', $logs[0]['level']);
        $this->assertStringContainsString("Article saved: " . $uuid, $logs[0]['message']);
    }

    public function testInvalidUUIDFormat(): void {
        $faker = Faker::create();
        $uuid = $faker->uuid;
        $authorUuid = $faker->uuid;

        $sql = "INSERT INTO users (uuid, firstName, lastName) VALUES (:uuid, :firstName, :lastName)";
        $prp = $this->db->prepare($sql);
        $params = [
            'uuid' => $authorUuid,
            'firstName' => 'Тест',
            'lastName' => 'Тестович'
        ];
        $prp->execute($params);


        $data = [
            'uuid' => 'invalid-uuid',
            'title' => 'Тестовый заголовок',
            'content' => 'Тестовый контент',
            'author_uuid' => $authorUuid
        ];

        $result = $this->createPost->execute($data);

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('Неверный формат UUID', $result['message']);

        $logs = $this->logger->getLogs();
        $this->assertCount(1, $logs);
        $this->assertEquals('WARNING', $logs[0]['level']);
        $this->assertStringContainsString('Incorrect UUID format for saving the article', $logs[0]['message']);
    }

    public function testAuthorNotFound(): void {
        $faker = Faker::create();
        $uuid = $faker->uuid;
        $authorUuid = $faker->uuid;
        
        $data = [
            'uuid' => $uuid,
            'title' => 'Тестовый заголовок',
            'content' => 'Тестовый контент',
            'author_uuid' => $authorUuid
        ];

        $result = $this->createPost->execute($data);

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('Пользователь не найден', $result['message']);

        $logs = $this->logger->getLogs();
        $this->assertCount(1, $logs);
        $this->assertEquals('WARNING', $logs[0]['level']);
        $this->assertStringContainsString('User not found for saving the article', $logs[0]['message']);
    }

    public function testMissingRequiredFields(): void {
        $faker = Faker::create();
        $uuid = $faker->uuid;

        $data = [
            'uuid' => $uuid,
            'title' => 'Тестовый заголовок'
        ];

        $result = $this->createPost->execute($data);

        $this->assertEquals('error', $result['status']);
        $this->assertEquals('Не все поля заполнены', $result['message']);

        $logs = $this->logger->getLogs();
        $this->assertCount(1, $logs);
        $this->assertEquals('WARNING', $logs[0]['level']);
        $this->assertStringContainsString('Not all required fields for creating an article', $logs[0]['message']);
    }
}

?>