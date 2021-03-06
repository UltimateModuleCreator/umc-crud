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

use Magento\Catalog\Api\AttributeSetRepositoryInterface;
use Magento\Eav\Api\Data\AttributeSetInterface;
use Magento\Eav\Api\Data\AttributeSetSearchResultsInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Api\SortOrderBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Source\Catalog\ProductAttributeSet;

class ProductAttributeSetTest extends TestCase
{
    /**
     * @var AttributeSetRepositoryInterface | MockObject
     */
    private $attributeSetRepository;
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
     * @var AttributeSetSearchResultsInterface | MockObject
     */
    private $searchResults;
    /**
     * @var ProductAttributeSet
     */
    private $productAttributeSet;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->attributeSetRepository = $this->createMock(AttributeSetRepositoryInterface::class);
        $this->searchCriteriaBuilder = $this->createMock(SearchCriteriaBuilder::class);
        $this->searchResults = $this->createMock(AttributeSetSearchResultsInterface::class);
        $this->sortOrderBuilder = $this->createMock(SortOrderBuilder::class);
        $this->searchCriteria = $this->createMock(SearchCriteria::class);
        $this->sortOrder = $this->createMock(SortOrder::class);
        $this->productAttributeSet = new ProductAttributeSet(
            $this->attributeSetRepository,
            $this->searchCriteriaBuilder,
            $this->sortOrderBuilder
        );
    }

    /**
     * @covers \Umc\Crud\Source\Catalog\ProductAttributeSet::toOptionArray
     * @covers \Umc\Crud\Source\Catalog\ProductAttributeSet::__construct
     */
    public function testToOptionArray()
    {
        $this->sortOrderBuilder->expects($this->once())->method('setAscendingDirection');
        $this->sortOrderBuilder->expects($this->once())->method('setField');
        $this->sortOrderBuilder->expects($this->once())->method('create')->willReturn($this->sortOrder);
        $this->searchCriteriaBuilder->expects($this->once())->method('addSortOrder')
            ->with($this->sortOrder);
        $this->searchCriteriaBuilder->expects($this->once())->method('create')->willReturn($this->searchCriteria);
        $this->searchResults->expects($this->once())->method('getItems')->willReturn([
            $this->getAttributeSetMock('set1', 1),
            $this->getAttributeSetMock('set2', 2),
            $this->getAttributeSetMock('set3', 3),
        ]);
        $this->attributeSetRepository->expects($this->once())->method('getList')->willReturn($this->searchResults);
        $expected = [
            [
                'label' => 'set1',
                'value' => 1
            ],
            [
                'label' => 'set2',
                'value' => 2
            ],
            [
                'label' => 'set3',
                'value' => 3
            ],
        ];
        $this->assertEquals($expected, $this->productAttributeSet->toOptionArray());
        //call twice to test memoizing
        $this->assertEquals($expected, $this->productAttributeSet->toOptionArray());
    }

    /**
     * @param $name
     * @param $id
     * @return MockObject
     */
    private function getAttributeSetMock($name, $id)
    {
        $mock = $this->createMock(AttributeSetInterface::class);
        $mock->method('getAttributeSetName')->willReturn($name);
        $mock->method('getAttributeSetId')->willReturn($id);
        return $mock;
    }
}
