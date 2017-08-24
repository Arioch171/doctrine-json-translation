<?php

namespace Erichard\DoctrineJsonTranslation;

use Doctrine\DBAL\Platforms\AbstractPlatform;

class TranslatedType extends JsonbArrayType
{
    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return 'JSONB';
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value === '') {
            return array();
        }

        $value = (is_resource($value)) ? stream_get_contents($value) : $value;

        $value = json_decode($value, true);

        return new TranslatedField($value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        $json = json_encode($value->all());

        return $json;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'translated';
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return !$platform->hasNativeJsonType();
    }
}