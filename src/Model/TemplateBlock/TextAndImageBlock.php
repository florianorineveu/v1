<?php

namespace App\Model\TemplateBlock;

use Symfony\Component\Validator\Constraints as Assert;

class TextAndImageBlock implements ConfigurationInterface
{
    #[Assert\NotBlank]
    #[Assert\Valid]
    public $text;

    #[Assert\NotBlank]
    #[Assert\Image]
    public $image;

    public function __construct()
    {
        $this->text  = new TextBlock();
        $this->image = new ImageBlock();
    }
}