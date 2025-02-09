<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Ingredient
 *
 * @ORM\Table(name="ingredient", indexes={@ORM\Index(name="IDX_6BAF7870C5ADBDF6", columns={"id_unite_mesure"})})
 * @ORM\Entity
 */
class Ingredient
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_ingredient", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="ingredient_id_ingredient_seq", allocationSize=1, initialValue=1)
     */
    private $idIngredient;

    /**
     * @var string
     *
     * @ORM\Column(name="nom_ingredient", type="string", length=50, nullable=false)
     */
    private $nomIngredient;

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
     * @var \UniteMesure
     *
     * @ORM\ManyToOne(targetEntity="UniteMesure")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_unite_mesure", referencedColumnName="id_unite_mesure")
     * })
     */
    private $idUniteMesure;

    public function getId(): ?int
{
    return $this->idIngredient;
}

    public function getIdIngredient(): ?int
    {
        return $this->idIngredient;
    }

    public function getNomIngredient(): ?string
    {
        return $this->nomIngredient;
    }

    public function setNomIngredient(string $nomIngredient): static
    {
        $this->nomIngredient = $nomIngredient;

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

    public function getIdUniteMesure(): ?UniteMesure
    {
        return $this->idUniteMesure;
    }

    public function setIdUniteMesure(?UniteMesure $idUniteMesure): static
    {
        $this->idUniteMesure = $idUniteMesure;

        return $this;
    }


}
