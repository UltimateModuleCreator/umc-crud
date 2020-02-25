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
use PHPUnit\Framework\TestCase;
use Umc\Crud\Ui\Form\DataModifier\CompositeDataModifier;
use Umc\Crud\Ui\Form\DataModifierInterface;

class CompositeDataModifierTest extends TestCase
{
    /**
     * @covers \Umc\Crud\Ui\Form\DataModifier\CompositeDataModifier::modifyData
     * @covers \Umc\Crud\Ui\Form\DataModifier\CompositeDataModifier::__construct
     */
    public function testModifyData()
    {
        $model = $this->createMock(AbstractModel::class);
        $processor1 = $this->createMock(DataModifierInterface::class);
        $processor1->method('modifyData')->willReturnCallback(
            function (AbstractModel $model, array $data) {
                $data['element1'] = ($data['element1'] ?? '') . '_processed1';
                return $data;
            }
        );
        $processor2 = $this->createMock(DataModifierInterface::class);
        $processor2->method('modifyData')->willReturnCallback(
            function (AbstractModel $model, array $data) {
                $data['element1'] = ($data['element1'] ?? '') . '_processed2';
                $data['element2'] = ($data['element2'] ?? '') . '_processed2';
                return $data;
            }
        );
        $compositeProcessor = new CompositeDataModifier([$processor1, $processor2]);
        $data = [
            'element1' => 'value1',
            'element2' => 'value2',
            'element3' => 'value3'
        ];
        $expected = [
            'element1' => 'value1_processed1_processed2',
            'element2' => 'value2_processed2',
            'element3' => 'value3'
        ];
        $this->assertEquals($expected, $compositeProcessor->modifyData($model, $data));
    }

    /**
     * @covers \Umc\Crud\Ui\Form\DataModifier\CompositeDataModifier::__construct
     */
    public function testGetConstructor()
    {
        $this->expectException(\InvalidArgumentException::class);
        new CompositeDataModifier(['string value']);
    }
}
