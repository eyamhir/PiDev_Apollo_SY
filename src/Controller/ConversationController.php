<?php
namespace App\Controller;

use App\Entity\Conversation;
use App\Entity\Message;
use App\Entity\ParticipantChat;
use App\Entity\Utilisateur;
use App\Repository\ConversationRepository;
use App\Form\ConversationType;
use App\Repository\MessageRepository;
use App\Repository\ParticipantChatRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class ConversationController extends AbstractController
{
 /**
     * @var UtilisateurRepository
     */
    private  $utilisateurRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManagerInterface;

    /**
     * @var ConversationRepository
     */
    private $conversationRepository;
    /**
     * @var ParticipantChatRepository
     */
    private $participantRepository;
    private $messageRepository;
    public function __construct(UtilisateurRepository $utilisateurRepository,EntityManagerInterface $entityManagerInterface,
    ConversationRepository $conversationRepository,MessageRepository $messageRepository,
   ParticipantChatRepository $participantRepository) {

        $this->utilisateurRepository = $utilisateurRepository;
        $this->entityManagerInterface = $entityManagerInterface;
        $this->conversationRepository = $conversationRepository;
        $this->messageRepository = $messageRepository;
        $this->participantRepository = $participantRepository;
        
        }

        #[Route('/user/{id}', name: 'ajouter_Conversation')]
        public function indexa(Request $request)
        {
            $otherUserId = $request->get('id'); // Get user ID from request
       
            // Find the other user
            $otherUser = $this->utilisateurRepository->find($otherUserId);
        
            if (is_null($otherUser)) {
                throw new \Exception('User not found'); // Clearer error message
            }
        
            // Check if user is trying to talk to themselves
            if ($otherUser->getId() === $this->getUser()->getId()) {
                throw new \Exception('User cannot talk to themselves');
            }
        
            // Find conversation between users
            $conversation = $this->conversationRepository->findConversationByParticipants(
                $this->getUser()->getId(),
                $otherUser->getId()
            );
           # dd($conversation);
            if($conversation !== null) {
                throw new \Exception('the conversation already existe');
            }
            $conversation=new Conversation();
            $conversation->setSujet($this->getUser()->getNom());
            $conversation->setTitre($otherUser->getNom());
            $conversation->setDescription('la conversation entre '+$this->getUser()->getNom()+'et'+$otherUser->getNom());
            $conversation->setdateCreation(new \DateTimeImmutable());  
            $conversation->setTypeConversation('DUO');
            $conversation->setVisibilite('PUBLIC');
            $participant=new ParticipantChat();
            $participant->setUtilisateur($this->getUser());
            $participant->setConversation($conversation);
            $otherparticipant=new ParticipantChat();
            $otherparticipant->setUtilisateur($otherUser);
            $otherparticipant->setConversation($conversation);
            $this->entityManagerInterface->getConnection()->beginTransaction();
            try{
                $this->entityManagerInterface->persist($conversation);
                $this->entityManagerInterface->flush();
                $this->entityManagerInterface->persist($participant); 
                $this->entityManagerInterface->flush();
                $this->entityManagerInterface->persist($otherparticipant); 
                $this->entityManagerInterface->flush(); 
                $this->entityManagerInterface->commit();
            }catch(\Exception $e){
        $this->entityManagerInterface->rollback();
        throw $e;
            }
            
        #dd($conversation);
        return $this->json([
            'id'=>$conversation->getId(),
        
        ],Response::HTTP_CREATED,[],[]);
        }
        #[Route('/conversations', name: 'getConversations')]
        public function getConversations(Request $request, ConversationRepository $conversationRepository ,Security $security)
        {
            // Get current user
            $user = $security->getUser();
           
            if (!$user instanceof Utilisateur) {
                throw new \Exception("Utilisateur non valide.");
            }
        
            // Fetch conversations where the current user is a participant
            $conversations = $conversationRepository->findByParticipant($user);
           
        
            // Transform conversations and participants into serialized data
            $serializedConversations = [];
            foreach ($conversations as $conversation) {
                $serializedParticipants = [];
                foreach ($conversation->getParticipants() as $participant) {
                  // Assuming you have a method to get the Utilisateur entity from Participant entity
                  $utilisateur = $participant->getUtilisateur();
        
                  // Assuming you have getNom() and getPrenom() methods in Utilisateur entity
                  $nom = $utilisateur->getNom();
                  $prenom = $utilisateur->getPrenom();
        
                  // Add participant's name and prenom to the serialized data
                  $serializedParticipants[] = [
                      'id' => $participant->getId(),
                      'nom' => $nom,
                      'prenom' => $prenom,
                      // Add other participant properties if needed
                  ];
                }
        
                $serializedConversations[] = [
                    'id' => $conversation->getId(),
                    'sujet' => $conversation->getSujet(),
                    'titre' => $conversation->getTitre(),
                    'description' => $conversation->getDescription(),
                    'createdAt' => $conversation->getdateCreation()->format('Y-m-d H:i:s'),
                    'typeConversation' => $conversation->getTypeConversation(),
                    'visibilite' => $conversation->getVisibilite(),
                    'participants' => $serializedParticipants,
                    // Add other conversation properties if needed
                ];
            }
        
               // Pass the data to the Twig template
               return $this->render('conversation/liste_conversations.html.twig', [
                'conversations' => $serializedConversations,
                'user' => $user, // Pass the current user to the template
            ]);
        }
        

        #[Route('/conversations/{id}/edit', name: 'edit_conversation')]
public function editConversation(Request $request, ConversationRepository $conversationRepository, int $id): Response
{
    // Get the conversation to be edited by its ID
    $conversation = $conversationRepository->find($id);

    // Check if conversation exists
    if (!$conversation) {
        throw $this->createNotFoundException('Conversation not found');
    }

    // Create the form to edit conversation details
    $form = $this->createForm(ConversationType::class, $conversation);

    // Handle form submission
    $form->handleRequest($request);

    // Check if form is submitted and valid
    if ($form->isSubmitted() && $form->isValid()) {
        // Save the updated conversation to the database
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->flush();

        // Redirect to a success page or show a success message
        return $this->redirectToRoute('getConversations', ['id' => $conversation->getId()]);
    }

    // If form is not valid, add flash message to display errors
    if ($form->isSubmitted() && !$form->isValid()) {
        $this->addFlash('error', 'There was an error with the form. Please check your input.');
    }

    // Render the edit form template with the conversation data
    return $this->render('conversation/edit_conversations.html.twig', [
        'form' => $form->createView(),
        'conversation' => $conversation,
    ]);
}
        
#[Route('/conversations/{id}/delete', name: 'delete_conversation')]
public function deleteConversation(Request $request, ConversationRepository $conversationRepository, int $id): Response
{
    $entityManager = $this->getDoctrine()->getManager();
    
    // Get the conversation to be deleted by its ID
    $conversation = $entityManager->getRepository(Conversation::class)->find($id);

    // Check if conversation exists
    if (!$conversation) {
        throw $this->createNotFoundException('Conversation not found');
    }

    // Get all messages associated with the conversation
    $messages = $conversation->getMessages();

    // Remove all messages associated with the conversation
    foreach ($messages as $message) {
        $entityManager->remove($message);
    }

    // Flush changes to remove messages
    $entityManager->flush();

    // Get all participants of the conversation
    $participants = $conversation->getParticipants();

    // Remove all participants from the conversation
    foreach ($participants as $participant) {
        $entityManager->remove($participant);
    }

    // Flush changes to remove participants
    $entityManager->flush();

    // Now delete the conversation itself
    $entityManager->remove($conversation);
    $entityManager->flush();

    // Redirect to a success page or show a success message
    $this->addFlash('success', 'Conversation, its messages, and participants have been successfully deleted.');

    return $this->redirectToRoute('getConversations');
}

#[Route('/conversation/create', name: 'create_groupe_conversation')]
public function createGroupeConversation(Request $request, EntityManagerInterface $entityManager, UtilisateurRepository $utilisateurRepository, ValidatorInterface $validator)
{
    $form = $this->createForm(ConversationType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $conversationData = $form->getData();

        // Créer la conversation de groupe avec les données du formulaire
        $conversation = new Conversation();
        $conversation->setSujet($conversationData->getSujet());
        $conversation->setTitre($conversationData->getTitre());
        $conversation->setDescription($conversationData->getDescription());
        $conversation->setdateCreation(new \DateTimeImmutable());
        $conversation->setTypeConversation('GROUP');
        $conversation->setVisibilite($conversationData->getVisibilite());
        
        // Valider la conversation
        $errors = $validator->validate($conversation);

        if (count($errors) > 0) {
            // Il y a des erreurs de validation, gérez-les ici (par exemple, renvoyez-les à la vue)
            return $this->render('conversation/creer_conversation.html.twig', [
                'form' => $form->createView(),
                'errors' => $errors, // Passez les erreurs à la vue pour les afficher
            ]);
        }
        
        // Récupérer l'utilisateur connecté pour l'ajouter comme participant
        $currentUser = $this->getUser();
        $participant = new ParticipantChat();
        $participant->setUtilisateur($currentUser);
        $participant->setConversation($conversation);
        $entityManager->persist($participant);

        // Enregistrer la conversation dans la base de données
        $entityManager->persist($conversation);
        $entityManager->flush();
        $conversationId = $conversation->getId();
        // Rediriger vers la liste des amis pour sélectionner d'autres participants
        return new RedirectResponse($this->generateUrl('appa',['conversationId' => $conversationId])); // Remplacez 'liste_amis' par le nom de la route de la liste des amis
    }

    return $this->render('conversation/creer_conversation.html.twig', [
        'form' => $form->createView(),
    ]);
}


#[Route('/conversation/with/{userId}', name: 'conversation_with_user')]
public function conversationWithUser(Request $request, EntityManagerInterface $entityManager): Response
{
    $otherUserId = $request->get('userId');
    $userId = $this->getUser()->getId(); 
    
    // Trouver l'autre utilisateur
    $otherUser = $this->utilisateurRepository->find($otherUserId);
    if (is_null($otherUser)) {
        throw new \Exception('User not found');
    }
    
    // Récupérer tous les utilisateurs sauf l'utilisateur courant
    $users = $this->utilisateurRepository->createQueryBuilder('u')
        ->andWhere('u != :user')
        ->setParameter('user', $userId)
        ->getQuery()
        ->getResult();
    
    // Trouver la conversation entre l'utilisateur actuel et l'autre utilisateur
    $conversation = $this->conversationRepository->findConversationByParticipants(
        $this->getUser()->getId(),
        $otherUser->getId()
    );

    // Si la conversation n'existe pas, en créer une nouvelle
    if (is_null($conversation)) {
        $conversation = new Conversation();
        $conversation->setSujet($otherUser->getNom());
        $conversation->setTitre($this->getUser()->getNom());
        $conversation->setDescription('La conversation entre ' . $this->getUser()->getNom() . ' et ' . $otherUser->getNom());
        $conversation->setdateCreation(new \DateTimeImmutable());  
        $conversation->setTypeConversation('DUO');
        $conversation->setVisibilite('PRIVATE');
        
        // Ajouter les participants à la conversation
        $participant = new ParticipantChat();
        $participant->setUtilisateur($this->getUser());
        $participant->setConversation($conversation);
        $otherparticipant = new ParticipantChat();
        $otherparticipant->setUtilisateur($otherUser);
        $otherparticipant->setConversation($conversation);
        
        $this->entityManagerInterface->getConnection()->beginTransaction();
        try {
            $this->entityManagerInterface->persist($conversation);
            $this->entityManagerInterface->flush();
            $this->entityManagerInterface->persist($participant); 
            $this->entityManagerInterface->flush();
            $this->entityManagerInterface->persist($otherparticipant); 
            $this->entityManagerInterface->flush(); 
            $this->entityManagerInterface->commit();
        } catch (\Exception $e) {
            $this->entityManagerInterface->rollback();
            throw $e;
        }
    }

    // Récupérer les messages pour la conversation
    $messages = $this->messageRepository->findMessageByConversationId($conversation->getId());

    // Rendre la vue de la conversation avec les détails de la conversation et les messages
    return $this->render('conversation/conversation_duo.html.twig', [
        'conversation' => $conversation,
        'messages' => $messages,
        'users' => $users,
    ]);
}

#[Route('/conversation/{conversationId}', name: 'conversation_with_group')]
public function conversationWithGroup(Request $request, EntityManagerInterface $entityManager, ConversationRepository $conversationRepository, int $conversationId): Response
{
    // Récupérer l'ID de l'utilisateur connecté
    $userId = $this->getUser()->getId();

    // Récupérer la conversation associée à l'ID fourni
    $conversation = $conversationRepository->find($conversationId);

    // Vérifier si la conversation existe
    if (!$conversation) {
        throw $this->createNotFoundException('La conversation n\'existe pas.');
    }

    // Vérifier si la conversation est de type groupe
    if ($conversation->getTypeConversation() !== 'GROUP') {
        throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette conversation car elle n\'est pas de type groupe.');
    }

    // Vérifier si l'utilisateur est un participant de la conversation
    $participants = $conversation->getParticipants();
    $isParticipant = false;
    foreach ($participants as $participant) {
        if ($participant->getUtilisateur()->getId() === $userId) {
            $isParticipant = true;
            break;
        }
    }

    // Si l'utilisateur n'est pas un participant, lancer une exception
    if (!$isParticipant) {
        throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à accéder à cette conversation.');
    }

    // Récupérer les messages de la conversation (si nécessaire)
    $messages = $this->messageRepository->findMessageByConversationId($conversation->getId());

    return $this->render('conversation/conversation_with_group.html.twig', [
        'conversation' => $conversation,
        'messages' => $messages,
        'user' => $this->getUser(),
        'participants'=>$participants // Passer l'utilisateur connecté au template
    ]);
}

