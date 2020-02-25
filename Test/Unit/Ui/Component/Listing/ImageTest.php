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

namespace Umc\Crud\Test\Unit\Ui\Component\Listing;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\Store;
use Magento\Store\Model\StoreManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Model\FileInfo;
use Umc\Crud\Ui\Component\Listing\Image;
use Umc\Crud\Ui\EntityUiConfig;

class ImageTest extends TestCase
{
    /**
     * @var ContextInterface | MockObject
     */
    private $context;
    /**
     * @var UiComponentFactory | MockObject
     */
    private $uiComponentFactory;
    /**
     * @var StoreManagerInterface | MockObject
     */
    private $storeManager;
    /**
     * @var EntityUiConfig | MockObject
     */
    private $uiConfig;
    /**
     * @var FileInfo | MockObject
     */
    private $fileInfo;
    /**
     * @var UiComponentInterface | MockObject
     */
    private $component;
    /**
     * @var Store | MockObject
     */
    private $store;
    /**
     * @var Image
     */
    private $image;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->context = $this->createMock(ContextInterface::class);
        $this->uiComponentFactory = $this->createMock(UiComponentFactory::class);
        $this->storeManager = $this->createMock(StoreManagerInterface::class);
        $this->uiConfig = $this->createMock(EntityUiConfig::class);
        $this->fileInfo = $this->createMock(FileInfo::class);
        $this->component = $this->createMock(UiComponentInterface::class);
        $this->store = $this->createMock(Store::class);
        $this->storeManager->method('getStore')->willReturn($this->store);
        $this->image = new Image(
            $this->context,
            $this->uiComponentFactory,
            $this->storeManager,
            $this->uiConfig,
            $this->fileInfo,
            [],
            ['name' => 'imageField']
        );
    }

    /**
     * @covers \Umc\Crud\Ui\Component\Listing\Image::prepareDataSource
     * @covers \Umc\Crud\Ui\Component\Listing\Image::getUrl
     * @covers \Umc\Crud\Ui\Component\Listing\Image::getEditUrl
     * @covers \Umc\Crud\Ui\Component\Listing\Image::getAlt
     * @covers \Umc\Crud\Ui\Component\Listing\Image::__construct
     */
    public function testPrepareDataSource()
    {
        $this->context->method('getUrl')->willReturn('edit_url');
        $this->uiConfig->method('getNameAttribute')->willReturn('altField');
        $this->fileInfo->method('isBeginsWithMediaDirectoryPath')->willReturnMap([
            ['image', false],
            ['inside/media', true]
        ]);
        $this->store->method('getBaseUrl')->willReturn('base_url/');
        $this->fileInfo->method('getBaseFilePath')->willReturn('base_path');
        $data = [
            'data' => [
                'items' => [
                    [
                        'imageField' => 'image',
                        'altField' => 'alt image',
                    ],
                    [
                        'imageField' => 'inside/media'
                    ],
                    [
                        'imageField' => ''
                    ],
                    []
                ]
            ]
        ];
        $expected = [
            'data' => [
                'items' => [
                    [
                        'imageField' => 'image',
                        'imageField_src' => 'base_url/base_path/image',
                        'imageField_orig_src' => 'base_url/base_path/image',
                        'imageField_alt' => 'alt image',
                        'imageField_link' => 'edit_url',
                        'altField' => 'alt image',
                    ],
                    [
                        'imageField' => 'inside/media',
                        'imageField_src' => 'inside/media',
                        'imageField_orig_src' => 'inside/media',
                        'imageField_alt' => null,
                        'imageField_link' => 'edit_url',
                    ],
                    [
                        'imageField' => '',
                        'imageField_src' => '',
                        'imageField_orig_src' => '',
                        'imageField_alt' => null,
                        'imageField_link' => 'edit_url'
                    ],
                    [
                        'imageField_src' => '',
                        'imageField_orig_src' => '',
                        'imageField_alt' => null,
                        'imageField_link' => 'edit_url'
                    ]
                ]
            ]
        ];
        $this->assertEquals($expected, $this->image->prepareDataSource($data));
    }
}
