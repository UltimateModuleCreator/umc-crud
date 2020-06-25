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

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\ViewModel\Formatter\Date;

class DateTest extends TestCase
{
    /**
     * @var TimezoneInterface | MockObject
     */
    private $localeDate;
    /**
     * @var Date
     */
    private $date;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->localeDate = $this->createMock(TimezoneInterface::class);
        $this->date = new Date($this->localeDate);
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter\Date::formatHtml
     * @covers \Umc\Crud\ViewModel\Formatter\Date::__construct
     */
    public function testFormatHtml()
    {
        $this->localeDate->expects($this->once())->method('formatDateTime')
            ->with(new \DateTime('1984-04-04'), \IntlDateFormatter::LONG, \IntlDateFormatter::NONE, null, null)
            ->willReturn('formatted');
        $this->assertEquals('formatted', $this->date->formatHtml('1984-04-04'));
    }

    /**
     * @covers \Umc\Crud\ViewModel\Formatter\Date::formatHtml
     * @covers \Umc\Crud\ViewModel\Formatter\Date::__construct
     */
    public function testFormatHtmlWithParams()
    {
        $this->localeDate->expects($this->once())->method('formatDateTime')
            ->with(new \DateTime('1984-04-04'), \IntlDateFormatter::SHORT, \IntlDateFormatter::SHORT, null, null)
            ->willReturn('formatted');
        $this->assertEquals(
            'formatted',
            $this->date->formatHtml(
                '1984-04-04',
                [
                    'format' => \IntlDateFormatter::SHORT,
                    'show_time' => true
                ]
            )
        );
    }
}
