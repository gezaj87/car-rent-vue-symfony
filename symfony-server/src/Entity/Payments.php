<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PaymentsRepository")
 */
class Payments
{
    public function __construct()
    {
        $this->date = new \DateTime();
    }


    /**
     * @ORM\Id
     * @ORM\Column(type="string", length=23)
     */
    private $pId;

    /**
     * @ORM\Column(type="integer")
     * @ORM\ManyToOne(targetEntity="App\Entity\Users", inversedBy="payments")
     * @ORM\JoinColumn(name="u_id", referencedColumnName="uId")
     */
    private $uId;

    /**
     * @ORM\Column(type="string", length=8)
     * @ORM\ManyToOne(targetEntity="App\Entity\Cars", inversedBy="payments")
     * @ORM\JoinColumn(name="plate", referencedColumnName="plate")
     */
    private $plate;

    /**
     * @ORM\Column(type="integer")
     */
    private $price;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    public function getPId(): ?string
    {
        return $this->pId;
    }

    public function setPId(string $pId): self
    {
        $this->pId = $pId;

        return $this;
    }

    public function getUId(): ?int
    {
        return $this->uId;
    }

    public function setUId(int $uId): self
    {
        $this->uId = $uId;

        return $this;
    }

    public function getPlate(): ?string
    {
        return $this->plate;
    }

    public function setPlate(string $plate): self
    {
        $this->plate = $plate;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }
}