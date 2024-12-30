<?php

namespace ITRvB_Khoryakova;

use Faker\Core\Uuid;

class Like {
    public string $uuid;
    public string $postuuid;
    public string $useruuid;

    public function __construct($uuid, $postuuid, $useruuid)
    {
        $this->uuid = $uuid;
        $this->postuuid = $postuuid;
        $this->useruuid = $useruuid;
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }

    public function getPostUuid(): string
    {
        return $this->postuuid;
    }

    public function getuserUuid(): string
    {
        return $this->useruuid;
    }
}

?>