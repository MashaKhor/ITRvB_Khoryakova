<?php

namespace ITRvB_Khoryakova\Repositories;

use ITRvB_Khoryakova\Interface\LoggerInterface;

class FileLogger implements LoggerInterface {
    private string $logFile;

    public function __construct(string $logFile)
    {
        $this->logFile = $logFile;
    }

    public function info(string $message): void
    {
        $this->log("INFO: " . $message);
    }

    public function warning(string $message): void
    {
        $this->log("WARNING: " . $message);
    }

    private function log(string $message): void
    {
        file_put_contents($this->logFile, date('Y-m-d H:i:s') . ' ' . $message . PHP_EOL, FILE_APPEND);
    }
}

?>