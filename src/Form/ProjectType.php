<?php

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('shortDescription')
            ->add('title')
            ->add('content')
            ->add('description')
            ->add('coverFile', VichImageType::class, [
                'required' => false,
            ])
            ->add('stack')
            ->add('team')
            ->add('githubOwner')
            ->add('githubRepository')
            ->add('githubDisplayed')
            ->add('url')
            ->add('sort')
            ->add('enabled')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
