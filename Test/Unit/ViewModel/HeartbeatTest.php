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

use Magento\Framework\UrlInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\ViewModel\Heartbeat;

class HeartbeatTest extends TestCase
{
    /**
     * @var UrlInterface | MockObject
     */
    private $url;
    /**
     * @var Heartbeat
     */
    private $heartbeat;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->url = $this->createMock(UrlInterface::class);
        $this->heartbeat = new Heartbeat(
            $this->url
        );
    }

    /**
     * @covers \Umc\Crud\ViewModel\Heartbeat
     */
    public function testGetUrl()
    {
        $this->url->expects($this->once())->method('getUrl')->with('crud/heartbeat/index', null)->willReturn('url');
        $this->assertEquals('url', $this->heartbeat->getUrl());
    }
}
