<?php

namespace App\Shared\Infrastructure;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;

abstract class UuidType extends Type
{
    abstract protected function getValueObjectClassName(): string;

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        return $platform->getGuidTypeDeclarationSQL($fieldDeclaration);
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        $className = $this->getValueObjectClassName();

        if ($value === null || $value instanceof $className) {
            return $value;
        }

        try {
            return new $className($value);
        } catch (\Exception $exception) {
            throw ConversionException::conversionFailed($value, $this->getName());
        }
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        $className = $this->getValueObjectClassName();

        if ($value === null || $value instanceof $className) {
            return (string)$value;
        }

        throw ConversionException::conversionFailed($value, $this->getName());
    }
}