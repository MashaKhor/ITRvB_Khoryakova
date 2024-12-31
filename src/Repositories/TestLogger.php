<?php

namespace ITRvB_Khoryakova\Repositories;

use ITRvB_Khoryakova\Interface\LoggerInterface;

class TestLogger implements LoggerInterface {
    private array $logs = [];

    public function info(string $message): void
    {
        $this->logs[] = ['level' => 'INFO', 'message' => $message];
    }

    public function warning(string $message): void
    {
        $this->logs[] = ['level' => 'WARNING', 'message' => $message];
    }

    public function getLogs(): array
    {
        return $this->logs;
    }
}

?>