#[Route('/group-conversations', name: 'group_conversations')]
public function groupConversations(ConversationRepository $conversationRepository): Response
{
    $userId = $this->getUser()->getId();
    
    // Récupérer les conversations de groupe de l'utilisateur connecté
    $groupConversations = $conversationRepository->findGroupConversationsByUser($userId);

    return $this->render('conversation/group_conversations.html.twig', [
        'groupConversations' => $groupConversations,
    ]);
}


#[Route('/groupe/{conversationId}', name: 'appa')]
public function groupe(Request $request, EntityManagerInterface $entityManager, UtilisateurRepository $utilisateurRepository, $conversationId): Response
{
    // Récupérer la conversation
    $conversation = $entityManager->getRepository(Conversation::class)->find($conversationId);

    // Vérifier si la conversation existe
    if (!$conversation) {
        throw $this->createNotFoundException('La conversation n\'existe pas');
    }

    // Récupérer la liste des utilisateurs
    $utilisateurs = $utilisateurRepository->createQueryBuilder('u')
        ->andWhere('u != :user')
        ->setParameter('user', $this->getUser())
        ->getQuery()
        ->getResult();

    return $this->render('utilisateur/ajouter_participant_conversation.html.twig', [
        'utilisateurs' => $utilisateurs,
        'conversation' => $conversation,
        'conversationId' => $conversationId,
    ]);
}

