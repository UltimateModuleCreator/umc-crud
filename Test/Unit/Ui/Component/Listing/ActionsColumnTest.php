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

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponentInterface;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Ui\Component\Listing\ActionsColumn;
use Umc\Crud\Ui\EntityUiConfig;

class ActionsColumnTest extends TestCase
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
     * @var UrlInterface | MockObject
     */
    private $urlBuilder;
    /**
     * @var EntityUiConfig | MockObject
     */
    private $uiConfig;
    /**
     * @var UiComponentInterface | MockObject
     */
    private $component;
    /**
     * @var ActionsColumn
     */
    private $actionsColumn;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->context = $this->createMock(ContextInterface::class);
        $this->uiComponentFactory = $this->createMock(UiComponentFactory::class);
        $this->urlBuilder = $this->createMock(UrlInterface::class);
        $this->uiConfig = $this->createMock(EntityUiConfig::class);
        $this->component = $this->createMock(UiComponentInterface::class);
        $this->actionsColumn = new ActionsColumn(
            $this->context,
            $this->uiComponentFactory,
            $this->urlBuilder,
            $this->uiConfig,
            [],
            [
                'name' => 'actions'
            ]
        );
    }

    /**
     * @covers \Umc\Crud\Ui\Component\Listing\ActionsColumn::prepareDataSource
     * @covers \Umc\Crud\Ui\Component\Listing\ActionsColumn::__construct
     */
    public function testPrepareDataSource()
    {
        $this->uiConfig->method('getEditUrlPath')->willReturn('edit_url');
        $this->uiConfig->method('getDeleteUrlPath')->willReturn('delete_url');
        $data = [
            'data' => [
                'items' => [
                    [
                        'field1' => 'value1',
                        'field2' => 'value2'
                    ]
                ]
            ]
        ];
        $this->urlBuilder->method('getUrl')->willReturnArgument(0);
        $this->uiConfig->method('getDeleteMessage')->willReturn('Really?');
        $this->uiConfig->method('getNameAttribute')->willReturn('field1');
        $expected = [
            'data' => [
                'items' => [
                    [
                        'field1' => 'value1',
                        'field2' => 'value2',
                        'actions' => [
                            'edit' => [
                                'href' => 'edit_url',
                                'label' => 'Edit'
                            ],
                            'delete' => [
                                'href' => 'delete_url',
                                'label' => 'Delete',
                                'confirm' => [
                                    'title' => 'Delete "${ $.$data.field1 }"',
                                    'message' => 'Really?'
                                ],
                                'post' => true
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $this->assertEquals($expected, $this->actionsColumn->prepareDataSource($data));
    }
}
