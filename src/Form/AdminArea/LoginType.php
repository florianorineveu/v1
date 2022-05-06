<?php

namespace App\Form\AdminArea;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
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
            ->add('_username', EmailType::class, [
                'label'      => 'security.login.form.username',
                'required'   => true,
                'attr'       => [
                    'autofocus' => true,
                    'placeholder' => 'security.login.form.username',
                ],
            ])
            ->add('_password', PasswordType::class, [
                'label'    => 'security.login.form.password',
                'required' => true,
                'attr'       => [
                    'placeholder' => 'security.login.form.password',
                ],
            ])
            ->add('_remember_me', CheckboxType::class, [
                'label'    => 'security.login.form.remember_me',
                'required' => false,
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
            'csrf_token_id'      => 'authenticate',
            'csrf_field_name'    => '_csrf_token',
            'translation_domain' => 'admin',
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
