<?php
namespace App;

use App\Entity\Message;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;
use App\Repository\ConversationRepository;
class MessageHandler implements MessageComponentInterface
{
 
    protected $connections;
    protected $entityManager;
    protected $conversationRepository;
 
    public function __construct(EntityManagerInterface $entityManager,ConversationRepository $conversationRepository)
    {
        $this->connections = new SplObjectStorage;
        $this->entityManager = $entityManager;
        $this->conversationRepository = $conversationRepository;
      
    }
   
    public function onOpen(ConnectionInterface $conn)
{
    $this->connections->attach($conn);
}
public function onClose(ConnectionInterface $conn)
{
    $this->connections->detach($conn);
}

public function onError(ConnectionInterface $conn, Exception $e)
{
    $this->connections->detach($conn);
    $conn->close();
}

public function onMessage(ConnectionInterface $from, $msg)
{

      // Convertir le message JSON en tableau associatif
        $messageData = json_decode($msg, true);  

        // Vérifier si le type de message est "new_message"
        if ($messageData['type'] === 'new_message') {
            // Récupérer les données du message
            $data = $messageData['data'];

            // Créer une nouvelle entité Message
            $message = new Message();
            $message->setContenu($data['contenu']);
            $message->setCreatedAt(new \DateTimeImmutable($data['createdAt']));

            // Récupérer l'utilisateur à partir de l'ID
            $utilisateur = $this->entityManager->getReference('App\Entity\Utilisateur', $data['utilisateur_id']);
            $message->setUtilisateur($utilisateur);

            // Récupérer la conversation à partir de l'ID
            $conversation = $this->entityManager->getReference('App\Entity\Conversation', $data['conversation_id']);
            $conversation->addMessage($message);

            // Définir d'autres attributs du message
            $message->setMine($data['mine']); // true ou false
          
            // Enregistrer le message dans la base de données
            $this->entityManager->persist($message);
            $this->entityManager->flush();
  // Mettre à jour le dernier message de la conversation
  $this->conversationRepository->updateLastMessageIds($conversation, $message, $this->entityManager);

            foreach ($this->connections as $connection) {
                $connection->send($msg);
            }
        }
    }
}

?>