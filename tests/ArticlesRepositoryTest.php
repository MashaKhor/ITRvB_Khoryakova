<?php

namespace tests;

use PHPUnit\Framework\TestCase;
use ITRvB_Khoryakova\Repositories\ArticlesRepository;
use ITRvB_Khoryakova\Article;
use Faker\Factory as Faker;
use PDO;

class ArticlesRepositoryTest extends TestCase {
    public PDO $db;
    private ArticlesRepository $repository;

    protected function setUp(): void
    {
        $this->db = new PDO('sqlite:db.sqlite');
        $this->db->exec('DELETE FROM posts WHERE uuid = "test_uuid"');
        $this->repository = new ArticlesRepository($this->db);
    }

    public function testFindPost() : void {
        $faker = Faker::create();
        $uuid = $faker->uuid;
        $authorUuid = $faker->uuid;

        $this->db->exec(
            "INSERT INTO posts (uuid, authorUuid, title, text) 
             VALUES ('$uuid', '$authorUuid', 'Тестовое название', 'Тестовый текст')"
        );

        $post = $this->repository->get($uuid);

        $this->assertEquals($uuid, $post->getUuid());
        $this->assertEquals($authorUuid, $post->getAuthorUuid());
        $this->assertEquals('Тестовое название', $post->getTitle());
        $this->assertEquals('Тестовый текст', $post->getContent());
    }

    public function testExceptionFindPost() : void {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('Статья не найдена.');
        
        $this->repository->get('uuid-not');
    }

    public function testSaveArticle() : void {
        $faker = Faker::create();
        $uuid = $faker->uuid;
        $authorUuid = $faker->uuid;

        $article = new Article($uuid, $authorUuid, 'Тестовое название', 'Тестовый текст');
        $this->repository->save($article);

        $result = $this->db->query("SELECT * FROM posts WHERE uuid = '$uuid'")->fetch(PDO::FETCH_ASSOC);

        $this->assertNotEmpty($result);
        $this->assertSame($article->uuid, $result['uuid']);
        $this->assertSame($article->title, $result['title']);
        $this->assertSame($article->content, $result['text']);
        $this->assertSame($article->authorUuid, $result['authorUuid']);
    }
}

?>