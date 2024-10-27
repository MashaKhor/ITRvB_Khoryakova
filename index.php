<?php 
    require 'autoload.php';
    require 'vendor/autoload.php';

    use User as User;
    use Comment as Comment;
    use Article as Article;
    use Faker\Factory as Faker;

    $faker = Faker::create();

    $user = new User($faker->randomNumber(), $faker->firstName, $faker->lastName);
    echo "User: {$user->id}, {$user->firstName}, {$user->lastName}<br>";

    $article = new Article($faker->randomNumber(), $user->id, $faker->title, $faker->text);
    echo "Article: {$article->id}, Author: {$article->authorId}, Title: {$article->title} Content: {$article->content}<br>";

    $comment = new Comment($faker->randomNumber(), $user->id, $article->id, $faker->text);
    echo "Comment: {$comment->id}, Author: {$comment->authorId}, Article: {$comment->articleId}, Text: {$comment->text}";
?>