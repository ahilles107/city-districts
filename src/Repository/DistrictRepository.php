<?php

namespace App\Repository;

use App\Entity\CityInterface;
use App\Entity\District;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DistrictRepository extends ServiceEntityRepository implements DistrictRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, District::class);
    }

    /**
     * {@inheritdoc}
     */
    public function getForCity(CityInterface $city)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.city = :city')
            ->setParameter('city', $city)
            ->getQuery()
            ->getResult()
            ;
    }
}
