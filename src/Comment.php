<?php

namespace ITRvB_Khoryakova;

use Faker\Core\Uuid;

class Comment
{
    public string $uuid;
    public string $authorUuid;
    public string $articleUuid;
    public string $text;

    public function __construct($uuid, $authorUuid, $articleUuid, $text)
    {
        $this->uuid = $uuid;
        $this->authorUuid = $authorUuid;
        $this->articleUuid = $articleUuid;
        $this->text = $text;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getAuthorUuid(): string
    {
        return $this->authorUuid;
    }

    public function getArticleUuid(): string
    {
        return $this->articleUuid;
    }

    public function getText(): string
    {
        return $this->text;
    }
}

?>