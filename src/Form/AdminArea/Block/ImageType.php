<?php

namespace App\Form\AdminArea\Block;

use App\Model\TemplateBlock\ImageBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Vich\UploaderBundle\Form\Type\VichImageType;

class ImageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            /*->add('imageFile', VichImageType::class, [
                'label'    => 'Image',
                'required' => false,
            ])*/
            ->add('imageFile', FileType::class, [
                'label'       => 'Image',
                'required'    => false,
                'mapped'      => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1M',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                        ],
                        'maxSizeMessage' => 'Maximum size allowed: {{ limit }} {{ suffix }}',
                        'mimeTypesMessage' => 'Allowed formats: png, jpg, jpeg.',
                    ])
                ]
            ])
            ->add('alt', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'label'    => 'Alt',
                'required' => false,
            ])
            ->add('caption', \Symfony\Component\Form\Extension\Core\Type\TextType::class, [
                'label'    => 'Légende',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ImageBlock::class,
        ]);
    }
}