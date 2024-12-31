<?php

namespace ITRvB_Khoryakova\Repositories;

use ITRvB_Khoryakova\Like;
use ITRvB_Khoryakova\Interface\LikeRepositoryInterface;
use Faker\Core\Uuid;
use ITRvB_Khoryakova\Interface\LoggerInterface;
use PDO;

class LikeRepository implements LikeRepositoryInterface{
    private PDO $db;
    private LoggerInterface $logger;

    public function __construct(PDO $db, LoggerInterface $logger)
    {
        $this->db = $db;
        $this->logger = $logger;
    }

    public function getByPostUuid (string $postuuid): array
    {
        $sql = "SELECT * FROM likes WHERE `postUuid` = '$postuuid'";
        $data = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            $this->logger->warning("Not found likes for post: $uuid");
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
        $this->logger->info("Like saved: " . $like->getUuid());
    }
}
?>