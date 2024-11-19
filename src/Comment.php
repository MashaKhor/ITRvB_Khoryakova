<?php

namespace ITRvB_Khoryakova\lesson4;

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
}

?>