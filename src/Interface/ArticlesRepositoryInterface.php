<?php 

namespace ITRvB_Khoryakova\Interface;

use Faker\Core\Uuid;
use ITRvB_Khoryakova\Article;

interface ArticlesRepositoryInterface
{
    public function get(string $uuid): Article;
    public function save(Article $post): void;
}

?>