<?php

namespace App\Form;

use App\Entity\ShortenUrl;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ShortenUrlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if ($options['full'] && $options['full']) {
            $builder
                ->add('name', TextType::class, [
                    'required' => false,
                ])
                ->add('enabled'/*, ChoiceType::class, [
                    'required' => false,
                ]*/)
                ->add('save', SubmitType::class)
            ;
        }

        $builder
            ->add('url', UrlType::class, [
                'required' => true,
                'attr' => [
                    'placeholder' => 'URL à raccourcir',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ShortenUrl::class,
            'full'       => false,
        ]);
    }
}
