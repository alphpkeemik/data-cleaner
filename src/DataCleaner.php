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
        $query = $provider->getQuery();
        foreach ($query->iterate() as $raw) {
            $this->processItem($raw);
        }
    }

    private function processItem(array $raw): void
    {
        $item = current($raw);
        $class = get_class($item);
        $em = $this->doctrine->getManagerForClass($class);
        $n = $this->normalizer->normalize($item, null, [
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