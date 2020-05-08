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

declare(strict_types=1);

namespace Umc\Crud\Test\Unit\Model;

use Magento\Framework\Filesystem\Io\File;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Model\FileChecker;

class FileCheckerTest extends TestCase
{
    /**
     * @var File | MockObject
     */
    private $file;
    /**
     * @var FileChecker
     */
    private $fileChecker;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->file = $this->createMock(File::class);
        $this->fileChecker = new FileChecker(
            $this->file
        );
    }

    /**
     * @covers \Umc\Crud\Model\FileChecker::getNewFileName
     * @covers \Umc\Crud\Model\FileChecker::__construct
     */
    public function testGetNewFileName()
    {
        $this->file->method('getPathInfo')->willReturn([
            'filename' => 'file',
            'extension' => 'ext',
            'basename' => 'file.ext',
            'dirname' => 'dir'
        ]);
        $this->file->method('fileExists')->willReturn(false);
        $this->assertEquals('file.ext', $this->fileChecker->getNewFileName('file', 0));
        $this->assertEquals('/f/i/file.ext', $this->fileChecker->getNewFileName('file'));
        $this->assertEquals('/f/i/l/e/_/_/file.ext', $this->fileChecker->getNewFileName('file', 6));
    }

    /**
     * @covers \Umc\Crud\Model\FileChecker::getNewFileName
     * @covers \Umc\Crud\Model\FileChecker::__construct
     */
    public function testGetNewFileNameFileExists()
    {
        $this->file->method('getPathInfo')->willReturn([
            'filename' => 'file',
            'extension' => 'ext',
            'basename' => 'file.ext',
            'dirname' => 'dir'
        ]);
        $this->file->method('fileExists')->willReturnOnConsecutiveCalls(true, true, false);
        $this->assertEquals('file_1.ext', $this->fileChecker->getNewFileName('file', 0));
    }

    /**
     * @covers \Umc\Crud\Model\FileChecker::getNewFileName
     * @covers \Umc\Crud\Model\FileChecker::__construct
     */
    public function testGetNewFileNameFileExistsThreeLevels()
    {
        $this->file->method('getPathInfo')->willReturn([
            'filename' => 'file',
            'extension' => 'ext',
            'basename' => 'file.ext',
            'dirname' => 'dir'
        ]);
        $this->file->method('fileExists')->willReturnOnConsecutiveCalls(true, true, true, true, false);
        $this->assertEquals('file_3.ext', $this->fileChecker->getNewFileName('file', 0));
    }
}
