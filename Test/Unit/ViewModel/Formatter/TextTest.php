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

use Magento\Framework\Escaper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\ViewModel\Formatter\Text;

class TextTest extends TestCase
{
    /**
     * @var Escaper | MockObject
     */
    private $escaper;
    /**
     * @var Text
     */
    private $text;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->escaper = $this->createMock(Escaper::class);
        $this->text = new Text($this->escaper);
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter\Text::formatHtml
     * @covers \Umc\Crud\ViewModel\Formatter\Text::__construct
     */
    public function testFormatHtml()
    {
        $this->escaper->expects($this->once())->method('escapeHtml')->willReturn('escaped');
        $this->assertEquals('escaped', $this->text->formatHtml('value'));
    }
}
