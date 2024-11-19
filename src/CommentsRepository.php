<?php 

namespace ITRvB_Khoryakova\lesson4;

use ITRvB_Khoryakova\lesson4\Comment;
use ITRvB_Khoryakova\lesson4\CommentsRepositoryInterface;

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
        $comment = new Comment();
        $comment->uuid = $data['uuid'];
        $comment->authorUuid = $data['authorUuid'];
        $comment->text = $data['text'];
        $comment->articleUuid = $data['postUuid'];
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