#[Route('/conversation/{conversationId}/add-participants', name: 'add_participants_to_conversation', methods: ['POST'])]
public function addParticipantsToConversation(Request $request, EntityManagerInterface $entityManager, $conversationId): Response
{
    // Récupérer la conversation
    $conversation = $entityManager->getRepository(Conversation::class)->find($conversationId);

    // Vérifier si la conversation existe
    if (!$conversation) {
        throw $this->createNotFoundException('La conversation n\'existe pas');
    }

    // Récupérer les identifiants des participants cochés depuis le formulaire
    $participantsIds = $request->request->get('participants');

    // Vérifier si des participants ont été sélectionnés
    if (!$participantsIds || !is_array($participantsIds)) {
        $this->addFlash('error', 'Veuillez sélectionner au moins un participant.');
        return $this->redirectToRoute('appa', ['conversationId' => $conversationId]);
    }

    // Parcourir les identifiants des participants sélectionnés
    foreach ($participantsIds as $participantId) {
        // Récupérer l'utilisateur à partir de son identifiant
        $utilisateur = $entityManager->getRepository(Utilisateur::class)->find($participantId);

        // Vérifier si l'utilisateur existe
        if (!$utilisateur) {
            continue; // Ignorer cet utilisateur et passer au suivant
        }

        // Vérifier si l'utilisateur est déjà participant à cette conversation
        $participant = $entityManager->getRepository(ParticipantChat::class)->findOneBy([
            'utilisateur' => $utilisateur,
            'conversation' => $conversation
        ]);

        if ($participant) {
            continue; // Ignorer cet utilisateur s'il est déjà participant
        }

        // Créer une nouvelle entité Participant
        $participant = new ParticipantChat();
        $participant->setUtilisateur($utilisateur);
        $participant->setConversation($conversation);

        // Persist et flush
        $entityManager->persist($participant);
    }

    // Flush pour enregistrer les modifications dans la base de données
    $entityManager->flush();

    // Rediriger vers la conversation avec les nouveaux participants
    return $this->redirectToRoute('conversation_with_group', ['conversationId' => $conversationId]);
}


