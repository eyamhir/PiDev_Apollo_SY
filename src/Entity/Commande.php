<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Commande
 *
 * @ORM\Table(name="commande", indexes={@ORM\Index(name="fk_panier", columns={"id_Panier"}), @ORM\Index(name="id_utilisateur", columns={"id_utilisateur"}), @ORM\Index(name="fk_payment", columns={"id_Payment"})})
 * @ORM\Entity(repositoryClass= App\Repository\CommandeRepository::class)
 */
class Commande
{
    /**
     * @var int|null
     *
     * @ORM\Column(name="id_utilisateur", type="integer", nullable=true)
     */
    private $idUtilisateur;

    /**
     * @var int
     *
     * @ORM\Column(name="id_Commande", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idCommande;

    /**
     * @var float
     *
     * @ORM\Column(name="Prix_total", type="float", precision=10, scale=0, nullable=false)
     */
    private $prixTotal;

    /**
     * @var string
     *
     * @ORM\Column(name="date_creation_commande", type="text", length=16777215, nullable=false)
     */
    private $dateCreationCommande;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_Panier", type="integer", nullable=true)
     */
    private $idPanier;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_Payment", type="integer", nullable=true)
     */
    private $idPayment;

    public function getIdUtilisateur(): ?int
    {
        return $this->idUtilisateur;
    }

    public function setIdUtilisateur(?int $idUtilisateur): static
    {
        $this->idUtilisateur = $idUtilisateur;

        return $this;
    }

    public function getIdCommande(): ?int
    {
        return $this->idCommande;
    }

    public function getPrixTotal(): ?float
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(float $prixTotal): static
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }

    public function getDateCreationCommande(): ?string
    {
        return $this->dateCreationCommande;
    }

    public function setDateCreationCommande(string $dateCreationCommande): static
    {
        $this->dateCreationCommande = $dateCreationCommande;

        return $this;
    }

    public function getIdPanier(): ?int
    {
        return $this->idPanier;
    }

    public function setIdPanier(?int $idPanier): static
    {
        $this->idPanier = $idPanier;

        return $this;
    }

    public function getIdPayment(): ?int
    {
        return $this->idPayment;
    }

    public function setIdPayment(?int $idPayment): static
    {
        $this->idPayment = $idPayment;

        return $this;
    }


}
