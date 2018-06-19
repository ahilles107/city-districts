<?php

namespace App\Tests\Manager;

use App\Entity\City;
use App\Entity\District;
use App\Manager\DistrictManager;
use App\Repository\DistrictRepositoryInterface;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;

class DistrictManagerTest extends TestCase
{
    public function testSynchronizing()
    {
        $repository = $this->createMock(DistrictRepositoryInterface::class);
        $repository->expects($this->any())
            ->method('getForCity')
            ->with(new City('Gdańsk'))
            ->willReturn([]);

        $em = $this->createMock(EntityManager::class);
        $em->expects($this->any())->method('getRepository')->willReturn($repository);
        $em->expects($this->exactly(2))->method('flush')->willReturn(true);
        $em->expects($this->exactly(0))->method('persist')->willReturn(true);
        $districtManager = new DistrictManager($em, $repository);
        self::assertNull($districtManager->synchronize(new City('Gdańsk'), []));


        $repository = $this->createMock(DistrictRepositoryInterface::class);
        $repository->expects($this->any())->method('getForCity')->with(new City('Gdańsk'))->willReturn([new District(new City('Gdańsk'), 'Przymorze Wielkie')]);
        $districtManager = new DistrictManager($em, $repository);
        $districtManager->synchronize(new City('Gdańsk'), [
            [
                'name' => 'Przymorze Wielkie',
                'area' => 3.3,
                'population' => 101
            ]
        ]);


        $em = $this->createMock(EntityManager::class);
        $em->expects($this->exactly(1))->method('persist')->willReturn(true);
        $repository = $this->createMock(DistrictRepositoryInterface::class);
        $repository->expects($this->any())->method('getForCity')->with(new City('Gdańsk'))->willReturn([]);
        $districtManager = new DistrictManager($em, $repository);
        $districtManager->synchronize(new City('Gdańsk'), [
            [
                'name' => 'Przymorze Wielkie',
                'area' => 3.3,
                'population' => 101
            ]
        ]);
    }
}
