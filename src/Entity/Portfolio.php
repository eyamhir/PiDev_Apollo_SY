<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="portfolio")
 * @ORM\Entity(repositoryClass= App\Repository\PortfolioRepository::class)
 */
class Portfolio
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
     * @ORM\Column(name="nom_artistique", type="string", length=255, nullable=false)
     */
    private $nomArtistique;

    /**
     * @var string|null
     *
     * @ORM\Column(name="image_url", type="string", length=255, nullable=true)
     */
    private $imageUrl;

    /**
     * @var string
     *
     * @ORM\Column(name="biographie", type="string", length=1000, nullable=false)
     */
    private $biographie;

    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="debut_carriere", type="date", nullable=true)
     */
    private $debutCarriere;

    /**
     * @var string|null
     *
     * @ORM\Column(name="reseau_sociaux", type="string", length=255, nullable=true)
     */
    private $reseauSociaux;

    /**
     * @ORM\OneToOne(targetEntity=User::class, cascade={"persist", "remove"})
     */
    private $id_user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomArtistique(): ?string
    {
        return $this->nomArtistique;
    }

    public function setNomArtistique(string $nomArtistique): static
    {
        $this->nomArtistique = $nomArtistique;

        return $this;
    }

    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    public function setImageUrl(?string $imageUrl): static
    {
        $this->imageUrl = $imageUrl;

        return $this;
    }

    public function getBiographie(): ?string
    {
        return $this->biographie;
    }

    public function setBiographie(string $biographie): static
    {
        $this->biographie = $biographie;

        return $this;
    }

    public function getDebutCarriere(): ?\DateTimeInterface
    {
        return $this->debutCarriere;
    }

    public function setDebutCarriere(?\DateTimeInterface $debutCarriere): static
    {
        $this->debutCarriere = $debutCarriere;

        return $this;
    }

    public function getReseauSociaux(): ?string
    {
        return $this->reseauSociaux;
    }

    public function setReseauSociaux(?string $reseauSociaux): static
    {
        $this->reseauSociaux = $reseauSociaux;

        return $this;
    }

    public function getIdUser(): ?user
    {
        return $this->id_user;
    }

    public function setIdUser(?user $id_user): self
    {
        $this->id_user = $id_user;

        return $this;
    }


}
