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
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Option\ArrayInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\ViewModel\Formatter\Options;

class OptionsTest extends TestCase
{
    /**
     * @var ObjectManagerInterface | MockObject
     */
    private $objectManager;
    /**
     * @var Escaper | MockObject
     */
    private $escaper;
    /**
     * @var Options
     */
    private $options;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->objectManager = $this->createMock(ObjectManagerInterface::class);
        $this->escaper = $this->createMock(Escaper::class);
        $this->options = new Options(
            $this->objectManager,
            $this->escaper
        );
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter\Options::formatHtml
     * @covers \Umc\Crud\ViewModel\Formatter\Options::getSource
     * @covers \Umc\Crud\ViewModel\Formatter\Options::__construct
     */
    public function testFormatHtmlNoOptions()
    {
        $this->assertEquals('', $this->options->formatHtml('1'));
        $this->assertEquals('N/A', $this->options->formatHtml('1', ['default' => 'N/A']));
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter\Options::formatHtml
     * @covers \Umc\Crud\ViewModel\Formatter\Options::getSource
     * @covers \Umc\Crud\ViewModel\Formatter\Options::__construct
     */
    public function testFormatHtmlWithSource()
    {
        $this->objectManager->expects($this->once())->method('get')->with('SourceModel')
            ->willReturn(
                $this->getSourceMock([
                    ['label' => 'label 1', 'value' => 1],
                    ['label' => 'label 2', 'value' => 2],
                    ['label' => 'label 3', 'value' => 3],
                    ['label' => 'label 4', 'value' => 4],
                ])
            );
        $this->escaper->method('escapeHtml')->willReturnArgument(0);
        $this->assertEquals('label 1, label 2', $this->options->formatHtml([1, 2], ['source' => 'SourceModel']));
        $this->assertEquals('label 1, label 4', $this->options->formatHtml([4, 1], ['source' => 'SourceModel']));
        $this->assertEquals('label 3', $this->options->formatHtml(3, ['source' => 'SourceModel']));
        $this->assertEquals('label 4', $this->options->formatHtml([4, 8, 9], ['source' => 'SourceModel']));
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter\Options::formatHtml
     * @covers \Umc\Crud\ViewModel\Formatter\Options::getSource
     * @covers \Umc\Crud\ViewModel\Formatter\Options::getOptions
     * @covers \Umc\Crud\ViewModel\Formatter\Options::__construct
     */
    public function testFormatHtmlWithOptions()
    {
        $this->objectManager->expects($this->never())->method('get');
        $arguments = [
            'options' => [
                ['label' => 'label 1', 'value' => 1],
                ['label' => 'label 2', 'value' => 2],
                ['label' => 'label 3', 'value' => 3],
                ['label' => 'label 4', 'value' => 4]
            ]
        ];
        $this->escaper->method('escapeHtml')->willReturnArgument(0);
        $this->assertEquals('label 1, label 2', $this->options->formatHtml([1, 2], $arguments));
        $this->assertEquals('label 1, label 4', $this->options->formatHtml([4, 1], $arguments));
        $this->assertEquals('label 3', $this->options->formatHtml(3, $arguments));
        $this->assertEquals('label 4', $this->options->formatHtml([4, 8, 9], $arguments));
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter\Options::formatHtml
     * @covers \Umc\Crud\ViewModel\Formatter\Options::getSource
     * @covers \Umc\Crud\ViewModel\Formatter\Options::__construct
     */
    public function testFormatHtmlWithSourceError()
    {
        $this->objectManager->expects($this->once())->method('get')->with('SourceModel')
            ->willReturn(new \stdClass());
        $this->expectException(\InvalidArgumentException::class);
        $this->options->formatHtml(1, ['source' => 'SourceModel']);
    }

    /**
     * @param $values
     * @return MockObject
     * @throws \ReflectionException
     */
    private function getSourceMock($values)
    {
        $source = $this->createMock(ArrayInterface::class);
        $source->method('toOptionArray')->willReturn($values);
        return $source;
    }
}
