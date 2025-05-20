<?php

class Logger {

    private static string $logFile = __DIR__ . '/../logs/logs.txt';


    public static function log(string $message): void {
        $logDir = dirname(self::$logFile);

        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);  // create directory recursively if not exists
        }

        $date = date('Y-m-d H:i:s');
        $entry = "[$date] $message" . PHP_EOL;
        file_put_contents(self::$logFile, $entry, FILE_APPEND);
    }

}
