<?php

declare(strict_types=1);

namespace App\Logger;

use Psr\Log\AbstractLogger;

class Logger extends AbstractLogger
{
    private const INFO = 'info';
    private const WARNING = 'warning';
    private const DEBUG = 'debug';
    private const ERROR = 'error';

    /**
     * @param String $logPath is a file path. If you want to use default path 'default.log' send 'default' string
     * @throws FileNotFoundException in case when file not found
     */
    public function __construct(protected string $logPath)
    {
        if ($logPath === 'default') {
            $this->logPath = $logPath = 'default.log';

            $file = fopen($logPath, 'ab+');
            fclose($file);
        }

        if (!file_exists($logPath)) {
            $message = sprintf("Log file %s not found<br>", $logPath);
            throw new FileNotFoundException($message);
        }

    }

    public function log($level, $message, array $context = []): void
    {
        $formattedMessage = $this->formatMessage($message, $level, $context);

        if (!empty($this->logPath) && file_exists($this->logPath)) {
            $this->writeToFile($formattedMessage);
        } else {
            $newMessage = sprintf('File with path [%s] was not found', $this->logPath);
            $newMessage = $this->formatMessage($newMessage, self::ERROR, []);

            $this->outputToConsole($newMessage);
        }

        $this->outputToConsole($formattedMessage);
    }

    public function info($message, array $context = []): void
    {
        $this->log(self::INFO, $message);
    }

    public function warning($message, array $context = []): void
    {
        $this->log(self::WARNING, $message);
    }

    public function error($message, array $context = []): void
    {
        $this->log(self::ERROR, $message);
    }

    public function debug($message, array $context = []): void
    {
        $this->log(self::DEBUG, $message);
    }

    protected function formatMessage($message, $logLevel, array $context): string
    {
        foreach ($context as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }

        return sprintf('[%s][%s]:[%s]', date('d/m/y H:i:s'), $logLevel, $message);
    }

    protected function writeToFile(string $message): void
    {
        file_put_contents($this->logPath, $message . PHP_EOL, FILE_APPEND);
    }

    protected function outputToConsole(string $message): void
    {
        echo sprintf("%s<br>", $message);
    }
}