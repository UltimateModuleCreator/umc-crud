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

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Serialize\Serializer\Json;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Ui\Form\DataModifier\DynamicRows;

class DataModifierTest extends TestCase
{
    /**
     * @var Json | MockObject
     */
    private $serializer;
    /**
     * @var AbstractModel | MockObject
     */
    private $model;
    /**
     * @var DynamicRows
     */
    private $dynamicRows;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->serializer = $this->createMock(Json::class);
        $this->model = $this->createMock(AbstractModel::class);
        $this->dynamicRows = new DynamicRows(
            $this->serializer,
            ['field1', 'field2', 'field3']
        );
    }

    /**
     * @covers \Umc\Crud\Ui\Form\DataModifier\DynamicRows::modifyData
     * @covers \Umc\Crud\Ui\Form\DataModifier\DynamicRows::__construct
     */
    public function testModifyData()
    {
        $data = [
            'field1' => 'value1',
            'field2' => ['value2'],
            'dummy' => 'dummy'
        ];
        $this->serializer->expects($this->once())->method('unserialize')->willReturnCallback(function ($item) {
            return [$item];
        });
        $expected = [
            'field1' => ['value1'],
            'field2' => ['value2'],
            'dummy' => 'dummy'
        ];
        $this->assertEquals($expected, $this->dynamicRows->modifyData($this->model, $data));
    }

    /**
     * @covers \Umc\Crud\Ui\Form\DataModifier\DynamicRows::modifyData
     * @covers \Umc\Crud\Ui\Form\DataModifier\DynamicRows::__construct
     */
    public function testModifyDataWithUnserializeError()
    {
        $data = [
            'field1' => 'value1',
            'field2' => ['value2'],
            'dummy' => 'dummy'
        ];
        $this->serializer->expects($this->once())->method('unserialize')->willThrowException(new \Exception());
        $expected = [
            'field1' => [],
            'field2' => ['value2'],
            'dummy' => 'dummy'
        ];
        $this->assertEquals($expected, $this->dynamicRows->modifyData($this->model, $data));
    }
}
