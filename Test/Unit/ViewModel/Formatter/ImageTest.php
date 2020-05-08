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
use Magento\Framework\Image\Adapter\AbstractAdapter;
use Magento\Framework\Image\AdapterFactory;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Model\FileInfo;
use Umc\Crud\Model\FileInfoFactory;
use Umc\Crud\ViewModel\Formatter\Image;

class ImageTest extends TestCase
{
    /**
     * @var AdapterFactory | MockObject
     */
    private $adapterFactory;
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
     * @var Image
     */
    private $image;
    /**
     * @var Filesystem\Directory\WriteInterface | MockObject
     */
    private $mediaDir;
    /**
     * @var FileInfo | MockObject
     */
    private $fileInfo;
    /**
     * @var Store | MockObject
     */
    private $store;
    /**
     * @var AbstractAdapter | MockObject
     */
    private $adapter;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->adapterFactory = $this->createMock(AdapterFactory::class);
        $this->fileInfoFactory = $this->createMock(FileInfoFactory::class);
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->mediaDir = $this->createMock(Filesystem\Directory\WriteInterface::class);
        $this->fileInfo = $this->createMock(FileInfo::class);
        $this->store = $this->createMock(Store::class);
        $this->adapter = $this->createMock(AbstractAdapter::class);
        $this->image = new Image(
            $this->adapterFactory,
            $this->fileInfoFactory,
            $this->filesystem,
            $this->storeManager
        );
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter\Image::formatHtml
     * @covers \Umc\Crud\ViewModel\Formatter\Image::getFileInfo
     * @covers \Umc\Crud\ViewModel\Formatter\Image::__construct
     */
    public function testFormatHtmlNoPath()
    {
        $this->fileInfoFactory->method('create')->willReturn($this->fileInfo);
        $this->fileInfo->method('getAbsoluteFilePath')->willReturn('');
        $this->filesystem->expects($this->never())->method('getDirectoryWrite');
        $this->assertEquals('', $this->image->formatHtml('value'));
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter\Image::formatHtml
     * @covers \Umc\Crud\ViewModel\Formatter\Image::getFileInfo
     * @covers \Umc\Crud\ViewModel\Formatter\Image::getDestinationRelativePath
     * @covers \Umc\Crud\ViewModel\Formatter\Image::getImageSettings
     * @covers \Umc\Crud\ViewModel\Formatter\Image::__construct
     */
    public function testFormatHtml()
    {
        $this->fileInfoFactory->method('create')->willReturn($this->fileInfo);
        $this->fileInfo->method('getFilePath')->willReturn('absolute/path/to/image/path/goes/here.jpg');
        $this->fileInfo->method('getAbsoluteFilePath')->willReturn('absolute/path');
        $this->filesystem->method('getDirectoryWrite')->willReturn($this->mediaDir);
        $this->mediaDir->method('isFile')->willReturn(false);
        $this->adapterFactory->method('create')->willReturn($this->adapter);
        $this->storeManager->method('getStore')->willReturn($this->store);
        $this->store->method('getBaseUrl')->willReturn('base/');
        $this->assertEquals(
            'base/absolute/path/to/image/cache/2e5cc4e4036c930cd893e7434a9fc500/path/goes/here.jpg',
            $this->image->formatHtml('image/path/goes/here.jpg', ['resize' => [100, 100]])
        );
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter\Image::formatHtml
     * @covers \Umc\Crud\ViewModel\Formatter\Image::getFileInfo
     * @covers \Umc\Crud\ViewModel\Formatter\Image::getDestinationRelativePath
     * @covers \Umc\Crud\ViewModel\Formatter\Image::getImageSettings
     * @covers \Umc\Crud\ViewModel\Formatter\Image::__construct
     */
    public function testFormatHtmlWithStringResize()
    {
        $this->fileInfoFactory->method('create')->willReturn($this->fileInfo);
        $this->fileInfo->method('getFilePath')->willReturn('absolute/path/to/image/path/goes/here.jpg');
        $this->fileInfo->method('getAbsoluteFilePath')->willReturn('absolute/path');
        $this->filesystem->method('getDirectoryWrite')->willReturn($this->mediaDir);
        $this->mediaDir->method('isFile')->willReturn(false);
        $this->adapterFactory->method('create')->willReturn($this->adapter);
        $this->storeManager->method('getStore')->willReturn($this->store);
        $this->store->method('getBaseUrl')->willReturn('base/');
        $this->assertEquals(
            'base/absolute/path/to/image/path/cache/a593982ff801608e4aaebdd647b05a73/goes/here.jpg',
            $this->image->formatHtml('image/path/goes/here.jpg', ['resize' => 100, 'image_name_parts' => 2])
        );
    }
}
