<?php

declare(strict_types=1);

namespace App\Fetcher;

use App\Entity\CityInterface;

class ChainFetcher implements FetcherInterface
{
    protected $fetchers = [];

    /**
     * Adds a loader instance.
     *
     * @param FetcherInterface $fetcher
     */
    public function addFetcher(FetcherInterface $fetcher)
    {
        if (false !== $key = array_search($fetcher, $this->fetchers)) {
            $this->fetchers[$key] = $fetcher;
        } else {
            $this->fetchers[] = $fetcher;
        }
    }

    /**
     * @param CityInterface $city
     * @return array
     */
    public function fetch(CityInterface $city): array
    {
        foreach ($this->fetchers as $fetcher) {
            if ($fetcher->isSupported($city)) {
                if (false !== $data = $fetcher->fetch($city)) {
                    return $data;
                }
            }
        }

        return [];
    }

    /**
     * Checks if Loader supports provided type.
     *
     * @param CityInterface $city
     *
     * @return bool
     */
    public function isSupported(CityInterface $city): bool
    {
        foreach ($this->fetchers as $fetcher) {
            if ($fetcher->isSupported($city)) {
                return true;
            }
        }

        return false;
    }
}
