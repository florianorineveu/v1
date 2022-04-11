<?php

namespace App\Form\AdminArea;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginType extends AbstractType
{
    public function __construct(
        private AuthenticationUtils $authenticationUtils,
    ) {}

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('_username', TextType::class, [
                'label'    => 'Identifiant',
                'required' => true,
            ])
            ->add('_password', PasswordType::class, [
                'label'    => 'Mot de passe',
                'required' => true,
            ])
        ;

        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $formEvent) {
            if ($error = $this->authenticationUtils->getLastAuthenticationError()) {
                $formEvent->getForm()->addError(new FormError($error->getMessageKey(), messageParameters: $error->getMessageData()));
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_token_id'   => 'authenticate',
            'csrf_field_name' => '_csrf_token'
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
