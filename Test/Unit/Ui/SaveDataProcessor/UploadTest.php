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
     * @var Upload
     */
    private $upload;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->uploader = $this->createMock(Uploader::class);
        $this->fileInfo = $this->createMock(FileInfo::class);
        $this->filesystem = $this->createMock(Filesystem::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->upload = new Upload(
            ['field1', 'field2', 'field3', 'field4'],
            $this->uploader,
            $this->fileInfo,
            $this->filesystem,
            $this->logger
        );
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
        $data = [
            'field1' => [
                [
                    'tmp_name' => 'tmp_name',
                    'file' => 'file1',
                    'url' => 'path/url'
                ]
            ],
            'field2' => [
                [
                    'url' => 'value2'
                ]
            ],
            'field3' => [],
            'dummy' => 'dummy'
        ];
        $this->filesystem->method('getUri')->willReturn('path');
        $this->uploader->expects($this->once())->method('moveFileFromTmp')->willReturn('tmp_moved');

        $expected = [
            'field1' => 'tmp_moved',
            'field2' => 'value2',
            'field3' => '',
            'dummy' => 'dummy'
        ];
        $this->assertEquals($expected, $this->upload->modifyData($data));
    }
}
