<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Swagger\Annotations as SWG;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="city")
 *
 * @UniqueEntity("cityCode")
 */
class City
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(name="city_id", type="integer")
     *
     * @SWG\Property(description="Internal city id")
     */
    private int $id;

    /**
     * @ORM\Column(name="musement_city_id", type="integer")
     *
     * @SerializedName("id")
     *
     * @Assert\NotNull()
     *
     * @SWG\Property(description="Musement API city ID")
     */
    private int $musementCityId;

    /**
     * @ORM\Column(name="city_code", type="string", length=50)
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "City code is limited to {{ limit }} characters",
     *      allowEmptyString = false
     * )
     *
     * @SerializedName("code")
     *
     * @SWG\Property(description="City handle")
     */
    private string $cityCode;

    /**
     * @ORM\Column(name="name", type="string", length=50)
     *
     * @Assert\NotNull()
     * @Assert\Length(
     *      max = 50,
     *      maxMessage = "City name is limited to {{ limit }} characters",
     *      allowEmptyString = false
     * )
     *
     * @SWG\Property(description="City friendly name")
     */
    private string $name;

    /**
     * @ORM\Column(name="latitude", type="float", columnDefinition="FLOAT")
     *
     * @SWG\Property(description="City location latitude")
     */
    private float $latitude;

    /**
     * @var float
     *
     * @ORM\Column(name="longitude", type="float", columnDefinition="FLOAT")
     *
     * @SWG\Property(description="City location longitude")
     */
    private float $longitude;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getMusementCityId(): int
    {
        return $this->musementCityId;
    }

    /**
     * @param int $musementCityId
     */
    public function setMusementCityId(int $musementCityId): void
    {
        $this->musementCityId = $musementCityId;
    }

    public function getCityCode(): string
    {
        return $this->cityCode;
    }

    public function setCityCode($cityCode): void
    {
        $this->cityCode = $cityCode;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
