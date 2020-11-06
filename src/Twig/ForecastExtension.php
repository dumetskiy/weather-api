<?php

declare(strict_types=1);

namespace App\Twig;

use App\DataFormatter\Date\DateDataFormatter;
use App\Entity\City;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ForecastExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('getCityCurrentDateTime', [$this, 'getCityCurrentDateTime']),
            new TwigFunction('getFormattedDate', [$this, 'getFormattedDate']),
        ];
    }

    public function getCityCurrentDateTime(City $city): \DateTimeInterface
    {
        return new \DateTime('now', new \DateTimeZone($city->getTimeZone()));
    }

    public function getFormattedDate(\DateTimeInterface $dateTime): string
    {
        return DateDataFormatter::getFormattedDate($dateTime);
    }
}