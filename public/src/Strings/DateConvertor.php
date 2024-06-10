<?php

declare(strict_types=1);

namespace App\Strings;

class DateConvertor
{
    /**
     * @throws InvalidInputException
     */
    public static function convertDateFormat($date, $from_format, $to_format): bool|string
    {
        $timestamp = strtotime(\DateTime::createFromFormat($from_format, $date)->format('Y-m-d'));

        if ($timestamp === false) {
            throw new InvalidInputException(sprintf('%s was incorrect date format with %s date',
                $from_format, $date));
        }

        return date($to_format, $timestamp);
    }

}