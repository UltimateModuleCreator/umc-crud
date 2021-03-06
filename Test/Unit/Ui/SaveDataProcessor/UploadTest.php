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

namespace Umc\Crud\Test\Unit\Ui\SaveDataProcessor;

use Magento\Framework\Filesystem;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Umc\Crud\Model\FileInfo;
use Umc\Crud\Model\Uploader;
use Umc\Crud\Ui\SaveDataProcessor\Upload;

class UploadTest extends TestCase
{
    /**
     * @var Uploader | MockObject
     */
    private $uploader;
    /**
     * @var FileInfo | MockObject
     */
    private $fileInfo;
    /**
     * @var Filesystem | MockObject
     */
    private $filesystem;
    /**
     * @var LoggerInterface | MockObject
     */
    private $logger;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->uploader = $this->createMock(Uploader::class);
        $this->fileInfo = $this->createMock(FileInfo::class);
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->logger = $this->createMock(LoggerInterface::class);
    }

    /**
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Upload::modifyData
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Upload::fileResidesOutsideUploadDir
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Upload::isTmpFileAvailable
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Upload::getUploadedImageName
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Upload::__construct
     */
    public function testModifyData()
    {
        $this->fileInfo->method('getFilePath')->willReturnArgument(0);
        $upload = new Upload(
            ['field1', 'field2', 'field3', 'field4'],
            $this->uploader,
            $this->fileInfo,
            $this->filesystem,
            $this->logger,
            false
        );
        $uploadStrict = new Upload(
            ['field1', 'field2', 'field3', 'field4'],
            $this->uploader,
            $this->fileInfo,
            $this->filesystem,
            $this->logger,
            true
        );
        $data = [
            'field1' => [
                [
                    'tmp_name' => 'tmp_name',
                    'file' => 'file1',
                    'url' => 'path/url',
                    'name' => 'path/url'
                ]
            ],
            'field2' => [
                [
                    'url' => 'value2',
                    'name' => 'value2'
                ]
            ],
            'field3' => [],
            'dummy' => 'dummy'
        ];
        $this->filesystem->method('getUri')->willReturn('path');
        $this->uploader->method('moveFileFromTmp')->willReturn('tmp_moved');

        $expected = [
            'field1' => 'tmp_moved',
            'field2' => 'value2',
            'field3' => '',
            'dummy' => 'dummy'
        ];
        $expectedStrict = [
            'field1' => 'tmp_moved',
            'field2' => 'value2',
            'field3' => '',
            'dummy' => 'dummy',
            'field4' => ''
        ];
        $this->assertEquals($expected, $upload->modifyData($data));
        $this->assertEquals($expectedStrict, $uploadStrict->modifyData($data));
    }

    /**
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Upload::modifyData
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Upload::fileResidesOutsideUploadDir
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Upload::isTmpFileAvailable
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Upload::getUploadedImageName
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Upload::__construct
     */
    public function testModifyDataWithException()
    {
        $this->fileInfo->method('getFilePath')->willReturnArgument(0);
        $upload = new Upload(
            ['field1', 'field2', 'field3', 'field4'],
            $this->uploader,
            $this->fileInfo,
            $this->filesystem,
            $this->logger,
            false
        );
        $data = [
            'field1' => [
                [
                    'tmp_name' => 'tmp_name',
                    'file' => 'file1',
                    'url' => 'path/url',
                    'name' => 'path/url',
                ]
            ],
            'field2' => [
                [
                    'url' => 'value2',
                    'name' => 'value2'
                ]
            ],
            'field3' => [],
            'dummy' => 'dummy'
        ];
        $this->filesystem->method('getUri')->willReturn('path');
        $this->uploader->expects($this->once())->method('moveFileFromTmp')->willThrowException(new \Exception());
        $this->logger->expects($this->once())->method('critical');
        $expected = [
            'field1' => [
                0 => [
                    'tmp_name' => 'tmp_name',
                    'file' => 'file1',
                    'url' => 'path/url',
                    'name' => 'path/url',
                ],
            ],
            'field2' => 'value2',
            'field3' => '',
            'dummy' => 'dummy'
        ];
        $this->assertEquals($expected, $upload->modifyData($data));
    }

    /**
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Upload::modifyData
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Upload::fileResidesOutsideUploadDir
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Upload::isTmpFileAvailable
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Upload::getUploadedImageName
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Upload::__construct
     */
    public function testModifyDataFileOutside()
    {
        $upload = new Upload(
            ['field1', 'field2', 'field3', 'field4'],
            $this->uploader,
            $this->fileInfo,
            $this->filesystem,
            $this->logger,
            false
        );
        $this->fileInfo->method('getFilePath')->willReturn('media/path');
        $this->filesystem->method('getUri')->willReturn('media');
        $data = [
            'field1' => [
                [
                    'file' => 'file1',
                    'name' => 'media/path/url',
                    'url' => 'media/path/url'
                ]
            ],
        ];
        $expected = [
            'field1' => 'media/path/url'
        ];
        $this->assertEquals($expected, $upload->modifyData($data));
    }
}
