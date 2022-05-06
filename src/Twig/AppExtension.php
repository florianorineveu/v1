<?php

namespace App\Twig;

use App\Entity\TemplateBlock;
use App\Model\TemplateBlock\ImageBlock;
use App\Model\TemplateBlock\InvalidConfiguration;
use App\Model\TemplateBlock\TextAndImageBlock;
use App\Model\TemplateBlock\TextBlock;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use function Symfony\Component\String\u;

class AppExtension extends AbstractExtension
{
    public function __construct(private Environment $twig)
    {
    }

    public function getFilters(): array
    {
        return [
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('render_block', [$this, 'renderBlock'], ['is_safe' => ['html']]),
        ];
    }

    public function getFunctions(): array
    {
        return [];
    }

    public function renderBlock(TemplateBlock $block)
    {
        if ($block->getConfiguration() instanceof InvalidConfiguration) {
            return $this->twig->render('_include/block/invalid.html.twig', ['block' => $block]);
        }

        $configurationName = (new \ReflectionClass($block->getConfiguration()))->getShortName();
        $explodedName = explode('_', u($configurationName)->snake());
        array_pop($explodedName);

        return $this->twig->render('_include/block/' . implode('_', $explodedName) . '.html.twig', [
            'block' => $block,
        ]);
    }
}
