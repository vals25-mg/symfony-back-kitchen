<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Plat
 *
 * @ORM\Table(name="plat")
 * @ORM\Entity
 */
class Plat
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_plat", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="plat_id_plat_seq", allocationSize=1, initialValue=1)
     */
    private $idPlat;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_plat", type="string", length=50, nullable=false)
     */
    private $nomPlat;

    /**
     * @var string|null
     *
     * @ORM\Column(name="url", type="string", length=200, nullable=true)
     */
    private $url;

    /**
     * @var string|null
     *
     * @ORM\Column(name="logo", type="string", length=200, nullable=true)
     */
    private $logo;

    /**
     * @var string|null
     *
     * @ORM\Column(name="temps_cuisson", type="string", nullable=true)
     */
    private $tempsCuisson;

    /**
     * @var float|null
     *
     * @ORM\Column(name="prix", type="float", precision=10, scale=0, nullable=true)
     */
    private $prix;

    public function getIdPlat(): ?int
    {
        return $this->idPlat;
    }

    public function getNomPlat(): ?string
    {
        return $this->nomPlat;
    }

    public function setNomPlat(string $nomPlat): static
    {
        $this->nomPlat = $nomPlat;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): static
    {
        $this->url = $url;

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): static
    {
        $this->logo = $logo;

        return $this;
    }

    public function getTempsCuisson(): ?string
    {
        return $this->tempsCuisson;
    }

    public function setTempsCuisson(?string $tempsCuisson): static
    {
        $this->tempsCuisson = $tempsCuisson;

        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }


}
