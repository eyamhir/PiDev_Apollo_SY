<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Rate
 *
 * @ORM\Table(name="rate", indexes={@ORM\Index(name="rk_oeuvre", columns={"id_oeuvre"})})
 * @ORM\Entity(repositoryClass=App\Repository\RateRepository::class)
 *
 */
class Rate
{
    /**
     * @var int
     *
     * @ORM\Column(name="id_rate", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idRate;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_oeuvre", type="integer", nullable=true)
     */
    private $idOeuvre;

    /**
     * @var float
     *
     * @ORM\Column(name="rateNote", type="float", precision=10, scale=0, nullable=false)
     */
    private $ratenote;

    /**
     * @var int
     *
     * @ORM\Column(name="id_user", type="integer", nullable=false)
     */
    private $idUser;


}
