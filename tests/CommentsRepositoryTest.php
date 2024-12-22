<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use ITRvB_Khoryakova\Repositories\CommentsRepository;
use ITRvB_Khoryakova\Comment;
use Faker\Factory as Faker;
use PDO;

class CommentsRepositoryTest extends TestCase {
    public PDO $db;
    private CommentsRepository $repository;

    protected function setUp(): void
    {
        $this->db = new PDO('sqlite:db.sqlite');
        $this->db->exec('DELETE FROM comments WHERE uuid = "test_uuid"');
        $this->repository = new CommentsRepository($this->db);
    }

    public function testFindComment() : void {
        $faker = Faker::create();
        $uuid = $faker->uuid;
        $authorUuid = $faker->uuid;
        $articleUuid = $faker->uuid;

        $this->db->exec(
            "INSERT INTO comments (uuid, authorUuid, postUuid, text) 
             VALUES ('$uuid', '$authorUuid', '$articleUuid', 'Тестовый текст')"
        );

        $comment = $this->repository->get($uuid);

        $this->assertEquals($uuid, $comment->getUuid());
        $this->assertEquals($authorUuid, $comment->getAuthorUuid());
        $this->assertEquals($articleUuid, $comment->getArticleUuid());
        $this->assertEquals('Тестовый текст', $comment->getText());
    }

    public function testExceptionFindComment() : void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Комментарий не найден.');
        
        $this->repository->get('uuid-not');
    }

    public function testSaveArticle() : void {
        $faker = Faker::create();
        $uuid = $faker->uuid;
        $authorUuid = $faker->uuid;
        $articleUuid = $faker->uuid;

        $comment = new Comment($uuid, $authorUuid, $articleUuid, 'Тестовый текст');
        $this->repository->save($comment);

        $result = $this->db->query("SELECT * FROM comments WHERE uuid = '$uuid'")->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($result);
        $this->assertSame($comment->uuid, $result['uuid']);
        $this->assertSame($comment->articleUuid, $result['postUuid']);
        $this->assertSame($comment->text, $result['text']);
        $this->assertSame($comment->authorUuid, $result['authorUuid']);
    }
}

?>