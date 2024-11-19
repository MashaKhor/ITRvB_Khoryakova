<?php

namespace ITRvB_Khoryakova\lesson4;

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
}

?>