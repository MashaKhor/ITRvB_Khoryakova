<?php 

namespace ITRvB_Khoryakova\Repositories;

use ITRvB_Khoryakova\Comment;
use ITRvB_Khoryakova\Interface\CommentsRepositoryInterface;
use ITRvB_Khoryakova\Interface\LoggerInterface;

use PDO;

class CommentsRepository implements CommentsRepositoryInterface
{
    private PDO $db;
    private LoggerInterface $logger;

    public function __construct(PDO $db, LoggerInterface $logger)
    {
        $this->db = $db;
        $this->logger = $logger;
    }

    public function get(string $uuid): Comment
    {
        $sql = "SELECT * FROM comments WHERE `uuid` = '$uuid'";
        $data = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            $this->logger->warning("Comment not found: $uuid");
            throw new \Exception('Комментарий не найден.');
        }
        $comment = new Comment($data['uuid'], $data['authorUuid'], $data['postUuid'], $data['text']);
        $this->logger->info("Comment found: $uuid");
        return $comment;
    }

    public function save(Comment $comment): void
    {
        $sql = "INSERT INTO `comments` (`uuid`, `authorUuid`, `postUuid`, `text`) VALUES (:uuid, :authorUuid, :postUuid, :text)";
        $prp = $this->db->prepare($sql);
        $params = [
            'uuid' => $comment->uuid,
            'authorUuid' => $comment->authorUuid,
            'postUuid' => $comment->articleUuid,
            'text' => $comment->text
        ];
        $prp->execute($params);

        $this->logger->info("Comment saved: " . $comment->getUuid());
    }
}

?>