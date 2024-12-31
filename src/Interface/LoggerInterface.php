<?php

namespace ITRvB_Khoryakova\Interface;

interface LoggerInterface
{
    public function info(string $message): void;
    public function warning(string $message): void;
}

?>