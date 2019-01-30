<?php

declare(strict_types=1);

namespace EasternColor\JsonTransBundle\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Convert a value into a json string to be stored into the persistency layer.
 */
class JsonTransType extends Type
{
    public const JSON = 'json_trans';

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return json_decode((string) $value, true);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return json_encode($value);
    }

    public function getName()
    {
        return self::JSON;
    }

    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
