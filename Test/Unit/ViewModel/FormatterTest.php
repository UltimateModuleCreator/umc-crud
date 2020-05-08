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

namespace Umc\Crud\Test\Unit\ViewModel;

use Magento\Framework\Escaper;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\ViewModel\Formatter;

class FormatterTest extends TestCase
{
    /**
     * @var Escaper | MockObject
     */
    private $escaper;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->escaper = $this->createMock(Escaper::class);
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter::formatHtml
     * @covers \Umc\Crud\ViewModel\Formatter::getFormatter
     * @covers \Umc\Crud\ViewModel\Formatter::__construct
     */
    public function testFormatHtml()
    {
        $formatter1 = $this->getFormatterMock();
        $formatter2 = $this->getFormatterMock();
        $formatter = new Formatter(
            [
                'type1' => $formatter1,
                'type2' => $formatter2,
            ],
            $this->escaper
        );
        $formatter1->expects($this->once())->method('formatHtml')->willReturn('formatted');
        $this->assertEquals('formatted', $formatter->formatHtml('value', ['type' => 'type1']));
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter::formatHtml
     * @covers \Umc\Crud\ViewModel\Formatter::getFormatter
     * @covers \Umc\Crud\ViewModel\Formatter::__construct
     */
    public function testFormatHtmlNoArgument()
    {
        $formatter1 = $this->getFormatterMock();
        $formatter2 = $this->getFormatterMock();
        $formatter = new Formatter(
            [
                'type1' => $formatter1,
                'type2' => $formatter2,
            ],
            $this->escaper
        );
        $formatter1->expects($this->never())->method('formatHtml');
        $this->escaper->expects($this->once())->method('escapeHtml')->willReturn('formatted');
        $this->assertEquals('formatted', $formatter->formatHtml('value', []));
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter::formatHtml
     * @covers \Umc\Crud\ViewModel\Formatter::getFormatter
     * @covers \Umc\Crud\ViewModel\Formatter::__construct
     */
    public function testFormatHtmlNoTypeConfigured()
    {
        $formatter1 = $this->getFormatterMock();
        $formatter2 = $this->getFormatterMock();
        $formatter = new Formatter(
            [
                'type1' => $formatter1,
                'type2' => $formatter2,
            ],
            $this->escaper
        );
        $this->expectException(\InvalidArgumentException::class);
        $formatter->formatHtml('value', ['type' => 'type3']);
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter::__construct
     */
    public function testConstruct()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Formatter(
            [
                'type1' => $this->getFormatterMock(),
                'type2' => 'string',
            ],
            $this->escaper
        );
    }

    /**
     * @return Formatter'FormatterInterface | MockObject
     * @throws \ReflectionException
     */
    private function getFormatterMock()
    {
        $formatter = $this->createMock(Formatter\FormatterInterface::class);
        return $formatter;
    }
}
