<?php declare(strict_types=1);

/*
 * This file is part of the Ambientia DataCleaner package.
 */

namespace Ambientia\DataCleaner;

use Symfony\Component\Serializer\Normalizer\ObjectNormalizer as SymfonyObjectNormalizer;

/**
 * @author mati.andreas@ambientia.ee
 * @internal
 */
class ObjectNormalizer extends SymfonyObjectNormalizer
{
    protected function isAllowedAttribute($classOrObject, $attribute, $format = null, array $context = [])
    {

        if (!key_exists(__CLASS__, $context)) {
            return parent::isAllowedAttribute($classOrObject, $attribute, $format, $context);
        }
        if (preg_match('/^__.+__$/', $attribute)) {
            return false;
        }
        if ($context[__CLASS__] !== $classOrObject) {
            return false;
        }


        return parent::isAllowedAttribute($classOrObject, $attribute, $format, $context);
    }
}