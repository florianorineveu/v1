<?php

namespace App\Form\AdminArea;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label'    => 'Nom',
                'required' => true,
            ])
            ->add('title', TextType::class, [
                'label'    => 'Titre',
                'required' => true,
            ])
            ->add('enabled', CheckboxType::class, [
                'label'    => 'Activé',
                'required' => false,
            ])
            ->add('blocks', CollectionType::class, [
                'label'         => 'Blocs',
                'entry_type'    => TemplateBlockType::class,
                //'allow_add'     => true,
                //'allow_delete'  => true,
                'required'      => false,
                //'by_reference'  => false,
                'entry_options' => ['label' => false],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
