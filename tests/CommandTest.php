<?php declare(strict_types=1);

/*
 * This file is part of the Ambientia DataCleaner package.
 */

namespace Ambientia\DataCleaner\Tests;


use Ambientia\DataCleaner\DataCleaner;
use Ambientia\DataCleaner\DataCleanerCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CommandTest extends TestCase
{


    public function testTest()
    {
        $dataCleaner = $this->createMock(DataCleaner::class);

        $dataCleaner
            ->expects($this->once())
            ->method('execute');

        $command = new DataCleanerCommand();
        $command->setDataCleaner($dataCleaner);
        $inputInterface = $this->createMock(InputInterface::class);
        $outputInterface = $this->createMock(OutputInterface::class);
        $command->run($inputInterface, $outputInterface);

    }
}
