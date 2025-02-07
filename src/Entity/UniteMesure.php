<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UniteMesure
 *
 * @ORM\Table(name="unite_mesure")
 * @ORM\Entity
 */
class UniteMesure
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_unite_mesure", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="unite_mesure_id_unite_mesure_seq", allocationSize=1, initialValue=1)
     */
    private $idUniteMesure;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nom_unite", type="string", length=50, nullable=true)
     */
    private $nomUnite;

    public function getIdUniteMesure(): ?int
    {
        return $this->idUniteMesure;
    }

    public function getNomUnite(): ?string
    {
        return $this->nomUnite;
    }

    public function setNomUnite(?string $nomUnite): static
    {
        $this->nomUnite = $nomUnite;

        return $this;
    }


}
