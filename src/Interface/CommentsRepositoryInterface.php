<?php 

namespace ITRvB_Khoryakova\Interface;

use Faker\Core\Uuid;
use ITRvB_Khoryakova\Comment;

interface CommentsRepositoryInterface {
    public function get(string $uuid): Comment;
    public function save(Comment $comment): void;
}
?>