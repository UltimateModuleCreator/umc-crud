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
use Umc\Crud\Ui\Form\DataModifier\Multiselect;

class MultiselectTest extends TestCase
{
    /**
     * @covers \Umc\Crud\Ui\Form\DataModifier\Multiselect
     */
    public function testModifyData()
    {
        $model = $this->createMock(AbstractModel::class);
        $modifier = new Multiselect(['field1', 'field2', 'field3', 'field4']);
        $data = [
            'field1' => '1,2,3',
            'field2' => [3, 4, 5],
            'field3' => null,
            'dummy' => 'dummy'
        ];
        $expected = [
            'field1' => [1, 2, 3],
            'field2' => [3, 4, 5],
            'field3' => [],
            'dummy' => 'dummy'
        ];
        $this->assertEquals($expected, $modifier->modifyData($model, $data));
    }
}
