<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\CityInterface;
use App\Entity\District;

interface DistrictRepositoryInterface
{
    /**
     * @return District[] Returns an array of District objects
     */
    public function getForCity(CityInterface $city);
}
