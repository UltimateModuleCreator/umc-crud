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
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\View\Element\AbstractBlock;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Controller\Adminhtml\InlineEdit;
use Umc\Crud\Ui\EntityUiManagerInterface;
use Umc\Crud\Ui\SaveDataProcessorInterface;

class InlineEditTest extends TestCase
{
    /**
     * @var Context | MockObject
     */
    private $context;
    /**
     * @var SaveDataProcessorInterface | MockObject
     */
    private $dataProcessor;
    /**
     * @var EntityUiManagerInterface | MockObject
     */
    private $entityUiManager;
    /**
     * @var ResultFactory | MockObject
     */
    private $resultFactory;
    /**
     * @var Json | MockObject
     */
    private $resultJson;
    /**
     * @var RequestInterface | MockObject
     */
    private $request;
    /**
     * @var InlineEdit
     */
    private $inlineEdit;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->context = $this->createMock(Context::class);
        $this->dataProcessor = $this->createMock(SaveDataProcessorInterface::class);
        $this->entityUiManager = $this->createMock(EntityUiManagerInterface::class);
        $this->resultFactory = $this->createMock(ResultFactory::class);
        $this->resultJson = $this->createMock(Json::class);
        $this->context->method('getResultFactory')->willReturn($this->resultFactory);
        $this->resultFactory->method('create')->willReturn($this->resultJson);
        $this->request = $this->createMock(RequestInterface::class);
        $this->context->method('getRequest')->willReturn($this->request);
        $this->inlineEdit = new InlineEdit(
            $this->context,
            $this->dataProcessor,
            $this->entityUiManager
        );
    }

    /**
     * @covers \Umc\Crud\Controller\Adminhtml\InlineEdit::execute
     * @covers \Umc\Crud\Controller\Adminhtml\InlineEdit::__construct
     */
    public function testExecuteNoAjaxCall()
    {
        $this->request->method('getParam')->with('isAjax')->willReturn(null);
        $this->dataProcessor->expects($this->never())->method('modifyData');
        $this->entityUiManager->expects($this->never())->method('save');
        $this->resultJson->expects($this->once())->method('setData')->with([
            'error' => false,
            'messages' => []
        ]);
        $this->assertEquals($this->resultJson, $this->inlineEdit->execute());
    }

    /**
     * @covers \Umc\Crud\Controller\Adminhtml\InlineEdit::execute
     * @covers \Umc\Crud\Controller\Adminhtml\InlineEdit::__construct
     */
    public function testExecuteNoItems()
    {
        $this->request->method('getParam')->willReturnMap([
            ['isAjax', null, 1],
            ['items', [], []]
        ]);
        $this->dataProcessor->expects($this->never())->method('modifyData');
        $this->entityUiManager->expects($this->never())->method('save');
        $this->resultJson->expects($this->once())->method('setData')->with([
            'error' => true,
            'messages' => [__('Please correct the data sent.')]
        ]);
        $this->assertEquals($this->resultJson, $this->inlineEdit->execute());
    }

    /**
     * @covers \Umc\Crud\Controller\Adminhtml\InlineEdit::execute
     * @covers \Umc\Crud\Controller\Adminhtml\InlineEdit::__construct
     */
    public function testExecuteWithError()
    {
        $items = [
            1 => [
                'dummy_data'
            ]
        ];
        $this->request->method('getParam')->willReturnMap([
            ['isAjax', null, 1],
            ['items', [], $items]
        ]);
        $this->entityUiManager->method('get')->willThrowException(new \Exception('Error'));
        $this->dataProcessor->expects($this->never())->method('modifyData');
        $this->entityUiManager->expects($this->never())->method('save');
        $this->resultJson->expects($this->once())->method('setData')->with([
            'error' => true,
            'messages' => ['[Error: 1] Error']
        ]);
        $this->assertEquals($this->resultJson, $this->inlineEdit->execute());
    }

    /**
     * @covers \Umc\Crud\Controller\Adminhtml\InlineEdit::execute
     * @covers \Umc\Crud\Controller\Adminhtml\InlineEdit::__construct
     */
    public function testExecute()
    {
        $entity = $this->createMock(AbstractModel::class);
        $entity->method('getData')->willReturn([]);
        $items = [
            1 => [
                'dummy_data'
            ],
            2 => [
                'dummy'
            ]
        ];
        $this->request->method('getParam')->willReturnMap([
            ['isAjax', null, 1],
            ['items', [], $items]
        ]);
        $this->entityUiManager->method('get')->willReturn($entity);
        $this->dataProcessor->expects($this->exactly(2))->method('modifyData')->willReturnArgument(0);
        $this->entityUiManager->expects($this->exactly(2))->method('save');
        $this->resultJson->expects($this->once())->method('setData')->with([
            'error' => false,
            'messages' => []
        ]);
        $this->assertEquals($this->resultJson, $this->inlineEdit->execute());
    }
}
