<?php 

namespace ITRvB_Khoryakova\Repositories;

use ITRvB_Khoryakova\Comment;
use ITRvB_Khoryakova\Interface\CommentsRepositoryInterface;

use PDO;

class CommentsRepository implements CommentsRepositoryInterface
{
    private PDO $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }

    public function get(string $uuid): Comment
    {
        $sql = "SELECT * FROM comments WHERE `uuid` = '$uuid'";
        $data = $this->db->query($sql)->fetch(PDO::FETCH_ASSOC);
        if (!$data) {
            throw new \Exception('Комментарий не найден.');
        }
        $comment = new Comment($data['uuid'], $data['authorUuid'], $data['postUuid'], $data['text']);
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
    }
}

?>