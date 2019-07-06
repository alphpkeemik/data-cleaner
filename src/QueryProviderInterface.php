<?php declare(strict_types=1);

/*
 * This file is part of the Ambientia DataCleaner package.
 *
 * (c) Ambientia Estonia OÜ
 */

namespace Ambientia\DataCleaner;

use Doctrine\ORM\AbstractQuery;

/**
 * @author mati.andreas@ambientia.ee
 */
interface QueryProviderInterface
{
    public function getQuery(): AbstractQuery;

}