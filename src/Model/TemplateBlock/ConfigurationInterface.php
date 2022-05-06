<?php

namespace App\Model\TemplateBlock;

use Symfony\Component\Serializer\Annotation\DiscriminatorMap;

#[DiscriminatorMap(typeProperty: 'type', mapping: [
    'text' => TextBlock::class
])]
interface ConfigurationInterface
{
}