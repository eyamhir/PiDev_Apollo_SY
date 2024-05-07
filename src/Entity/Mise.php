<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Mise
 *
 * @ORM\Table(name="mise", indexes={@ORM\Index(name="fk_encher", columns={"id_enchers"}), @ORM\Index(name="fk_user", columns={"id_utilisateur"})})
 * @ORM\Entity(repositoryClass= App\Repository\MiseRepository::class)
 */
class Mise
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_mise", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idMise;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_enchers", type="integer", nullable=true)
     */
    private $idEnchers;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_utilisateur", type="integer", nullable=true)
     */
    private $idUtilisateur;

    /**
     * @var float
     *
     * @ORM\Column(name="max_montant", type="float", precision=10, scale=0, nullable=false)
     */
    private $maxMontant;

    public function getIdMise(): ?int
    {
        return $this->idMise;
    }

    public function getIdEnchers(): ?int
    {
        return $this->idEnchers;
    }

    public function setIdEnchers(?int $idEnchers): static
    {
        $this->idEnchers = $idEnchers;

        return $this;
    }

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?int $idUtilisateur): static
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    public function getMaxMontant(): ?float
    {
        return $this->maxMontant;
    }

    public function setMaxMontant(float $maxMontant): static
    {
        $this->maxMontant = $maxMontant;

        return $this;
    }


}
