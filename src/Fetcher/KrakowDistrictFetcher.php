<?php

declare(strict_types=1);

namespace App\Fetcher;

use App\Entity\CityInterface;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Exception\GuzzleException;

class KrakowDistrictFetcher extends UrlFetcher implements FetcherInterface
{
    private const CITY_NAME = 'Kraków';
    private const DISTRICTS_URL = 'http://appimeri.um.krakow.pl/app-pub-dzl/pages/DzlViewAll.jsf?a=1&lay=normal&fo=0';
    private const DISTRICT_URL_PATTERN = 'http://appimeri.um.krakow.pl/app-pub-dzl/pages/DzlViewGlw.jsf?id=%s&lay=normal&fo=0';

    /**
     * {@inheritdoc}
     */
    public function fetch(CityInterface $city): array
    {
        try {
            $pageSource = $this->getUrlSource(self::DISTRICTS_URL);
        } catch (GuzzleException $e) {
            return [];
        }

        $crawler = new Crawler($pageSource);
        $children = $crawler->filter('#mainDiv > form > select > option');
        $districts = [];
        /** @var \DOMElement $child */
        foreach ($children as $child) {
            $district = $child->getAttribute('value');
            $districtName = $child->nodeValue;
            if ($district !== 'Wszystkie') {
                $districtData = $this->getDataFromDistrictPage(sprintf(self::DISTRICT_URL_PATTERN, $district));

                $districts[] = [
                    'name' => $districtName,
                    'population' => $districtData['population'],
                    'area' => $districtData['area']
                ];
            }
        }

        return $districts;
    }

    /**
     * {@inheritdoc}
     */
    public function isSupported(CityInterface $city): bool
    {
        if ($city->getName() === self::CITY_NAME) {
            return true;
        }

        return false;
    }

    /**
     * @param string $url
     * @return array
     */
    private function getDataFromDistrictPage(string $url): array
    {
        $data = [
            'population' => 0,
            'area' => 0
        ];

        try {
            $pageSource = $this->getUrlSource($url);
        } catch (GuzzleException $e) {
            return $data;
        }

        $crawler = new Crawler($pageSource);
        $districtDataNodes = $crawler->filter('#mainDiv > table:nth-child(2) > tr > td > table:nth-child(2) > tr > td:nth-child(2)');
        foreach ($districtDataNodes as $node) {
            $value = $node->nodeValue;
            if (false !== strpos($value, 'ha')) {
                $data['area'] = round((float) str_replace(',', '.', str_replace(' ha', '', $value)) / 100, 2);
            } else {
                $data['population'] = (int) $value;
            }
        }

        return $data;
    }
}
