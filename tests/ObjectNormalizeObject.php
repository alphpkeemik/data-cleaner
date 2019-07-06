<?php
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