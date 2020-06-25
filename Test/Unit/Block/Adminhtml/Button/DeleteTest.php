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

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\UrlInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Block\Adminhtml\Button\Delete;
use Umc\Crud\Ui\EntityUiConfig;
use Umc\Crud\Ui\EntityUiManagerInterface;

class DeleteTest extends TestCase
{
    /**
     * @var RequestInterface | MockObject
     */
    private $request;
    /**
     * @var EntityUiManagerInterface | MockObject
     */
    private $entityUiManager;
    /**
     * @var EntityUiConfig | MockObject
     */
    private $uiConfig;
    /**
     * @var UrlInterface | MockObject
     */
    private $url;
    /**
     * @var Delete
     */
    private $delete;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->request = $this->createMock(RequestInterface::class);
        $this->entityUiManager = $this->createMock(EntityUiManagerInterface::class);
        $this->uiConfig = $this->createMock(EntityUiConfig::class);
        $this->url = $this->createMock(UrlInterface::class);
        $this->delete = new Delete(
            $this->request,
            $this->entityUiManager,
            $this->uiConfig,
            $this->url
        );
    }

    /**
     * @covers \Umc\Crud\Block\Adminhtml\Button\Delete::getButtonData
     * @covers \Umc\Crud\Block\Adminhtml\Button\Delete::getDeleteUrl
     * @covers \Umc\Crud\Block\Adminhtml\Button\Delete::getEntityId
     * @covers \Umc\Crud\Block\Adminhtml\Button\Delete::__construct
     */
    public function testGetButtonData()
    {
        $entity = $this->createMock(AbstractModel::class);
        $entity->method('getId')->willReturn(1);
        $this->entityUiManager->method('get')->willReturn($entity);
        $this->url->expects($this->once())->method('getUrl')->willReturn('url');
        $result = $this->delete->getButtonData();
        $this->assertArrayHasKey('label', $result);
        $this->assertArrayHasKey('on_click', $result);
    }

    /**
     * @covers \Umc\Crud\Block\Adminhtml\Button\Delete::getButtonData
     * @covers \Umc\Crud\Block\Adminhtml\Button\Delete::getEntityId
     * @covers \Umc\Crud\Block\Adminhtml\Button\Delete::__construct
     */
    public function testGetButtonNoEntity()
    {
        $this->entityUiManager->method('get')->willThrowException(
            $this->createMock(NoSuchEntityException::class)
        );
        $this->url->expects($this->never())->method('getUrl');
        $this->assertEquals([], $this->delete->getButtonData());
    }
}