#[Route('/zoom', name: 'zoom_view')]
public function zoomView(): Response
{
    return $this->render('conversation/zoom.html.twig');
}

#[Route('/choix-conversation', name: 'choix_conversation')]
public function choixConversation(Request $request): Response
{ 
    return $this->render('conversation/choix-conversation.html.twig');
}

#[Route('/conversation/{conversationId}/leave', name: 'leave_group_conversation', methods: ['POST'])]
public function leaveGroupConversation(Request $request, EntityManagerInterface $entityManager, $conversationId): Response
{
    // Récupérer la conversation
    $conversation = $entityManager->getRepository(Conversation::class)->find($conversationId);

    // Vérifier si la conversation existe
    if (!$conversation) {
        throw $this->createNotFoundException('La conversation n\'existe pas');
    }

    // Récupérer l'utilisateur actuel
    $currentUser = $this->getUser();

    // Vérifier si l'utilisateur est un participant de la conversation
    $participant = $entityManager->getRepository(ParticipantChat::class)->findOneBy([
        'utilisateur' => $currentUser,
        'conversation' => $conversation
    ]);

    // Si l'utilisateur n'est pas un participant, afficher une erreur
    if (!$participant) {
        $this->addFlash('error', 'Vous n\'êtes pas un participant de cette conversation.');
        return $this->redirectToRoute('group_conversations');
    }

    // Supprimer l'utilisateur comme participant de la conversation
    $entityManager->remove($participant);
    $entityManager->flush();

    // Rediriger vers la liste des conversations de groupe
    $this->addFlash('success', 'Vous avez quitté la conversation avec succès.');
    return $this->redirectToRoute('group_conversations');
}

}
