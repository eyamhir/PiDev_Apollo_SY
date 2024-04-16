<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Panier
 *
 * @ORM\Table(name="panier")
 * @ORM\Entity(repositoryClass=App\Repository\PanierRepository::class) */
class Panier
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_Panier", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idPanier;

    /**
     * @var int
     *
     * @ORM\Column(name="Nbr_Commande", type="integer", nullable=false)
     */
    private $nbrCommande;


}
