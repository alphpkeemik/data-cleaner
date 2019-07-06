<?php declare(strict_types=1);

/*
 * This file is part of the Ambientia DataCleaner package.
 *
 * (c) Ambientia Estonia OÜ
 */

namespace Ambientia\DataCleaner\Tests;

/**
 * @author mati.andreas@ambientia.ee
 */
class ObjectNormalizeObject
{
    public $scalar = 'test';
    public $__skip__ = false;

    public $sub;

    public function __construct()
    {
        $this->sub = new ObjectNormalizeOther();
    }
}