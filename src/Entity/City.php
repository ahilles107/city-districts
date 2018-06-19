<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CityRepository")
 */
class City implements CityInterface
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
     * @ORM\OneToMany(targetEntity="App\Entity\District", mappedBy="city", orphanRemoval=true)
     */
    private $districts;

    public function __construct(? string $name)
    {
        if ($name) {
            $this->setName($name);
        }

        $this->districts = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return Collection|District[]
     */
    public function getDistricts(): Collection
    {
        return $this->districts;
    }

    public function addDistrict(District $district)
    {
        if (!$this->districts->contains($district)) {
            $this->districts[] = $district;
            $district->setCity($this);
        }
    }

    public function removeDistrict(District $district)
    {
        if ($this->districts->contains($district)) {
            $this->districts->removeElement($district);

            if ($district->getCity() === $this) {
                $district->setCity(null);
            }
        }
    }

    public function __toString()
    {
        return $this->getName();
    }
}
