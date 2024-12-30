<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use ITRvB_Khoryakova\Repositories\ArticlesRepository;
use ITRvB_Khoryakova\Article;
use ITRvB_Khoryakova\Repositories\CreatePost;
use Faker\Factory as Faker;
use PDO;

class CreatePostTest extends TestCase {
    public PDO $db;
    private CreatePost $createPost;
    private ArticlesRepository $repository;

    protected function setUp(): void {
        $this->db = new PDO('sqlite:db.sqlite');
        $this->repository = new ArticlesRepository($this->db);
        $this->createPost = new CreatePost($this->db, $this->repository);
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
    }
}

?>