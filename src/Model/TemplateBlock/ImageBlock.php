<?php

namespace App\Model\TemplateBlock;

use Symfony\Component\Validator\Constraints as Assert;

class ImageBlock implements ConfigurationInterface
{
    #[Assert\NotBlank]
    public string $src;

    #[Assert\NotBlank]
    public string $alt;

    public string $caption;
}