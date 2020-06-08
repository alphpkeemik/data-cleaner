<?php declare(strict_types=1);

/*
 * This file is part of the Ambientia DataCleaner package.
 */

namespace Ambientia\DataCleaner;

use Traversable;

/**
 * @author mati.andreas@ambientia.ee
 */
interface QueryProviderInterface
{
    public function getItems(): Traversable;

}