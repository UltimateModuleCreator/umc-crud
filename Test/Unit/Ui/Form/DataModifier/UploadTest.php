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

namespace Umc\Crud\Test\Unit\Ui\Form\DataModifier;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Umc\Crud\Model\FileInfo;
use Umc\Crud\Model\Uploader;
use Umc\Crud\Ui\Form\DataModifier\Upload;

class UploadTest extends TestCase
{
    /**
     * @var Uploader | MockObject
     */
    private $uploader;
    /**
     * @var LoggerInterface | MockObject
     */
    private $logger;
    /**
     * @var FileInfo | MockObject
     */
    private $fileInfo;
    /**
     * @var StoreManagerInterface | MockObject
     */
    private $storeManager;
    /**
     * @var AbstractModel | MockObject
     */
    private $model;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->uploader = $this->createMock(Uploader::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->fileInfo = $this->createMock(FileInfo::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->model = $this->createMock(AbstractModel::class);
    }

    /**
     * @covers \Umc\Crud\Ui\Form\DataModifier\Upload::modifyData
     * @covers \Umc\Crud\Ui\Form\DataModifier\Upload::__construct
     */
    public function testModifyDataNoValue()
    {
        $upload = new Upload(
            ['field1'],
            $this->uploader,
            $this->logger,
            $this->fileInfo,
            $this->storeManager
        );
        $data = ['dummy' => 'dummy', 'field1' => ''];
        $this->assertEquals($data, $upload->modifyData($this->model, $data));
    }

    /**
     * @covers \Umc\Crud\Ui\Form\DataModifier\Upload::modifyData
     * @covers \Umc\Crud\Ui\Form\DataModifier\Upload::__construct
     */
    public function testModifyDataNoMissingFile()
    {
        $upload = new Upload(
            ['field1'],
            $this->uploader,
            $this->logger,
            $this->fileInfo,
            $this->storeManager
        );
        $data = ['dummy' => 'dummy', 'field1' => 'file'];
        $this->fileInfo->method('isExist')->willReturn(false);
        $this->assertEquals($data, $upload->modifyData($this->model, $data));
    }

    /**
     * @covers \Umc\Crud\Ui\Form\DataModifier\Upload::modifyData
     * @covers \Umc\Crud\Ui\Form\DataModifier\Upload::__construct
     */
    public function testModifyData()
    {
        $upload = new Upload(
            ['field1'],
            $this->uploader,
            $this->logger,
            $this->fileInfo,
            $this->storeManager
        );
        $data = ['dummy' => 'dummy', 'field1' => 'file'];
        $this->fileInfo->method('isExist')->willReturn(true);
        $this->fileInfo->method('isBeginsWithMediaDirectoryPath')->willReturn(true);
        $this->fileInfo->method('getMimeType')->willReturn('mime');
        $this->fileInfo->method('getStat')->willReturn(['size' => 20]);
        $expected = [
            'dummy' => 'dummy',
            'field1' => [
                [
                    'name' => 'file',
                    'url' => 'file',
                    'size' => 20,
                    'type' => 'mime'
                ]
            ]
        ];
        $this->assertEquals($expected, $upload->modifyData($this->model, $data));
    }

    /**
     * @covers \Umc\Crud\Ui\Form\DataModifier\Upload::getUrl
     * @covers \Umc\Crud\Ui\Form\DataModifier\Upload::__construct
     */
    public function testGetUrlNoFile()
    {
        $this->expectException(LocalizedException::class);
        $upload = new Upload(
            ['field1'],
            $this->uploader,
            $this->logger,
            $this->fileInfo,
            $this->storeManager
        );
        $upload->getUrl([]);
    }

    /**
     * @covers \Umc\Crud\Ui\Form\DataModifier\Upload::getUrl
     * @covers \Umc\Crud\Ui\Form\DataModifier\Upload::__construct
     */
    public function testGetUrl()
    {
        $store = $this->createMock(Store::class);
        $store->method('getBaseUrl')->willReturn('base_url/');
        $this->storeManager->method('getStore')->willReturn($store);
        $this->fileInfo->method('getBaseFilePath')->willReturn('file_path');
        $upload = new Upload(
            ['field1'],
            $this->uploader,
            $this->logger,
            $this->fileInfo,
            $this->storeManager
        );
        $expected = 'base_url/file_path/file';
        $this->assertEquals($expected, $upload->getUrl('file'));
    }
}
