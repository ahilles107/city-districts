<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;

interface CityInterface
{
    public function getId(): int;

    public function getName(): ?string;

    public function setName(string $name);

    /**
     * @return Collection|District[]
     */
    public function getDistricts(): Collection;

    public function addDistrict(District $district);

    public function removeDistrict(District $district);
}
