<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use DateTime;

class AgeExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('age', [$this, 'calculateAge']),
        ];
    }

    public function calculateAge($birthdate)
    {
        $today = new DateTime();
        $diff = $today->diff($birthdate);
        return $diff->y;
    }
}

