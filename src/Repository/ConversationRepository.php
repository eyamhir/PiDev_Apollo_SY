<?php

namespace App\Repository;

use App\Entity\Conversation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Message;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Conversation>
 *
 * @method Conversation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Conversation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Conversation[]    findAll()
 * @method Conversation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

//    /**
//     * @return Conversation[] Returns an array of Conversation objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Conversation
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }


public function findConversationByParticipants(int $me, int $otherUser): ?Conversation
{
    $qb = $this->createQueryBuilder('c');

    $qb->select('c') // Select the Conversation entity directly
        ->innerJoin('c.Participants', 'p1') // Join with participants
        ->innerJoin('c.Participants', 'p2') // Join with participants again
        ->where($qb->expr()->andX(
            $qb->expr()->eq('p1.utilisateur', ':me'),
            $qb->expr()->eq('p2.utilisateur', ':otherUser'),
            $qb->expr()->eq('c.Type_Conversation', ':conversationType')
        ))
        ->setParameter('me', $me)
        ->setParameter('otherUser', $otherUser)
        ->setParameter('conversationType', 'DUO')
        ->setMaxResults(1); // Limit result to a single conversation

    return $qb->getQuery()->getOneOrNullResult();
}
public function findByParticipant(Utilisateur $user): array
{
    return $this->createQueryBuilder('c')
        ->innerJoin('c.Participants', 'p')
        ->andWhere('p.utilisateur = :user')
        ->setParameter('user', $user)
        ->getQuery()
        ->getResult();
}

public function findConversationsByUser(int $userId): array
{
    $qb = $this->createQueryBuilder('c');

    $qb->select('otherUser.nom', 'otherUser.prenom','c.id AS conversationId','c.date_creation') // Use proper alias for clarity
        ->innerJoin('c.Participants', 'p', 'WITH', $qb->expr()->neq('p.utilisateur', ':userId'))
        ->innerJoin('c.Participants', 'me', 'WITH', $qb->expr()->neq('p.utilisateur', ':userId'))
     #   ->leftJoin('c.dernier_message', 'lm')
        ->innerJoin('me.utilisateur', 'meUser')
        ->innerJoin('p.utilisateur', 'otherUser')
        ->where('meUser.id = :user')  // Use 'user' for consistency
        ->setParameter('userId', $userId)
        ->setParameter('user', $userId) // Add the missing parameter
        ->orderBy('c.date_creation', 'DESC'); // Optional: Order by creation date

    return $qb->getQuery()->getResult();
}
public function findGroupConversationsByUser(int $userId): array
{
    $qb = $this->createQueryBuilder('c');

    $qb->select('c') // Sélectionner l'entité Conversation directement
        ->innerJoin('c.Participants', 'p') // Joindre avec les participants
        ->where($qb->expr()->andX(
            $qb->expr()->eq('p.utilisateur', ':userId'), // Vérifier que l'utilisateur est un participant
            $qb->expr()->eq('c.Type_Conversation', ':conversationType') // Vérifier que c'est une conversation de groupe
        ))
        ->setParameter('userId', $userId)
        ->setParameter('conversationType', 'GROUP');

    return $qb->getQuery()->getResult();
}
public function checkIfUserIsParticipant(int $conversationId, int $userId): bool
{
    $qb = $this->createQueryBuilder('c');
    $qb
        ->innerJoin('c.Participants', 'p')
        ->where('c.id = :conversationId')
        ->andWhere($qb->expr()->eq('p.utilisateur', ':userId'))
        ->setParameter('conversationId', $conversationId)
        ->setParameter('userId', $userId);

    return $qb->getQuery()->getOneOrNullResult() !== null;
}



public function updateLastMessageIds(Conversation $conversation, Message $message, EntityManagerInterface $entityManager)
{
    try {
        $qb = $this->createQueryBuilder('c');

        $qb->update('App\Entity\Conversation', 'c')
           ->set('c.dernier_message', $message->getId())
           ->getQuery()
           ->execute();
       
        $entityManager->flush();
    } catch (\Exception $e) {
        throw $e;
    }
}




public function getLastMessageDetails(int $conversationId): ?array
{
    $qb = $this->createQueryBuilder('c');

    $qb->select('lm.id as messageId', 'lm.contenu as messageContent', 'lm.createdAt as messageCreatedAt')
        ->leftJoin('c.dernier_message', 'lm')
        ->where('c.id = :conversationId')
        ->setParameter('conversationId', $conversationId);

    return $qb->getQuery()->getOneOrNullResult();
}



}
