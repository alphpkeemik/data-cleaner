<?php declare(strict_types=1);

/*
 * This file is part of the Ambientia DataCleaner package.
 */

namespace Ambientia\DataCleaner;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @author mati.andreas@ambientia.ee
 */
class DataCleanerCommand extends Command
{
    /**
     * @var DataCleaner
     */
    private $dataCleaner;

    /**
     * @param DataCleaner $dataCleaner
     *
     * @required
     */
    public function setDataCleaner($dataCleaner)
    {
        $this->dataCleaner = $dataCleaner;
    }

    protected function configure()
    {
        parent::configure();
        $this->setName('ambientia:data-cleaner');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->dataCleaner->execute();
        return 0;
    }
}