<?php

use ITRvB_Khoryakova\lesson4\ArticlesRepository;
use ITRvB_Khoryakova\lesson4\CommentsRepository;
use ITRvB_Khoryakova\lesson4\Article;
use ITRvB_Khoryakova\lesson4\Comment;

require './vendor/autoload.php';

$db = new PDO('sqlite:' . __DIR__ . '/db.sqlite');

$db->exec("CREATE TABLE IF NOT EXISTS users (
    uuid TEXT PRIMARY KEY,
    firstName TEXT NOT NULL,
    lastName TEXT NOT NULL
);");

$db->exec("CREATE TABLE IF NOT EXISTS posts (
    uuid TEXT PRIMARY KEY,
    authorUuid TEXT NOT NULL,
    title TEXT NOT NULL,
    'text' TEXT NOT NULL,
    FOREIGN KEY (authorUuid) REFERENCES users(uuid)
);");

$db->exec("CREATE TABLE IF NOT EXISTS comments (
    uuid TEXT PRIMARY KEY,
    authorUuid TEXT NOT NULL,
    postUuid TEXT NOT NULL,
    'text' TEXT NOT NULL,
    FOREIGN KEY (authorUuid) REFERENCES users(uuid),
    FOREIGN KEY (postUuid) REFERENCES posts(uuid)
);");

$faker = Faker\Factory::create();

$articlesRepository = new ArticlesRepository($db);
$commentsRepository = new CommentsRepository($db);

$articleUuid = $faker->uuid();
$authorUuid = $faker->uuid();
$commentUuid = $faker->uuid();

$article = new Article();
$article->uuid = $articleUuid;
$article->authorUuid = $authorUuid;
$article->title = 'Заголовок';
$article->text = 'Текст';

$articlesRepository->save($article);
$articleDb = $articlesRepository->get($articleUuid);

$comment = new Comment();
$comment->uuid = $commentUuid;
$comment->authorUuid = $authorUuid;
$comment->articleUuid = $articleUuid;
$comment->text = 'Текст';

$commentsRepository->save($comment);
$commentDb = $commentsRepository->get($commentUuid);
?>