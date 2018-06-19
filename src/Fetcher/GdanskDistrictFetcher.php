<?php

declare(strict_types=1);

namespace App\Fetcher;

use App\Entity\CityInterface;
use Symfony\Component\DomCrawler\Crawler;
use GuzzleHttp\Exception\GuzzleException;

class GdanskDistrictFetcher extends UrlFetcher implements FetcherInterface
{
    private const CITY_NAME = 'Gdańsk';
    private const DISTRICTS_URL = 'http://www.gdansk.pl/dzielnice';
    private const MAIN_URL = 'http://www.gdansk.pl';

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
        $children = $crawler->filter('body > div.container > div:nth-child(1) > div.col-md-8.dzielnice.block > div:nth-child(1) > div > ul > li > a > span');
        $districts = [];
        /** @var \DOMElement $child */
        foreach ($children as $child) {
            $district = $child->nodeValue;
            if ($district !== 'Wszystkie') {
                $districtData = $this->getDataFromDistrictPage(self::MAIN_URL.'/'.$child->parentNode->getAttribute('href'));

                $districts[] = [
                    'name' => $district,
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
     * @param $url
     * @return array
     */
    private function getDataFromDistrictPage($url): array
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
        $districtDataNodes = $crawler->filter('.opis#hideMe > div');
        foreach ($districtDataNodes as $node) {
            $value = $node->nodeValue;
            if (false !== strpos($value, 'Powierzchnia:')) {
                $data['area'] = (float) str_replace(',', '.', str_replace('Powierzchnia: ', '', str_replace(' km2', '', $value)));
            } elseif (false !== strpos($value, 'Liczba ludności:')) {
                $data['population'] = (int) str_replace('Liczba ludności: ', '', str_replace(' osób', '', $value));
            }
        }

        return $data;
    }
}
