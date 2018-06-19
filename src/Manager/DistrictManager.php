<?php

declare(strict_types=1);

namespace App\Manager;

use App\Entity\CityInterface;
use App\Entity\District;
use App\Repository\DistrictRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;

class DistrictManager implements DistrictManagerInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var DistrictRepositoryInterface
     */
    private $repository;

    /**
     * DistrictManager constructor.
     * @param EntityManagerInterface $manager
     * @param DistrictRepositoryInterface $repository
     */
    public function __construct(EntityManagerInterface $manager, DistrictRepositoryInterface $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function synchronize(CityInterface $city, array $districts): void
    {
        $existingDistricts = $this->repository->getForCity($city);
//        if needed can be uncommented and will remove not exisitng on source districts
//
//        $districtsNames = array_map(function($district) {
//            return $district['name'];
//        }, $districts);
//
//        // remove from db not existing in fetched data (district was destroyed)
//        foreach ($existingDistricts as $existingDistrict) {
//            if (!\in_array($existingDistrict->getName(), $districtsNames)) {
//                $this->manager->remove($existingDistrict);
//            }
//        }
//        unset($existingDistrict);

        // add new or update districts
        foreach ($districts as $district) {
            $foundDistricts = \array_filter(
                $existingDistricts,
                function($d) use (&$district) {
                    return $d->getName() === $district['name'];
                }
            );

            $existingDistrict = null;
            if (count($foundDistricts) === 1) {
                $existingDistrict = reset($foundDistricts);
            }

            if (null === $existingDistrict) {
                $newDistrict = new District($city, $district['name'], $district['population'], $district['area']);
                $this->manager->persist($newDistrict);
            } else {
                $existingDistrict->setArea($district['area']);
                $existingDistrict->setPopulation($district['population']);
            }
        }

        $this->manager->flush();
    }
}
