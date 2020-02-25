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
use Umc\Crud\Ui\SaveDataProcessor\NullProcessor;

class NullProcessorTest extends TestCase
{
    /**
     * @covers \Umc\Crud\Ui\SaveDataProcessor\NullProcessor
     */
    public function testModifyData()
    {
        $data = ['dummy'];
        $this->assertEquals($data, (new NullProcessor())->modifyData($data));
    }
}
