<?php

namespace App\Form\AdminArea;

use App\Entity\Page;
use App\Repository\PageRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PageType extends AbstractType
{
    private $templateDir;

    public function __construct(KernelInterface $kernel)
    {
        $this->templateDir = $kernel->getProjectDir() . '/templates/page/';
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $templateChoices = [];
        $templateFinder  = (new Finder())->in($this->templateDir)->depth(0)->files();

        foreach ($templateFinder as $file) {
            $templateChoices[$file->getFilename()] = $file->getFilename();
        }

        $currentPage = $builder->getData();

        $builder
            ->add('name', TextType::class, [
                'label'    => 'Nom',
                'required' => true,
            ])
            ->add('type', ChoiceType::class, [
                'label'    => 'Type',
                'required' => true,
                'choices'  => Page::TYPES,
            ])
            ->add('template', ChoiceType::class, [
                'label'    => 'Template',
                'required' => true,
                'choices'  => $templateChoices,
            ])
            ->add('parent', EntityType::class, [
                'label'         => 'Page parent',
                'required'      => false,
                'multiple'      => false,
                'class'         => Page::class,
                'choice_label'  => 'name',
                'query_builder' => function (PageRepository $pageRepository) use ($currentPage) {
                    $qb = $pageRepository->createQueryBuilder('p');

                    if ($currentPage->getId()) {
                        $currentAndChildren = [$currentPage->getId()?->toBinary()];

                        foreach ($currentPage->getChildren() as $child) {
                            $currentAndChildren[] = $child->getId()->toBinary();
                        }

                        $qb
                            ->andWhere('p NOT IN (:current_and_children)')
                            ->setParameters([
                                'current_and_children' => $currentAndChildren,
                            ])
                        ;
                    }

                    return $qb;
                },
            ])
            ->add('home', CheckboxType::class, [
                'label'    => 'Page d\'accueil',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
        ]);
    }
}
