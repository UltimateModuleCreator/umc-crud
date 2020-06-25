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

use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Model\FileInfo;

class FileInfoTest extends TestCase
{
    /**
     * @var Filesystem | MockObject
     */
    private $filesystem;
    /**
     * @var Mime | MockObject
     */
    private $mime;
    /**
     * @var StoreManagerInterface | MockObject
     */
    private $storeManager;
    /**
     * @var Store | MockObject
     */
    private $store;
    /**
     * @var FileInfo
     */
    private $fileInfo;
    /**
     * @var Filesystem\Directory\WriteInterface | MockObject
     */
    private $mediaDirectory;
    /**
     * @var Filesystem\Directory\ReadInterface | MockObject
     */
    private $readDirectory;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->mime = $this->createMock(Mime::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->store = $this->createMock(Store::class);
        $this->storeManager->method('getStore')->willReturn($this->store);
        $this->store->method('getBaseUrl')->willReturn('base_url/');
        $this->mediaDirectory = $this->createMock(Filesystem\Directory\WriteInterface::class);
        $this->readDirectory = $this->createMock(Filesystem\Directory\ReadInterface::class);
        $this->filesystem->method('getDirectoryWrite')->willReturn($this->mediaDirectory);
        $this->filesystem->method('getDirectoryRead')->willReturn($this->readDirectory);
        $this->fileInfo = new FileInfo(
            $this->filesystem,
            $this->mime,
            $this->storeManager,
            'base/path'
        );
    }

    /**
     * @covers \Umc\Crud\Model\FileInfo::getBaseFilePath
     * @covers \Umc\Crud\Model\FileInfo::__construct
     */
    public function testGetBaseFilePath()
    {
        $this->assertEquals('/base/path', $this->fileInfo->getBaseFilePath());
    }

    /**
     * @covers \Umc\Crud\Model\FileInfo::getMimeType
     * @covers \Umc\Crud\Model\FileInfo::getMediaDirectory
     * @covers \Umc\Crud\Model\FileInfo::getFilePath
     * @covers \Umc\Crud\Model\FileInfo::getPubDirectory
     * @covers \Umc\Crud\Model\FileInfo::getBaseDirectory
     * @covers \Umc\Crud\Model\FileInfo::getMediaDirectoryPathRelativeToBaseDirectoryPath
     * @covers \Umc\Crud\Model\FileInfo::removeStorePath
     * @covers \Umc\Crud\Model\FileInfo::isBeginsWithMediaDirectoryPath
     * @covers \Umc\Crud\Model\FileInfo::__construct
     */
    public function testGetMimeType()
    {
        $this->readDirectory->method('getAbsolutePath')->willReturn('absolute/path');
        $this->readDirectory->method('getRelativePath')->willReturn('relative');
        $this->mediaDirectory->method('getAbsolutePath')->willReturn('media/absolute/path');
        $this->mime->method('getMimeType')->willReturn('mime');
        $this->assertEquals('mime', $this->fileInfo->getMimeType('some/file.png'));
    }

    /**
     * @covers \Umc\Crud\Model\FileInfo::getStat
     * @covers \Umc\Crud\Model\FileInfo::getMediaDirectory
     * @covers \Umc\Crud\Model\FileInfo::getFilePath
     * @covers \Umc\Crud\Model\FileInfo::getPubDirectory
     * @covers \Umc\Crud\Model\FileInfo::getBaseDirectory
     * @covers \Umc\Crud\Model\FileInfo::getMediaDirectoryPathRelativeToBaseDirectoryPath
     * @covers \Umc\Crud\Model\FileInfo::removeStorePath
     * @covers \Umc\Crud\Model\FileInfo::isBeginsWithMediaDirectoryPath
     * @covers \Umc\Crud\Model\FileInfo::__construct
     */
    public function testGetStat()
    {
        $this->readDirectory->method('getAbsolutePath')->willReturn('absolute/path');
        $this->readDirectory->method('getRelativePath')->willReturn('relative');
        $this->mediaDirectory->method('getAbsolutePath')->willReturn('media/absolute/path');
        $this->mediaDirectory->method('stat')->willReturn(['stat']);
        $this->assertEquals(['stat'], $this->fileInfo->getStat('some/file.png'));
    }

    /**
     * @covers \Umc\Crud\Model\FileInfo::isExist
     * @covers \Umc\Crud\Model\FileInfo::getMediaDirectory
     * @covers \Umc\Crud\Model\FileInfo::getFilePath
     * @covers \Umc\Crud\Model\FileInfo::getPubDirectory
     * @covers \Umc\Crud\Model\FileInfo::getBaseDirectory
     * @covers \Umc\Crud\Model\FileInfo::getMediaDirectoryPathRelativeToBaseDirectoryPath
     * @covers \Umc\Crud\Model\FileInfo::removeStorePath
     * @covers \Umc\Crud\Model\FileInfo::isBeginsWithMediaDirectoryPath
     * @covers \Umc\Crud\Model\FileInfo::__construct
     */
    public function testIsExist()
    {
        $this->readDirectory->method('getAbsolutePath')->willReturn('absolute/path');
        $this->readDirectory->method('getRelativePath')->willReturn('relative');
        $this->mediaDirectory->method('getAbsolutePath')->willReturn('media/absolute/path');
        $this->mediaDirectory->method('isExist')->willReturn(true);
        $this->assertTrue($this->fileInfo->isExist('some/file.png'));
    }

    /**
     * @covers \Umc\Crud\Model\FileInfo::getAbsoluteFilePath
     * @covers \Umc\Crud\Model\FileInfo::getMediaDirectory
     * @covers \Umc\Crud\Model\FileInfo::getFilePath
     * @covers \Umc\Crud\Model\FileInfo::getPubDirectory
     * @covers \Umc\Crud\Model\FileInfo::getBaseDirectory
     * @covers \Umc\Crud\Model\FileInfo::getMediaDirectoryPathRelativeToBaseDirectoryPath
     * @covers \Umc\Crud\Model\FileInfo::removeStorePath
     * @covers \Umc\Crud\Model\FileInfo::isBeginsWithMediaDirectoryPath
     * @covers \Umc\Crud\Model\FileInfo::__construct
     */
    public function testGetAbsoluteFilePath()
    {
        $this->readDirectory->method('getAbsolutePath')->willReturn('absolute/path');
        $this->readDirectory->method('getRelativePath')->willReturn('relative');
        $this->mediaDirectory->method('getAbsolutePath')->willReturn('media/absolute/path');
        $this->mediaDirectory->method('isExist')->willReturn(true);
        $this->assertEquals('media/absolute/path', $this->fileInfo->getAbsoluteFilePath('some/file.png'));
    }
}
