<?php

declare(strict_types=1);

namespace App\Logger;

class LoggerAnalyser
{

    /**
     * @throws FileNotFoundException in case when there is no file to analyse
     */
    public function __construct(protected string $logPath, protected string $pattern)
    {
        if (!file_exists($logPath)) {
            $message = sprintf("Log file %s not found<br>", $logPath);
            throw new FileNotFoundException($message);
        }
    }

    public function getLogsFromFile(): array
    {
        $messages = array();

        if (file_exists($this->logPath)) {
            $file = fopen($this->logPath, "r");
            while (!feof($file)) {
                $line = fgets($file);
                if (is_string($line) && preg_match($this->pattern, $line) !== false) {
                    $messages[] = $line;
                }

            }
        }

        return $messages;
    }

    public function filterByDate(string $from, string $to, array $logArray): array
    {
        $newMessages = array();

        $fromDate = \DateTime::createFromFormat('d/m/y H:i:s', $from);
        $toDate = \DateTime::createFromFormat('d/m/y H:i:s', $to);

        foreach ($logArray as $log) {
            if (preg_match($this->pattern, $log, $match)) {
                $date = trim($match[1], '[]');
                $dateTime = \DateTime::createFromFormat('d/m/y H:i:s', $date);

                if ($dateTime >= $fromDate && $dateTime <= $toDate) {
                    $newMessages[] = $log;
                }
            }
        }

        return $newMessages;
    }

    public function filteredByLevel(string $levelRegEx, array $logArray): array
    {
        $newMessages = array();

        foreach ($logArray as $log) {
            preg_match($this->pattern, $log, $match);
            $logLevel = $match[2];

            if (preg_match($levelRegEx, $logLevel)) {
                $newMessages[] = $log;
            }
        }

        return $newMessages;
    }

}