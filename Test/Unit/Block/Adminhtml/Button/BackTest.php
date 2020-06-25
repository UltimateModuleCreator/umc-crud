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

use Magento\Framework\UrlInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Block\Adminhtml\Button\Back;
use Umc\Crud\Ui\EntityUiConfig;

class BackTest extends TestCase
{
    /**
     * @var UrlInterface | MockObject
     */
    private $url;
    /**
     * @var EntityUiConfig | MockObject
     */
    private $uiConfig;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->url = $this->createMock(UrlInterface::class);
        $this->uiConfig = $this->createMock(EntityUiConfig::class);
    }

    /**
     * @covers \Umc\Crud\Block\Adminhtml\Button\Back::getButtonData
     * @covers \Umc\Crud\Block\Adminhtml\Button\Back::getLabel
     * @covers \Umc\Crud\Block\Adminhtml\Button\Back::__construct
     */
    public function testGetButtonData()
    {
        $back = new Back($this->url, $this->uiConfig);
        $this->uiConfig->method('getBackLabel')->willReturn('Back to list');
        $this->url->method('getUrl')->willReturn('url');
        $result = $back->getButtonData();
        $this->assertEquals('Back to list', $result['label']);
        $this->assertEquals("location.href = 'url';", $result['on_click']);
    }

    /**
     * @covers \Umc\Crud\Block\Adminhtml\Button\Back::getButtonData
     * @covers \Umc\Crud\Block\Adminhtml\Button\Back::getLabel
     * @covers \Umc\Crud\Block\Adminhtml\Button\Back::__construct
     */
    public function testGetButtonDataNoUiConfig()
    {
        $back = new Back($this->url);
        $this->url->method('getUrl')->willReturn('url');
        $result = $back->getButtonData();
        $this->assertEquals('Back', $result['label']);
        $this->assertEquals("location.href = 'url';", $result['on_click']);
    }
}
