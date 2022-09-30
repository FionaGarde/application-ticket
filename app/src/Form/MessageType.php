<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MessageType extends AbstractType
{
    private TokenStorageInterface $token;
    private RequestStack $requestStack;
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager, TokenStorageInterface $token, RequestStack $requestStack)
    {
        $this->token = $token;
        $this->requestStack = $requestStack;
        $this->entityManager = $entityManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentRequest = $this->requestStack->getCurrentRequest();

        $builder
            ->add('content')
            ->add('receiver', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (EntityRepository $entityRepository) {

                    // On ne veut pas afficher dans la liste l'utilisateur qui est connecté

                    $connectedUser = $this->token->getToken()->getUser();

                    return $entityRepository->createQueryBuilder('u')
                        ->where('u.mail != :mail')
                        ->setParameter('mail', $connectedUser->getMail());
                }
            ])

            // ici on veut enlever le champ receiver si on est en train de modifier un message
            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $formEvent) use ($currentRequest) {

                $form = $formEvent->getForm();
                $message = $formEvent->getData();

                if (!$message->getId()) {

                    // Ici, on veut présélectionner l'utilisateur sur lequel l'élève a cliqué sur sa page de sélection
                    if ($currentRequest->query->get('id')) {
                        $form->remove('receiver');
                        $form->add('receiver', EntityType::class, [
                            'class' => User::class,
                            'query_builder' => function (EntityRepository $entityRepository) {

                                $connectedUser = $this->token->getToken()->getUser();

                                return $entityRepository->createQueryBuilder('u')
                                    ->where('u.mail != :mail')
                                    ->setParameter('mail', $connectedUser->getMail());
                            },
                            'data' => $this->entityManager->getRepository(User::class)->find($currentRequest->query->get('id')),
                        ]);
                    }
                    return;
                }

                $form->remove('receiver');
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
