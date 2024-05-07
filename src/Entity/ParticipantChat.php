<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ParticipantChat
 *
 * @ORM\Table(name="participant_chat", indexes={@ORM\Index(name="fk_usera", columns={"id_utilisateur"}), @ORM\Index(name="fk_conversationa", columns={"conversation_id"})})
 * @ORM\Entity(repositoryClass= App\Repository\ParticipantChatRepository::class)
 */
class ParticipantChat
{
    /**
     * @var int
     *
     * @ORM\Column(name="participant_id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $participantId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="conversation_id", type="integer", nullable=true)
     */
    private $conversationId;

    /**
     * @var int|null
     *
     * @ORM\Column(name="id_utilisateur", type="integer", nullable=true)
     */
    private $idUtilisateur;

    public function getParticipantId(): ?int
    {
        return $this->participantId;
    }

    public function getConversationId(): ?int
    {
        return $this->conversationId;
    }

    public function setConversationId(?int $conversationId): static
    {
        $this->conversationId = $conversationId;

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


}
