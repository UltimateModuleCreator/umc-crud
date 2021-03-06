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

namespace Umc\Crud\Test\Unit\Block\Adminhtml\Button;

use PHPUnit\Framework\TestCase;
use Umc\Crud\Block\Adminhtml\Button\Reset;

class ResetTest extends TestCase
{
    /**
     * @covers \Umc\Crud\Block\Adminhtml\Button\Reset::getButtonData
     */
    public function testGetButtonData()
    {
        $reset = new Reset();
        $result = $reset->getButtonData();
        $this->assertEquals(__('Reset'), $result['label']);
        $this->assertEquals("location.reload();", $result['on_click']);
    }
}
