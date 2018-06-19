<?php

declare(strict_types=1);

namespace App\DataFixtures;

use App\Entity\City;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class CityFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $gdansk = new City('Gdańsk');
        $krakow = new City('Kraków');

        $manager->persist($gdansk);
        $manager->persist($krakow);

        $manager->flush();
    }
}
