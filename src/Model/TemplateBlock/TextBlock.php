<?php

namespace App\Model\TemplateBlock;

use Symfony\Component\Validator\Constraints as Assert;

class TextBlock implements ConfigurationInterface
{
    #[Assert\NotBlank]
    public string $content;
}