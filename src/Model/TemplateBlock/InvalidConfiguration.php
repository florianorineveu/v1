<?php

namespace App\Model\TemplateBlock;

class InvalidConfiguration implements ConfigurationInterface
{
    public function __construct(public array $previousConfiguration)
    {
    }

    public static function getType(): string
    {
        return 'invalid';
    }

    public function update(array $data = []): void
    {
    }
}