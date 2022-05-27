<?php

namespace App\Form\AdminArea;

use App\Entity\TemplateBlock;
use App\Form\AdminArea\Block\ImageType;
use App\Form\AdminArea\Block\TextType;
use App\Model\TemplateBlock\ImageBlock;
use App\Model\TemplateBlock\TextBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateBlockType extends AbstractType
{
    const CONFIGURATION_TYPES = [
        TextBlock::class  => TextType::class,
        ImageBlock::class => ImageType::class,
    ];

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('type', ChoiceType::class, [
                'required' => true,
                'choices'  => [
                    'Texte' => TextBlock::class,
                    'Image' => ImageBlock::class,
                ],
                'attr'     => [
                    'class' => 'js-trigger-type',
                ],
            ])
            ->add('position', HiddenType::class)
        ;

        $formModifier = function (FormInterface $form, TemplateBlock $block) {
            $wantedConfiguration = $block->getType();
            $currentConfiguration = $block->getConfiguration();

            if (!$currentConfiguration instanceof $wantedConfiguration) {
                $block->setConfiguration(new $wantedConfiguration);
            }

            $form->add('configuration', self::CONFIGURATION_TYPES[$wantedConfiguration], [
                'label' => false,
            ]);
        };

        $builder
            ->addEventListener(
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $formModifier($event->getForm(), $event->getData());
                }
            )
            ->get('type')->addEventListener(
                FormEvents::POST_SUBMIT,
                function (FormEvent $event) use ($formModifier) {
                    $formModifier($event->getForm()->getParent(), $event->getForm()->getParent()->getData());
                }
            )
        ;

        /*$builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($builder) {
            $block = $event->getData();

            if ($block) {
                $blockConfiguration = $block->getConfiguration();

                switch (true) {
                    case $blockConfiguration instanceof TextBlock:
                        $event->getForm()->add('configuration', TextType::class);
                        break;
                    case $blockConfiguration instanceof ImageBlock:
                        $event->getForm()->add('configuration', ImageType::class);
                        break;
                }
            }
        });*/
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TemplateBlock::class,
        ]);
    }
}