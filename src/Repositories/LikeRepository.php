<?php

namespace ITRvB_Khoryakova\Repositories;

use ITRvB_Khoryakova\Like;
use ITRvB_Khoryakova\Interface\LikeRepositoryInterface;
use Faker\Core\Uuid;
use PDO;

class LikeRepository implements LikeRepositoryInterface{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function getByPostUuid (string $postuuid): array
    {
        $sql = "SELECT * FROM likes WHERE `postUuid` = '$postuuid'";
        $data = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new \Exception('Нет лайков для статьи');
        }
        $likes = [];
        foreach($data as $d) {
            $likes[] = new Like($d['uuid'], $d['postUuid'], $d['userUuid']);
        }
        return $likes;
    }

    public function save(Like $like): void
    {
        $sql = "INSERT INTO posts (uuid, postUuid, userUuid) VALUES (:uuid, :postUuid, :userUuid)";
        $prp = $this->db->prepare($sql);
        $params = [
            'uuid' => $like->uuid,
            'postUuid' => $like->postUuid,
            'userUuid' => $like->userUuid
        ];
        $prp->execute($params);
    }
}
?>