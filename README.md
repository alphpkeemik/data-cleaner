# Data cleaner

PHP library for Purging old data from database

## Creating provider
```
<?php
namespace App\Module;

use Ambientia\DataCleaner\QueryProviderInterface;
use DateTime;
use Doctrine\Persistence\ManagerRegistry;
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
## Add cron
` * * * * * ambientia:data-cleaner >> /path/to/log/file 2>&1`

## Running code fixer

Run php cs fixer `./vendor/bin/php-cs-fixer fix`

## Running the tests

Run tests with phpunit `./vendor/bin/phpunit`

## Running analyzer

Run phan `./vendor/bin/phan`