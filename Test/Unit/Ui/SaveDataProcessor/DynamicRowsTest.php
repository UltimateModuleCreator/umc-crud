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

use Magento\Framework\Serialize\Serializer\Json;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Ui\SaveDataProcessor\DynamicRows;

class DynamicRowsTest extends TestCase
{
    /**
     * @var Json | MockObject
     */
    private $serializer;
    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->serializer = $this->createMock(Json::class);
    }

    /**
     * @covers \Umc\Crud\Ui\SaveDataProcessor\DynamicRows::modifyData
     * @covers \Umc\Crud\Ui\SaveDataProcessor\DynamicRows::__construct
     */
    public function testModifyData()
    {
        $dynamicRows = new DynamicRows(
            $this->serializer,
            ['field1', 'field2', 'field3'],
            false
        );
        $data = [
            'field1' => [1, 2, 3],
            'field2' => 'string',
            'field4' => ['not_processed']
        ];
        $this->serializer->expects($this->once())->method('serialize')->willReturnCallback(
            function (array $item) {
                $item['serialized'] = 1;
                return $item;
            }
        );
        $expected = [
            'field1' => [
                0 => 1,
                1 => 2,
                2 => 3,
                'serialized' => 1
            ],
            'field2' => 'string',
            'field4' => ['not_processed']
        ];
        $this->assertEquals($expected, $dynamicRows->modifyData($data));
    }

    /**
     * @covers \Umc\Crud\Ui\SaveDataProcessor\DynamicRows::modifyData
     * @covers \Umc\Crud\Ui\SaveDataProcessor\DynamicRows::__construct
     */
    public function testModifyDataStrictMode()
    {
        $dynamicRows = new DynamicRows(
            $this->serializer,
            ['field1', 'field2', 'field3'],
            true
        );
        $data = [
            'field1' => [1, 2, 3],
            'field2' => 'string',
            'field4' => ['not_processed']
        ];
        $this->serializer->expects($this->exactly(2))->method('serialize')->willReturnCallback(
            function (array $item) {
                $item['serialized'] = 1;
                return $item;
            }
        );
        $expected = [
            'field1' => [
                0 => 1,
                1 => 2,
                2 => 3,
                'serialized' => 1
            ],
            'field2' => 'string',
            'field3' => ['serialized' => 1],
            'field4' => ['not_processed']
        ];
        $this->assertEquals($expected, $dynamicRows->modifyData($data));
    }
}
