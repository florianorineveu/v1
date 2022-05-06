<?php

namespace App\Form\AdminArea;

use App\Entity\TemplateBlock;
use App\Form\AdminArea\Block\TextType;
use App\Model\TemplateBlock\TextBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('position')
        ;

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($builder) {
            $block = $event->getData();

            if ($block) {
                $blockConfiguration = $block->getConfiguration();

                switch (true) {
                    case $blockConfiguration instanceof TextBlock:
                        $event->getForm()->add('configuration', TextType::class);
                        break;
                }
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TemplateBlock::class,
        ]);
    }
}