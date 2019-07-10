<?php declare(strict_types=1);

/*
 * This file is part of the Ambientia DataCleaner package.
 *
 * (c) Ambientia Estonia OÜ
 */

namespace Ambientia\DataCleaner;

use Doctrine\Common\Persistence\ManagerRegistry;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * @author mati.andreas@ambientia.ee
 */
class DataCleaner
{
    /**
     *
     * @var ManagerRegistry
     */
    private $doctrine;

    /**
     * @var NormalizerInterface
     */
    private $normalizer;

    /**
     * @var array|QueryProviderInterface[]
     */
    private $providers = [];

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(ManagerRegistry $doctrine, NormalizerInterface $normalizer, $providers, LoggerInterface $logger)
    {
        $this->doctrine = $doctrine;
        $this->normalizer = $normalizer;
        $this->providers = $providers;
        $this->logger = $logger;
    }

    public function execute(): void
    {
        foreach ($this->providers as $provider) {
            $this->processProvider($provider);
        }
    }

    private function processProvider(QueryProviderInterface $provider): void
    {

        foreach ($provider->getItems() as $raw) {
            $this->processItem($raw);
        }
    }

    private function processItem($item): void
    {
        $class = get_class($item);
        $em = $this->doctrine->getManagerForClass($class);
        $n = $this->normalizer->normalize($item, '', [
            ObjectNormalizer::class => $item
        ]);
        $em->remove($item);
        $em->flush();
        $em->clear();
        $this->logger->info('item removed', [
            'class' => $class,
            'item' => $n,
        ]);
    }
}