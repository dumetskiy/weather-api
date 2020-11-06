<?php

declare(strict_types=1);

namespace App\DataRenderer;

use App\DTO\WeatherForecastDTO;
use Twig\Environment as Twig;

class WeatherForecastRenderer
{
    public const TEMPLATE_HTML = 'html';
    public const TEMPLATE_CONSOLE = 'console';
    private const TEMPLATE_PATH_TEMPLATE = '/weather_forecast/forecast.%s.twig';

    private Twig $twig;

    public function __construct(Twig $twig)
    {
        $this->twig = $twig;
    }

    public function render(WeatherForecastDTO $weatherForecastDTO, string $templateType): string
    {
        return $this->twig->render(sprintf(self::TEMPLATE_PATH_TEMPLATE, $templateType), [
            'forecast' => $weatherForecastDTO,
        ]);
    }
}
