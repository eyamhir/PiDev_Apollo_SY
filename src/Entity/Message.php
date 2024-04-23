<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use phpDocumentor\Reflection\Types\Boolean;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $Contenu = null;
    private $mine;


    #[ORM\Column]
    private ?\DateTimeImmutable $date_envoi = null;

    #[ORM\ManyToOne(inversedBy: 'messages'),JoinColumn(nullable:false)]
    private ?Conversation $conversation = null;

    #[ORM\ManyToOne(inversedBy: 'messages'),JoinColumn(nullable:false)]
    private ?Utilisateur $utilisateur = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContenu(): ?string
    {
        return $this->Contenu;
    }

    public function setContenu(string $Contenu): static
    {
        $this->Contenu = $Contenu;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->date_envoi;
    }

    public function setCreatedAt(\DateTimeImmutable $CreatedAt): static
    {
        $this->date_envoi = $CreatedAt;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): static
    {
        $this->conversation = $conversation;

        return $this;
    }

    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }
    public function getMine(){
        return $this->mine;
    }
    public function SetMine():bool
    {
        $this->mine=true;
        return $this->mine;
    }
 
}
