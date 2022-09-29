<?php

namespace App\Form;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class MessageType extends AbstractType
{
    private TokenStorageInterface $token;

    public function __construct(TokenStorageInterface $token)
    {
        $this->token = $token;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
