<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DistrictRepository")
 */
class District
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $population;

    /**
     * @ORM\Column(type="float")
     */
    private $area;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\City", inversedBy="districts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $city;

    public function __construct(CityInterface $city = null, string $name = null, int $population = 0, float $area = 0)
    {
        if ($city) {
            $this->setCity($city);
        }

        if ($name) {
            $this->setName($name);
        }

        $this->setPopulation($population);
        $this->setArea($area);
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getPopulation(): ?int
    {
        return $this->population;
    }

    public function setPopulation(int $population): self
    {
        $this->population = $population;

        return $this;
    }

    public function getArea(): ?float
    {
        return $this->area;
    }

    public function setArea(float $area): self
    {
        $this->area = $area;

        return $this;
    }

    public function getCity(): ?CityInterface
    {
        return $this->city;
    }

    public function setCity(?CityInterface $city): self
    {
        $this->city = $city;

        return $this;
    }
}
