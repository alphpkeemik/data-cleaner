# Data cleaner

PHP library for Purging old data from database

## Configuring in Symfony
Minimal configuration in Symfony 4:
```
services:
    # this config only applies to the services created by this file
    _instanceof:
        # services whose classes are instances of CustomInterface will be tagged automatically
        Ambientia\DataCleaner\QueryProviderInterface:
            tags: ['ambientia.data_cleaner_provider']


   Ambientia\DataCleaner\DataCleaner:
        # inject all services tagged with app.handler as first argument
        arguments:
            - '@doctrine'
            - '@serializer'
            - !tagged ambientia.data_cleaner_provider
            - '@logger'
        tags:
            - { name: monolog.logger, channel: data-cleaner}
            
   Ambientia\DataCleaner\DataCleanerCommand:
        tags: ['console.command']

monolog:
    handlers:
        data-cleaner:
            type:  rotating_file
            path:  "%kernel.logs_dir%/data-cleaner.log"
            channels: data-cleaner       
        
```        
Read more from https://symfony.com/doc/current/service_container/tags.html
## Creating provider
```
<?php
namespace App\Module;

use Ambientia\DataCleaner\QueryProviderInterface;
use DateTime;
use Doctrine\Common\Persistence\ManagerRegistry;
use Traversable;

class DataCleanerQueryProvider implements QueryProviderInterface
{

    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function getItems(): Traversable
    {
        $qb = $this->doctrine->getManager()
            ->getRepository(Entity::class)
            ->createQueryBuilder('m');
        $qb->setParameter('date', new DateTime('-3 months'));
        $qb->andWhere($$qb->expr()->lte('m.ended', ':date'));

        foreach ($qb->getQuery()->iterate() as $item) {
            yield current($item);
        }
    }
}
```

## Running code fixer

Run php cs fixer `./vendor/bin/php-cs-fixer fix`

## Running the tests

Run tests with phpunit `./vendor/bin/phpunit`

## Running analyzer

Run phan `./vendor/bin/phan`