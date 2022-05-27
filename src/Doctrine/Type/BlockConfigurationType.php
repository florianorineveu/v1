<?php

namespace App\Doctrine\Type;

use App\Model\TemplateBlock\ConfigurationInterface;
use App\Model\TemplateBlock\ImageBlock;
use App\Model\TemplateBlock\InvalidConfiguration;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonType;
use Symfony\Component\Serializer\Exception\NotNormalizableValueException;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class BlockConfigurationType extends JsonType
{
    private static NormalizerInterface & DenormalizerInterface $serializer;

    public static function setSerializer(NormalizerInterface & DenormalizerInterface $serializer)
    {
        self::$serializer = $serializer;
    }

    public function convertToDatabaseValue($configuration, AbstractPlatform $platform)
    {
        if ($configuration instanceof ImageBlock) {
            //dump($configuration);
        }
        $rawConfiguration = self::$serializer->normalize($configuration);
        if ($configuration instanceof ImageBlock) {
            //dump($rawConfiguration);
            //die();
        }
        return parent::convertToDatabaseValue($rawConfiguration, $platform);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform): ?ConfigurationInterface
    {
        $rawConfiguration = parent::convertToPHPValue($value, $platform);

        if (null === $rawConfiguration) {
            return null;
        }

        try {
            return self::$serializer->denormalize($rawConfiguration, ConfigurationInterface::class);
        } catch (NotNormalizableValueException) {
            return new InvalidConfiguration($rawConfiguration);
        }
    }

    public function getName(): string
    {
        return 'block_configuration';
    }
}