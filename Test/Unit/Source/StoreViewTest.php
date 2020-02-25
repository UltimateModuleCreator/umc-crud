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

namespace Umc\Crud\Test\Unit\Source;

use Magento\Framework\Escaper;
use Magento\Store\Model\Group;
use Magento\Store\Model\System\Store;
use Magento\Store\Model\Website;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Source\StoreView;

class StoreViewTest extends TestCase
{
    /**
     * @var Store | MockObject
     */
    private $systemStore;
    /**
     * @var Escaper | MockObject
     */
    private $escaper;
    /**
     * @var StoreView
     */
    private $storeView;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->systemStore = $this->createMock(Store::class);
        $this->escaper = $this->createMock(Escaper::class);
        $this->storeView = new StoreView(
            $this->systemStore,
            $this->escaper
        );
    }

    /**
     * @covers \Umc\Crud\Source\StoreView
     */
    public function testToOptionArray()
    {
        $this->systemStore->expects($this->once())->method('getWebsiteCollection')->willReturn([
            $this->getWebsiteMock(1, 'website1'),
            $this->getWebsiteMock(2, 'website2')
        ]);
        $this->systemStore->expects($this->once())->method('getGroupCollection')->willReturn([
            $this->getGroupMock(11, 1, 'group1'),
            $this->getGroupMock(12, 1, 'group2'),
        ]);
        $this->systemStore->expects($this->once())->method('getStoreCollection')->willReturn([
            $this->getStoreViewMock(1, 11, 'storeview1'),
            $this->getStoreViewMock(2, 12, 'storeview2'),
        ]);
        $expected = [
            [
                'label' => _('All Store Views'),
                'value' => 0
            ],
            [
                'label' => 'website1',
                '__disableTmpl' => 1,
                'value' => [
                    [
                        'label' => '    group1',
                        '__disableTmpl' => 1,
                        'value' => [
                            [
                                'label' => '        storeview1',
                                'value' => 1,
                                '__disableTmpl' => 1
                            ]
                        ]
                    ],
                    [
                        'label' => '    group2',
                        '__disableTmpl' => 1,
                        'value' => [
                            [
                                'label' => '        storeview2',
                                'value' => 2,
                                '__disableTmpl' => 1
                            ]
                        ]
                    ]
                ]
            ]
        ];
        $this->assertEquals($expected, $this->storeView->toOptionArray());
        //call twice to test memoizing
        $this->assertEquals($expected, $this->storeView->toOptionArray());
    }

    /**
     * @param $id
     * @param $name
     * @return MockObject
     */
    private function getWebsiteMock($id, $name)
    {
        $mock = $this->createMock(Website::class);
        $mock->method('getId')->willReturn($id);
        $mock->method('getName')->willReturn($name);
        return $mock;
    }

    /**
     * @param $id
     * @param $websiteId
     * @param $name
     * @return MockObject
     */
    private function getGroupMock($id, $websiteId, $name)
    {
        $mock = $this->createMock(Group::class);
        $mock->method('getId')->willReturn($id);
        $mock->method('getWebsiteId')->willReturn($websiteId);
        $mock->method('getName')->willReturn($name);
        return $mock;
    }

    /**
     * @param $id
     * @param $groupId
     * @param $name
     * @return MockObject
     */
    private function getStoreViewMock($id, $groupId, $name)
    {
        $mock = $this->createMock(\Magento\Store\Model\Store::class);
        $mock->method('getId')->willReturn($id);
        $mock->method('getGroupId')->willReturn($groupId);
        $mock->method('getName')->willReturn($name);
        return $mock;
    }
}
