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

use PHPUnit\Framework\TestCase;
use Umc\Crud\Ui\SaveDataProcessor\Multiselect;

class MultiselectTest extends TestCase
{
    /**
     * @covers \Umc\Crud\Ui\SaveDataProcessor\Multiselect
     */
    public function testModifyData()
    {
        $modifier = new Multiselect(['field1', 'field2', 'field3']);
        $data = [
            'field1' => [1, 2, 3],
            'field2' => '4,5,6',
            'dummy' => 'dummy'
        ];
        $expected = [
            'field1' => '1,2,3',
            'field2' => '4,5,6',
            'dummy' => 'dummy'
        ];
        $this->assertEquals($expected, $modifier->modifyData($data));
    }
}
