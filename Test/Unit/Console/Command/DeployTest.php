<?php

/**
 * Umc_Crud extension
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category  Umc
 * @package   Umc_Crud
 * @copyright 2020 Marius Strajeru
 * @license   http://opensource.org/licenses/mit-license.php MIT License
 * @author    Marius Strajeru
 */

namespace Umc\Crud\Test\Unit\Console\Command;

use Magento\Framework\Filesystem\DirectoryList;
use Magento\Framework\Filesystem\Io\File;
use Magento\Framework\Module\Dir\Reader;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Umc\Crud\Console\Command\Deploy;

class DeployTest extends TestCase
{
    /**
     * @var Reader | MockObject
     */
    private $reader;
    /**
     * @var File | MockObject
     */
    private $ioFile;
    /**
     * @var DirectoryList | MockObject
     */
    private $directoryList;
    /**
     * @var InputInterface | MockObject
     */
    private $input;
    /**
     * @var OutputInterface | MockObject
     */
    private $output;
    /**
     * @var Deploy
     */
    private $deploy;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->reader = $this->createMock(Reader::class);
        $this->ioFile = $this->createMock(File::class);
        $this->directoryList = $this->createMock(DirectoryList::class);
        $this->input = $this->createMock(InputInterface::class);
        $this->output = $this->createMock(OutputInterface::class);
        $this->deploy = new Deploy(
            $this->reader,
            $this->ioFile,
            $this->directoryList
        );
    }

    /**
     * @covers \Umc\Crud\Console\Command\Deploy
     */
    public function testRun()
    {
        $this->ioFile->method('fileExists')->willReturn(false);
        $this->input->method('getOption')->willReturn(false);
        $this->output->method('isVerbose')->willReturn(false);
        $this->output->expects($this->never())->method('writeln');
        $this->ioFile->expects($this->once())->method('checkAndCreateFolder');
        $this->ioFile->expects($this->once())->method('cp');
        $this->deploy->run($this->input, $this->output);
    }

    /**
     * @covers \Umc\Crud\Console\Command\Deploy
     */
    public function testRunVerbose()
    {
        $this->ioFile->method('fileExists')->willReturn(false);
        $this->input->method('getOption')->willReturn(false);
        $this->output->method('isVerbose')->willReturn(true);
        $this->output->expects($this->once())->method('writeln');
        $this->ioFile->expects($this->once())->method('checkAndCreateFolder');
        $this->ioFile->expects($this->once())->method('cp');
        $this->deploy->run($this->input, $this->output);
    }

    /**
     * @covers \Umc\Crud\Console\Command\Deploy
     */
    public function testRunVFileExists()
    {
        $this->ioFile->method('fileExists')->willReturn(true);
        $this->input->method('getOption')->willReturn(false);
        $this->output->method('isVerbose')->willReturn(false);
        $this->output->expects($this->once())->method('writeln');
        $this->ioFile->expects($this->never())->method('checkAndCreateFolder');
        $this->ioFile->expects($this->never())->method('cp');
        $this->deploy->run($this->input, $this->output);
    }

    /**
     * @covers \Umc\Crud\Console\Command\Deploy
     */
    public function testRunVFileExistsForce()
    {
        $this->ioFile->method('fileExists')->willReturn(true);
        $this->input->method('getOption')->willReturn(true);
        $this->output->method('isVerbose')->willReturn(false);
        $this->output->expects($this->never())->method('writeln');
        $this->ioFile->expects($this->once())->method('checkAndCreateFolder');
        $this->ioFile->expects($this->once())->method('cp');
        $this->deploy->run($this->input, $this->output);
    }
}
