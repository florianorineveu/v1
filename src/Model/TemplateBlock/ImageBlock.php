<?php

namespace App\Model\TemplateBlock;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[Vich\Uploadable]
class ImageBlock implements ConfigurationInterface
{
    public ?string $imagePath;

    /*#[Assert\File(maxSize: '1M', mimeTypes: ['image/png', 'image/jpg', 'image/jpeg', 'image/gif'], maxSizeMessage: 'Maximum size allowed: {{ limit }} {{ suffix }}', mimeTypesMessage: 'Allowed formats: png, jp(e)g, gif.')]
    #[Vich\UploadableField(mapping: 'block_images', fileNameProperty: 'imagePath')]*/
    //public ?File $imageFile;

    #[Assert\NotBlank]
    public string $alt;

    public string $caption;
}