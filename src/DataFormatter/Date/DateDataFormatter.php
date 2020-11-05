<?php

declare(strict_types=1);

namespace App\DataFormatter\Date;

class DateDataFormatter
{
    private const DEFAULT_DATE_FORMAT = 'Y-m-d';

    public static function getUpcomingDayDatesFormatted(int $daysCount, string $format = self::DEFAULT_DATE_FORMAT): array
    {
        $formattedDates = [];
        $date = new \DateTime();

        while ($daysCount > 0) {
            $formattedDates[] = self::getFormattedDate($date, $format);
            $date->modify('+1 day');
            $daysCount--;
        }

        return $formattedDates;
    }

    public static function getFormattedDate(\DateTimeInterface $dateTime, string $format = self::DEFAULT_DATE_FORMAT): string
    {
        return $dateTime->format($format);
    }
}