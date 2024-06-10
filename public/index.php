<?php

declare(strict_types=1);

require_once('vendor/autoload.php');

use App\Logger\{Logger, FileNotFoundException};
use App\Strings\{DateConvertor, InvalidInputException};

$from_format = 'd.m.Y';
$to_format = 'Y-m-d';
$date = '02.04.2004';

try {
    $logger = new Logger('default');
}catch (FileNotFoundException $e){
    echo $e->getMessage();
}

if (isset($logger)){
    try {
        $newDate = DateConvertor::convertDateFormat($date, $from_format, $to_format);

        $logger->info(sprintf('Get: [%s] from [%s]', $newDate, $date));
    }catch (InvalidInputException $e){
        $logger->error($e->getMessage());
    }

}
