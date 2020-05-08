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

namespace Umc\Crud\Test\Unit\ViewModel\Formatter;

use Magento\Framework\Filesystem;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Model\FileInfo;
use Umc\Crud\Model\FileInfoFactory;
use Umc\Crud\ViewModel\Formatter\File;

class FileTest extends TestCase
{
    /**
     * @var FileInfoFactory | MockObject
     */
    private $fileInfoFactory;
    /**
     * @var Filesystem | MockObject
     */
    private $filesystem;
    /**
     * @var StoreManagerInterface | MockObject
     */
    private $storeManager;
    /**
     * @var File
     */
    private $file;
    /**
     * @var FileInfo | MockObject
     */
    private $fileInfo;
    /**
     * @var Store | MockObject
     */
    private $store;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->fileInfoFactory = $this->createMock(FileInfoFactory::class);
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->fileInfo = $this->createMock(FileInfo::class);
        $this->store = $this->createMock(Store::class);
        $this->file = new File(
            $this->fileInfoFactory,
            $this->filesystem,
            $this->storeManager
        );
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter\File::formatHtml
     * @covers \Umc\Crud\ViewModel\Formatter\File::getFileInfo
     * @covers \Umc\Crud\ViewModel\Formatter\File::__construct
     */
    public function testFormatHtmlWithWrongPath()
    {
        $this->fileInfoFactory->expects($this->once())->method('create')->willReturn($this->fileInfo);
        $this->fileInfo->method('getFilePath')->willReturn('');
        $this->storeManager->expects($this->never())->method('getStore');
        $this->assertEquals('', $this->file->formatHtml('value'));
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter\File::formatHtml
     * @covers \Umc\Crud\ViewModel\Formatter\File::getFileInfo
     * @covers \Umc\Crud\ViewModel\Formatter\File::__construct
     */
    public function testFormatHtml()
    {
        $this->fileInfoFactory->expects($this->once())->method('create')->willReturn($this->fileInfo);
        $this->fileInfo->method('getFilePath')->willReturn('/path');
        $this->storeManager->expects($this->once())->method('getStore')->willReturn($this->store);
        $this->store->method('getBaseUrl')->willReturn('base/');
        $this->assertEquals('base/path', $this->file->formatHtml('value'));
    }
}
