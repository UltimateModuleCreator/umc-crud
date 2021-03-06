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

namespace Umc\Crud\Test\Unit\Controller\Adminhtml;

use Magento\Backend\App\Action\Context;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Message\Manager;
use Magento\Framework\View\Element\AbstractBlock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Controller\Adminhtml\Delete;
use Umc\Crud\Ui\EntityUiConfig;
use Umc\Crud\Ui\EntityUiManagerInterface;

class DeleteTest extends TestCase
{
    /**
     * @var Context | MockObject
     */
    private $context;
    /**
     * @var EntityUiConfig | MockObject
     */
    private $uiConfig;
    /**
     * @var EntityUiManagerInterface | MockObject
     */
    private $uiManager;
    /**
     * @var RequestInterface | MockObject
     */
    private $request;
    /**
     * @var Redirect | MockObject
     */
    private $result;
    /**
     * @var ResultFactory | MockObject
     */
    private $resultFactory;
    /**
     * @var Manager | MockObject
     */
    private $messageManager;
    /**
     * @var Delete
     */
    private $delete;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->context = $this->createMock(Context::class);
        $this->uiConfig = $this->createMock(EntityUiConfig::class);
        $this->uiManager = $this->createMock(EntityUiManagerInterface::class);
        $this->request = $this->createMock(RequestInterface::class);
        $this->result = $this->createMock(Redirect::class);
        $this->resultFactory = $this->createMock(ResultFactory::class);
        $this->messageManager = $this->createMock(Manager::class);
        $this->context->method('getResultFactory')->willReturn($this->resultFactory);
        $this->context->method('getRequest')->willReturn($this->request);
        $this->context->method('getMessageManager')->willReturn($this->messageManager);
        $this->resultFactory->method('create')->willReturn($this->result);
        $this->delete = new Delete(
            $this->context,
            $this->uiConfig,
            $this->uiManager
        );
    }

    /**
     * @covers \Umc\Crud\Controller\Adminhtml\Delete::execute
     * @covers \Umc\Crud\Controller\Adminhtml\Delete::__construct
     */
    public function testExecute()
    {
        $this->request->method('getParam')->willReturn(1);
        $this->uiManager->expects($this->once())->method('delete')->with(1);
        $this->messageManager->expects($this->once())->method('addSuccessMessage');
        $this->messageManager->expects($this->never())->method('addErrorMessage');
        $this->uiConfig->expects($this->once())->method('getDeleteSuccessMessage');
        $this->result->expects($this->once())->method('setPath')->with('*/*/');
        $this->assertEquals($this->result, $this->delete->execute());
    }

    /**
     * @covers \Umc\Crud\Controller\Adminhtml\Delete::execute
     * @covers \Umc\Crud\Controller\Adminhtml\Delete::__construct
     */
    public function testExecuteNoId()
    {
        $this->request->method('getParam')->willReturn(null);
        $this->uiManager->expects($this->never())->method('delete');
        $this->messageManager->expects($this->never())->method('addSuccessMessage');
        $this->messageManager->expects($this->once())->method('addErrorMessage');
        $this->uiConfig->expects($this->once())->method('getDeleteMissingEntityMessage');
        $this->result->expects($this->once())->method('setPath')->with('*/*/');
        $this->assertEquals($this->result, $this->delete->execute());
    }

    /**
     * @covers \Umc\Crud\Controller\Adminhtml\Delete::execute
     * @covers \Umc\Crud\Controller\Adminhtml\Delete::__construct
     */
    public function testExecuteNoEntity()
    {
        $this->request->method('getParam')->willReturn(1);
        $this->uiManager->expects($this->once())->method('delete')->with(1)->willThrowException(
            $this->createMock(NoSuchEntityException::class)
        );
        $this->messageManager->expects($this->never())->method('addSuccessMessage');
        $this->messageManager->expects($this->once())->method('addErrorMessage');
        $this->uiConfig->expects($this->once())->method('getDeleteMissingEntityMessage');
        $this->result->expects($this->once())->method('setPath')->with('*/*/');
        $this->assertEquals($this->result, $this->delete->execute());
    }

    /**
     * @covers \Umc\Crud\Controller\Adminhtml\Delete::execute
     * @covers \Umc\Crud\Controller\Adminhtml\Delete::__construct
     */
    public function testExecuteLocalizedException()
    {
        $this->request->method('getParam')->willReturn(1);
        $this->uiManager->expects($this->once())->method('delete')->with(1)->willThrowException(
            $this->createMock(LocalizedException::class)
        );
        $this->messageManager->expects($this->never())->method('addSuccessMessage');
        $this->messageManager->expects($this->once())->method('addErrorMessage');
        $this->result->expects($this->once())->method('setPath')->with('*/*/edit');
        $this->assertEquals($this->result, $this->delete->execute());
    }

    /**
     * @covers \Umc\Crud\Controller\Adminhtml\Delete::execute
     * @covers \Umc\Crud\Controller\Adminhtml\Delete::__construct
     */
    public function testExecuteLGeneralException()
    {
        $this->request->method('getParam')->willReturn(1);
        $this->uiManager->expects($this->once())->method('delete')->with(1)->willThrowException(new \Exception());
        $this->messageManager->expects($this->never())->method('addSuccessMessage');
        $this->messageManager->expects($this->once())->method('addErrorMessage');
        $this->uiConfig->expects($this->once())->method('getGeneralDeleteErrorMessage');
        $this->result->expects($this->once())->method('setPath')->with('*/*/edit');
        $this->assertEquals($this->result, $this->delete->execute());
    }
}
