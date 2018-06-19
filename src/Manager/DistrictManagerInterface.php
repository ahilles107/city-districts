<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\CityInterface;

interface DistrictManagerInterface
{
    /**
     * @param CityInterface $city
     * @param array $districts
     */
    public function synchronize(CityInterface $city, array $districts): void;
}
