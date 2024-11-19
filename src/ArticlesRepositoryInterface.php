<?php 

namespace ITRvB_Khoryakova\lesson4;

use Faker\Core\Uuid;
use ITRvB_Khoryakova\lesson4\Article;

interface ArticlesRepositoryInterface
{
    public function get(string $uuid): Article;
    public function save(Article $post): void;
}

?>