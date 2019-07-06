<?php declare(strict_types=1);

/*
 * This file is part of the Ambientia DataCleaner package.
 *
 * (c) Ambientia Estonia OÜ
 */

namespace Ambientia\DataCleaner\Tests;


use Ambientia\DataCleaner\ObjectNormalizer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Serializer\Serializer;

class ObjectNormalizerTest extends TestCase
{
    public function testTest()
    {


        $normalizer = new ObjectNormalizer();
        $normalizer->setSerializer(new Serializer([
            $normalizer
        ]));
        $item = new ObjectNormalizeObject;
        $actual = $normalizer->normalize($item, null, [
            ObjectNormalizer::class => $item
        ]);

        $expected = [
            'scalar' => 'test',
            'sub' => []
        ];
        $this->assertSame($expected, $actual);

    }
}
