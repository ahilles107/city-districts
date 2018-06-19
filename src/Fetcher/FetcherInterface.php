<?php

declare(strict_types=1);

namespace App\Fetcher;

use App\Entity\CityInterface;

interface FetcherInterface
{
    /**
     * @param CityInterface $city
     *
     * @return array
     */
    public function fetch(CityInterface $city): array;

    /**
     * Checks if Loader supports provided type.
     *
     * @param CityInterface $city
     *
     * @return bool
     */
    public function isSupported(CityInterface $city): bool;
}
