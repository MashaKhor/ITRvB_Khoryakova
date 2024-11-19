<?php 

namespace ITRvB_Khoryakova\lesson4;

use Faker\Core\Uuid;
use ITRvB_Khoryakova\lesson4\Comment;

interface CommentsRepositoryInterface {
    public function get(string $uuid): Comment;
    public function save(Comment $comment): void;
}
?>