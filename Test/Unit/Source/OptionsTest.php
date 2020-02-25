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

use PHPUnit\Framework\TestCase;
use Umc\Crud\Source\Options;

class OptionsTest extends TestCase
{
    /**
     * @covers \Umc\Crud\Source\Options::toOptionArray
     * @covers \Umc\Crud\Source\Options::__construct
     */
    public function testToOptionArray()
    {
        $input = [
            [
                'label' => 'label1',
                'value' => 'value1'
            ],
            [
                'label' => 'label2',
                'value' => 'value2',
                'disabled' => true
            ],
            [
                'value' => 'value3',
                'disabled' => false
            ],
            [
                'label' => 'label4',
            ],
            [
                'label' => 'label5',
                'value' => 'value5',
                'disabled' => false
            ],
            'dummy'
        ];
        $expected = [
            [
                'label' => 'label1',
                'value' => 'value1'
            ],
            [
                'label' => 'label5',
                'value' => 'value5'
            ],
        ];
        $options = new Options($input);
        $this->assertEquals($expected, $options->toOptionArray());
    }
}
