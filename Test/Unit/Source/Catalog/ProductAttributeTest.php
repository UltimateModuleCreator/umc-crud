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

namespace Umc\Crud\Test\Unit\Source\Catalog;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\Data\ProductAttributeSearchResultsInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Source\Catalog\ProductAttribute;

class ProductAttributeTest extends TestCase
{
    /**
     * @var ProductAttributeRepositoryInterface | MockObject
     */
    private $attributeRepository;
    /**
     * @var SearchCriteriaBuilder | MockObject
     */
    private $searchCriteriaBuilder;
    /**
     * @var SearchCriteria | MockObject
     */
    private $searchCriteria;
    /**
     * @var SortOrderBuilder | MockObject
     */
    private $sortOrderBuilder;
    /**
     * @var SortOrder | MockObject
     */
    private $sortOrder;
    /**
     * @var ProductAttributeSearchResultsInterface | MockObject
     */
    private $searchResults;
    /**
     * @var ProductAttribute
     */
    private $productAttribute;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->attributeRepository = $this->createMock(ProductAttributeRepositoryInterface::class);
        $this->searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);
        $this->sortOrderBuilder = $this->createMock(SortOrderBuilder::class);
        $this->searchCriteria = $this->createMock(SearchCriteria::class);
        $this->sortOrder = $this->createMock(SortOrder::class);
        $this->searchResults = $this->createMock(ProductAttributeSearchResultsInterface::class);
        $this->productAttribute = new ProductAttribute(
            $this->attributeRepository,
            $this->searchCriteriaBuilder,
            $this->sortOrderBuilder,
            [
                ['key' => 'field1', 'value' => 'value1'],
                ['key' => 'field2', 'value' => 'value2', 'condition' => 'in'],
            ]
        );
    }

    /**
     * @covers \Umc\Crud\Source\Catalog\ProductAttribute
     */
    public function testToOptionArray()
    {
        $this->sortOrderBuilder->expects($this->once())->method('setAscendingDirection');
        $this->sortOrderBuilder->expects($this->once())->method('setField');
        $this->sortOrderBuilder->expects($this->once())->method('create')->willReturn($this->sortOrder);
        $this->searchCriteriaBuilder->expects($this->exactly(3))->method('addFilter');
        $this->searchCriteriaBuilder->expects($this->once())->method('addSortOrder')
            ->with($this->sortOrder);
        $this->searchCriteriaBuilder->expects($this->once())->method('create')->willReturn($this->searchCriteria);
        $this->searchResults->expects($this->once())->method('getItems')->willReturn([
            $this->getAttributeMock('label1', 'code1'),
            $this->getAttributeMock('label2', 'code2'),
            $this->getAttributeMock('label3', 'code3'),
        ]);
        $this->attributeRepository->expects($this->once())->method('getList')->willReturn($this->searchResults);
        $expected = [
            [
                'label' => 'label1',
                'value' => 'code1'
            ],
            [
                'label' => 'label2',
                'value' => 'code2'
            ],
            [
                'label' => 'label3',
                'value' => 'code3'
            ],
        ];
        $this->assertEquals($expected, $this->productAttribute->toOptionArray());
        //call twice to test memoizing
        $this->assertEquals($expected, $this->productAttribute->toOptionArray());
    }

    /**
     * @param $label
     * @param $code
     * @return MockObject
     */
    private function getAttributeMock($label, $code)
    {
        $mock = $this->createMock(ProductAttributeInterface::class);
        $mock->method('getDefaultFrontendLabel')->willReturn($label);
        $mock->method('getAttributeCode')->willReturn($code);
        return $mock;
    }
}
