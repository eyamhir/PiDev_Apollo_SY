<?php

namespace App\Entity;
use App\Entity\Portfolio;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;


/**
 * Exposition
 *
 * @ORM\Table(name="exposition", indexes={@ORM\Index(name="IDX_BC31FD1381DC659", columns={"portfolios_id"})})
 * @ORM\Entity(repositoryClass= App\Repository\ExpositionRepository::class)
 */
class Exposition
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="image_affiche", type="string", length=255, nullable=false)
     */
    private $imageAffiche;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=1000, nullable=false)
     */
    private $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_debut", type="date", nullable=false)
     */
    private $dateDebut;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_fin", type="date", nullable=false)
     */
    private $dateFin;

    /**
     * @var string
     *
     * @ORM\Column(name="type_expo", type="string", length=255, nullable=false)
     */
    private $typeExpo;

    /**
     * @var string
     *
     * @ORM\Column(name="lacalisation", type="string", length=255, nullable=false)
     */
    private $lacalisation;

    /**
     * @var \App\Entity\Portfolio|null
     *
     * @ORM\ManyToOne(targetEntity="Portfolio")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="portfolios_id", referencedColumnName="id")
     * })
     */
    private $portfolios;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImageAffiche(): ?string
    {
        return $this->imageAffiche;
    }

    public function setImageAffiche(string $imageAffiche): static
    {
        $this->imageAffiche = $imageAffiche;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): static
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): static
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getTypeExpo(): ?string
    {
        return $this->typeExpo;
    }

    public function setTypeExpo(string $typeExpo): static
    {
        $this->typeExpo = $typeExpo;

        return $this;
    }

    public function getLacalisation(): ?string
    {
        return $this->lacalisation;
    }

    public function setLacalisation(string $lacalisation): static
    {
        $this->lacalisation = $lacalisation;

        return $this;
    }

    public function getPortfolios(): Portfolio
    {
        return $this->portfolios;
    }

    public function setPortfolios(?Portfolio $portfolios): static
    {
        $this->portfolios = $portfolios;

        return $this;
    }


}
