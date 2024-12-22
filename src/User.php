<?php

namespace ITRvB_Khoryakova;

use Faker\Core\Uuid;

class User
{
    public string $uuid;
    public string $firstName;
    public string $lastName;

    public function __construct($uuid, $firstName, $lastName)
    {
        $this->uuid = $uuid;
        $this->firstName = $firstName;
        $this->lastName = $lastName;
    }
}

?>