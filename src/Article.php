<?php

namespace ITRvB_Khoryakova;

use Faker\Core\Uuid;

class Article
{
    public string $uuid;
    public string $authorUuid;
    public string $title;
    public string $content;

    public function __construct($uuid, $authorUuid, $title, $content)
    {
        $this->uuid = $uuid;
        $this->authorUuid = $authorUuid;
        $this->title = $title;
        $this->content = $content;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getAuthorUuid(): string
    {
        return $this->authorUuid;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getContent(): string
    {
        return $this->content;
    }
}

?>