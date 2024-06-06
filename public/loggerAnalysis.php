<?php

use App\Logger\{FileNotFoundException, LoggerAnalyser};

function printLogs(array $logs): void
{
    foreach ($logs as $log) {
        echo sprintf('%s<br>', $log);
    }
}

$pattern = '/(\[.+\])(\[.+\]:)(\[.+\])/';
$from = '20/04/24 00:00:00';
$to = '20/04/24 23:59:59';

try {
    $loggerAnalyser = new LoggerAnalyser('default.log', $pattern);
} catch (FileNotFoundException $e) {
    echo sprintf('%s<br>', $e->getMessage());
}

if (isset($loggerAnalyser)) {
    $messages = $loggerAnalyser->getLogsFromFile();
    $filteredMessages = $loggerAnalyser->filterByDate($from, $to, $messages);

    echo 'Filtered by date:<br>';
    printLogs($filteredMessages);

    $filteredLevelMessages = $loggerAnalyser->filteredByLevel('/error/', $filteredMessages);

    echo 'Filtered by date and level [error]:<br>';
    printLogs($filteredLevelMessages);
}
