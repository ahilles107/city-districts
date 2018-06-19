<?php

declare(strict_types=1);

namespace App\Fetcher;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

abstract class UrlFetcher
{
    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * UrlFetcher constructor.
     */
    public function __construct()
    {
        $this->client = new Client();
    }

    /**
     * @param $url
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    protected function getUrlSource($url): string
    {
        return $this->client->request('GET', $url)->getBody()->getContents();
    }
}
