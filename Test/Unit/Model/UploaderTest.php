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

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Helper\File\Storage\Database;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Umc\Crud\Model\FileChecker;
use Umc\Crud\Model\Uploader;

class UploaderTest extends TestCase
{
    /**
     * @var Database | MockObject
     */
    private $coreFileStorageDatabase;
    /**
     * @var Filesystem | MockObject
     */
    private $filesystem;
    /**
     * @var UploaderFactory | MockObject
     */
    private $uploaderFactory;
    /**
     * @var StoreManagerInterface | MockObject
     */
    private $storeManager;
    /**
     * @var LoggerInterface | MockObject
     */
    private $logger;
    /**
     * @var Uploader
     */
    private $uploader;
    /**
     * @var Store | MockObject
     */
    private $store;
    /**
     * @var Filesystem\Directory\WriteInterface | MockObject
     */
    private $mediaDirectory;
    /**
     * @var FileChecker | MockObject
     */
    private $fileChecker;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->coreFileStorageDatabase = $this->createMock(Database::class);
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->uploaderFactory = $this->createMock(UploaderFactory::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->store = $this->createMock(Store::class);
        $this->storeManager->method('getStore')->willReturn($this->store);
        $this->mediaDirectory = $this->createMock(Filesystem\Directory\WriteInterface::class);
        $this->filesystem->method('getDirectoryWrite')->willReturn($this->mediaDirectory);
        $this->fileChecker = $this->createMock(FileChecker::class);
        $this->uploader = new Uploader(
            $this->coreFileStorageDatabase,
            $this->filesystem,
            $this->uploaderFactory,
            $this->storeManager,
            $this->logger,
            $this->fileChecker,
            'base_tmp_path',
            'base_path',
            ['ext1', 'ext2']
        );
    }

    /**
     * @covers \Umc\Crud\Model\Uploader::getBaseTmpPath
     * @covers \Umc\Crud\Model\Uploader::__construct
     */
    public function testGetBaseTmpPath()
    {
        $this->assertEquals('base_tmp_path', $this->uploader->getBaseTmpPath());
    }

    /**
     * @covers \Umc\Crud\Model\Uploader::getBasePath
     * @covers \Umc\Crud\Model\Uploader::__construct
     */
    public function testGetBasePath()
    {
        $this->assertEquals('base_path', $this->uploader->getBasePath());
    }

    /**
     * @covers \Umc\Crud\Model\Uploader::getAllowedExtensions
     * @covers \Umc\Crud\Model\Uploader::__construct
     */
    public function testGetAllowedExtensions()
    {
        $this->assertEquals(['ext1', 'ext2'], $this->uploader->getAllowedExtensions());
    }

    /**
     * @covers \Umc\Crud\Model\Uploader::getFilePath
     * @covers \Umc\Crud\Model\Uploader::__construct
     */
    public function testGetFilePath()
    {
        $this->assertEquals('path/name', $this->uploader->getFilePath('path/', '/name'));
    }

    /**
     * @covers \Umc\Crud\Model\Uploader::moveFileFromTmp
     * @covers \Umc\Crud\Model\Uploader::__construct
     */
    public function testMoveFileFromTmp()
    {
        $this->coreFileStorageDatabase->expects($this->once())->method('copyFile');
        $this->mediaDirectory->expects($this->once())->method('renameFile');
        $this->fileChecker->expects($this->once())->method('getNewFilename')->willReturn('name');
        $this->assertEquals('name', $this->uploader->moveFileFromTmp('name'));
    }

    /**
     * @covers \Umc\Crud\Model\Uploader::moveFileFromTmp
     * @covers \Umc\Crud\Model\Uploader::__construct
     */
    public function testMoveFileFromTmpWithException()
    {
        $this->coreFileStorageDatabase->expects($this->once())->method('copyFile');
        $this->mediaDirectory->expects($this->once())->method('renameFile')->willThrowException(new \Exception());
        $this->fileChecker->expects($this->once())->method('getNewFilename')->willReturn('name');
        $this->expectException(LocalizedException::class);
        $this->assertEquals('name', $this->uploader->moveFileFromTmp('name'));
    }

    /**
     * @covers \Umc\Crud\Model\Uploader::getBaseUrl
     * @covers \Umc\Crud\Model\Uploader::__construct
     */
    public function testGetBaseUrl()
    {
        $this->store->method('getBaseUrl')->willReturn('base_url/');
        $this->assertEquals('base_url/', $this->uploader->getBaseUrl());
    }

    /**
     * @covers \Umc\Crud\Model\Uploader::saveFileToTmpDir
     * @covers \Umc\Crud\Model\Uploader::__construct
     */
    public function testSaveFileToTmpDir()
    {
        $uploader = $this->createMock(\Magento\MediaStorage\Model\File\Uploader::class);
        $this->uploaderFactory->method('create')->willReturn($uploader);
        $this->store->method('getBaseUrl')->willReturn('base_url/');
        $uploader->method('save')->willReturn([
            'tmp_name' => 'tmp_name',
            'path' => 'path',
            'file' => 'file'
        ]);
        $this->coreFileStorageDatabase->expects($this->once())->method('saveFile');
        $expected = [
            'tmp_name' => 'tmp_name',
            'path' => 'path',
            'file' => 'file',
            'url' => 'base_url/base_tmp_path/file'
        ];
        $this->assertEquals($expected, $this->uploader->saveFileToTmpDir('fileId'));
    }

    /**
     * @covers \Umc\Crud\Model\Uploader::saveFileToTmpDir
     * @covers \Umc\Crud\Model\Uploader::__construct
     */
    public function testSaveFileToTmpDirNoResult()
    {
        $this->expectException(LocalizedException::class);
        $uploader = $this->createMock(\Magento\MediaStorage\Model\File\Uploader::class);
        $this->uploaderFactory->method('create')->willReturn($uploader);
        $this->store->method('getBaseUrl')->willReturn('base_url/');
        $uploader->method('save')->willReturn([
            'tmp_name' => 'tmp_name',
            'path' => 'path',
            'file' => 'file'
        ]);
        $this->coreFileStorageDatabase->expects($this->once())->method('saveFile')
            ->willThrowException(new \Exception());
        $this->uploader->saveFileToTmpDir('fileId');
    }

    /**
     * @covers \Umc\Crud\Model\Uploader::saveFileToTmpDir
     * @covers \Umc\Crud\Model\Uploader::__construct
     */
    public function testSaveFileToTmpStorageSaveError()
    {
        $this->expectException(LocalizedException::class);
        $uploader = $this->createMock(\Magento\MediaStorage\Model\File\Uploader::class);
        $this->uploaderFactory->method('create')->willReturn($uploader);
        $this->store->method('getBaseUrl')->willReturn('base_url/');
        $uploader->method('save')->willReturn(null);
        $this->coreFileStorageDatabase->expects($this->never())->method('saveFile');
        $this->uploader->saveFileToTmpDir('fileId');
    }
}
