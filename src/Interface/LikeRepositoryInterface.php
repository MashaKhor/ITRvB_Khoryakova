<?php 

namespace ITRvB_Khoryakova\Interface;

use Faker\Core\Uuid;
use ITRvB_Khoryakova\Like;

interface LikeRepositoryInterface
{
    public function getByPostUuid(string $uuid): array;
    public function save(Like $like): void;
}

?>