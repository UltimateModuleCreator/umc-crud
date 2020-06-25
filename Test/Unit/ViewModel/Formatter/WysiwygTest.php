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

namespace Umc\Crud\Test\Unit\ViewModel\Formatter;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\ViewModel\Formatter\Wysiwyg;

class WysiwygTest extends TestCase
{
    /**
     * @var \Zend_Filter_Interface | MockObject
     */
    private $filter;
    /**
     * @var Wysiwyg
     */
    private $wysiwyg;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->filter = $this->createMock(\Zend_Filter_Interface::class);
        $this->wysiwyg = new Wysiwyg($this->filter);
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter\Wysiwyg::formatHtml
     * @covers \Umc\Crud\ViewModel\Formatter\Wysiwyg::__construct
     */
    public function testFormatHtml()
    {
        $this->filter->expects($this->once())->method('filter')->willReturn('filtered');
        $this->assertEquals('filtered', $this->wysiwyg->formatHtml('value'));
    }
}
