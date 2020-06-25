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

use Magento\Framework\Stdlib\DateTime\Filter\Date as DateFilter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Ui\SaveDataProcessor\Date;

class DateTest extends TestCase
{
    /**
     * @var \Zend_Filter_InputFactory | MockObject
     */
    private $filterFactory;
    /**
     * @var DateFilter | MockObject
     */
    private $dateFilter;
    /**
     * @var \Zend_Filter_Input | MockObject
     */
    private $filter;
    /**
     * @var Date
     */
    private $date;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->filterFactory = $this->createMock(\Zend_Filter_InputFactory::class);
        $this->dateFilter = $this->createMock(DateFilter::class);
        $this->filter = $this->createMock(\Zend_Filter_Input::class);
        $this->date = new Date(
            ['field1', 'field2', 'field3'],
            $this->filterFactory,
            $this->dateFilter
        );
    }

    /**
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Date
     */
    public function testModifyData()
    {
        $data = [
            'field1' => '2020-01-01',
            'field2' => '2021-01-01',
        ];
        $expectedFactoryData = [
            'filterRules' => [
                'field1' => $this->dateFilter,
                'field2' => $this->dateFilter
            ],
            'validatorRules' => [],
            'data' => $data
        ];
        $this->filterFactory->expects($this->once())->method('create')
            ->with($expectedFactoryData)
            ->willReturn($this->filter);
        $this->filter->expects($this->once())->method('getUnescaped')->willReturn(['result']);
        $this->assertEquals(['result'], $this->date->modifyData($data));
    }
}
