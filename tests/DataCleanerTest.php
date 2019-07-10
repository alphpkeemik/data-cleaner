<?php declare(strict_types=1);

/*
 * This file is part of the Ambientia DataCleaner package.
 *
 * (c) Ambientia Estonia OÜ
 */

namespace Ambientia\DataCleaner\Tests;


use Ambientia\DataCleaner\DataCleaner;
use Ambientia\DataCleaner\ObjectNormalizer;
use Ambientia\DataCleaner\QueryProviderInterface;
use ArrayIterator;
use ArrayObject;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Persistence\ObjectManager;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use stdClass;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class DataCleanerTest extends TestCase
{
    use StackMockTrait;

    public function testObjectManagerStack()
    {
        $callStack = new ArrayObject();
        $omClass = ObjectManager::class;
        $objectManager = $this->createStackMock($callStack, $omClass);

        $doctrine = $this->createDoctrine($objectManager);

        $cleaner = $this->crateCleaner($doctrine);
        $cleaner->execute();
        $this->assertSame(
            [
                "$omClass:remove",
                "$omClass:flush",
                "$omClass:clear"
            ],
            $callStack->getArrayCopy()
        );
    }

    public function testObjectManagerCalls()
    {

        $doctrine = $this->createMock(ManagerRegistry::class);
        $objectManager = $this->createMock(ObjectManager::class);
        $object = new class extends stdClass
        {
        };
        $providers = [$this->createProvider($object)];

        $doctrine
            ->expects($this->once())
            ->method('getManagerForClass')
            ->with(get_class($object))
            ->willReturn($objectManager);
        $objectManager
            ->expects($this->once())
            ->method('remove');

        $cleaner = $this->crateCleaner($doctrine, null, $providers);
        $cleaner->execute();

    }

    public function testNormalizer()
    {
        $object = new stdClass();

        $normalizer = $this->createMock(NormalizerInterface::class);
        $providers = [$this->createProvider($object)];

        $normalizer
            ->expects($this->once())
            ->method('normalize')
            ->with(
                $object,
                '',
                [
                    ObjectNormalizer::class => $object
                ]
            );

        $cleaner = $this->crateCleaner(null, $normalizer, $providers);
        $cleaner->execute();

    }

    public function testLogger()
    {
        $object = new class extends stdClass
        {
        };
        $array = [
            'value' => uniqid()
        ];
        $normalizer = $this->createConfiguredMock(
            NormalizerInterface::class,
            [
                'normalize' => $array
            ]
        );
        $providers = [$this->createProvider($object)];
        $logger = $this->createMock(LoggerInterface::class);

        $logger
            ->expects($this->once())
            ->method('info')
            ->with(
                'item removed', [
                    'class' => get_class($object),
                    'item' => $array,
                ]
            );

        $cleaner = $this->crateCleaner(null, $normalizer, $providers, $logger);
        $cleaner->execute();

    }

    private function crateCleaner(
        ManagerRegistry $doctrine = null,
        NormalizerInterface $normalizer = null,
        array $providers = null,
        LoggerInterface $logger = null
    ): DataCleaner {
        if (!$doctrine) {
            $doctrine = $this->createDoctrine();
        }
        if (!$normalizer) {
            $normalizer = $this->createMock(NormalizerInterface::class);
        }
        if ($providers === null) {
            $providers = [$this->createProvider()];
        }
        if (!$logger) {
            $logger = $this->createMock(LoggerInterface::class);
        }
        $cleaner = new DataCleaner(
            $doctrine,
            $normalizer,
            $providers,
            $logger
        );

        return $cleaner;
    }

    private function createDoctrine(ObjectManager $objectManager = null)
    {
        if (!$objectManager) {
            $objectManager = $this->createMock(ObjectManager::class);
        }
        $doctrine = $this->createConfiguredMock(
            ManagerRegistry::class,
            [
                'getManagerForClass' => $objectManager
            ]
        );

        return $doctrine;
    }

    private function createProvider(stdClass $object = null)
    {

        if (!$object) {
            $object = new stdClass();
        }
        $provider = $this->createConfiguredMock(
            QueryProviderInterface::class,
            [
                'getItems' => new ArrayIterator([$object])
            ]
        );

        return $provider;
    }


}
