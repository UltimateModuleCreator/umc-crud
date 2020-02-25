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
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Eav\Api\Data\AttributeOptionInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Source\Catalog\ProductAttributeOptions;

class ProductAttributeOptionsTest extends TestCase
{
    /**
     * @var ProductAttributeRepositoryInterface | MockObject
     */
    private $attributeRepository;
    /**
     * @var ProductAttributeOptions
     */
    private $productAttributeOptions;
    /**
     * @var ProductAttributeInterface | MockObject
     */
    private $attribute;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->attributeRepository = $this->createMock(ProductAttributeRepositoryInterface::class);
        $this->attribute = $this->createMock(ProductAttributeInterface::class);
        $this->productAttributeOptions = new ProductAttributeOptions(
            $this->attributeRepository,
            'attributeCode'
        );
    }

    /**
     * @covers \Umc\Crud\Source\Catalog\ProductAttributeOptions::toOptionArray
     * @covers \Umc\Crud\Source\Catalog\ProductAttributeOptions::__construct
     */
    public function testToOptionArray()
    {
        $this->attributeRepository->expects($this->once())->method('get')->willReturn($this->attribute);
        $this->attribute->expects($this->once())->method('getOptions')->willReturn([
            $this->getOptionMock(1, 'label1'),
            $this->getOptionMock(2, 'label2'),
        ]);
        $expected = [
            [
                'label' => 'label1',
                'value' => 1
            ],
            [
                'label' => 'label2',
                'value' => 2
            ]
        ];
        $this->assertEquals($expected, $this->productAttributeOptions->toOptionArray());
        //call twice to test memoizing
        $this->assertEquals($expected, $this->productAttributeOptions->toOptionArray());
    }

    /**
     * @covers \Umc\Crud\Source\Catalog\ProductAttributeOptions::toOptionArray
     * @covers \Umc\Crud\Source\Catalog\ProductAttributeOptions::__construct
     */
    public function testToOptionArrayNoAttribute()
    {
        $this->attributeRepository->expects($this->once())->method('get')->willThrowException(
            $this->createMock(NoSuchEntityException::class)
        );
        $this->attribute->expects($this->never())->method('getOptions');
        $this->assertEquals([], $this->productAttributeOptions->toOptionArray());
        //call twice to test memoizing
        $this->assertEquals([], $this->productAttributeOptions->toOptionArray());
    }

    /**
     * @param $value
     * @param $label
     * @return MockObject
     */
    private function getOptionMock($value, $label)
    {
        $mock = $this->createMock(AttributeOptionInterface::class);
        $mock->method('getValue')->willReturn($value);
        $mock->method('getLabel')->willReturn($label);
        return $mock;
    }
}
