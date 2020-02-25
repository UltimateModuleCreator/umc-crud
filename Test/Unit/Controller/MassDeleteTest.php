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
use Magento\Framework\Data\Collection\AbstractDb;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Message\Manager;
use Magento\Framework\Model\AbstractModel;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Ui\Component\MassAction\Filter;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Controller\Adminhtml\MassDelete;
use Umc\Crud\Ui\CollectionProviderInterface;
use Umc\Crud\Ui\EntityUiConfig;
use Umc\Crud\Ui\EntityUiManagerInterface;

class MassDeleteTest extends TestCase
{
    /**
     * @var ObjectManager
     */
    private $om;
    /**
     * @var Context | MockObject
     */
    private $context;
    /**
     * @var Filter | MockObject
     */
    private $filter;
    /**
     * @var CollectionProviderInterface | MockObject
     */
    private $collectionProvider;
    /**
     * @var EntityUiConfig | MockObject
     */
    private $uiConfig;
    /**
     * @var EntityUiManagerInterface | MockObject
     */
    private $uiManager;
    /**
     * @var ResultFactory | MockObject
     */
    private $resultFactory;
    /**
     * @var Redirect | MockObject
     */
    private $redirectResult;
    /**
     * @var Manager  | MockObject
     */
    private $messageManager;
    /**
     * @var AbstractDb | MockObject
     */
    private $collection;
    /**
     * @var MassDelete
     */
    private $massDelete;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->om = new ObjectManager($this);
        $this->context = $this->createMock(Context::class);
        $this->filter = $this->createMock(Filter::class);
        $this->collectionProvider = $this->createMock(CollectionProviderInterface::class);
        $this->uiConfig = $this->createMock(EntityUiConfig::class);
        $this->uiManager = $this->createMock(EntityUiManagerInterface::class);
        $this->messageManager = $this->createMock(Manager::class);
        $this->resultFactory = $this->createMock(ResultFactory::class);
        $this->redirectResult = $this->createMock(Redirect::class);
        $this->context->method('getResultFactory')->willReturn($this->resultFactory);
        $this->context->method('getMessageManager')->willReturn($this->messageManager);
        $this->resultFactory->method('create')->willReturn($this->redirectResult);
        $this->filter->method('getCollection')->willReturnArgument(0);
        $this->collection = $this->om->getCollectionMock(
            AbstractDb::class,
            [
                $this->createMOck(AbstractModel::class),
                $this->createMOck(AbstractModel::class)
            ]
        );
        $this->collectionProvider->method('getCollection')->willReturn($this->collection);
        $this->massDelete = new MassDelete(
            $this->context,
            $this->filter,
            $this->collectionProvider,
            $this->uiConfig,
            $this->uiManager
        );
    }

    /**
     * @covers \Umc\Crud\Controller\Adminhtml\MassDelete::execute
     * @covers \Umc\Crud\Controller\Adminhtml\MassDelete::__construct
     */
    public function testExecute()
    {
        $this->uiManager->expects($this->exactly(2))->method('delete');
        $this->messageManager->expects($this->once())->method('addSuccessMessage');
        $this->messageManager->expects($this->never())->method('addErrorMessage');
        $this->assertEquals($this->redirectResult, $this->massDelete->execute());
    }

    /**
     * @covers \Umc\Crud\Controller\Adminhtml\MassDelete::execute
     * @covers \Umc\Crud\Controller\Adminhtml\MassDelete::__construct
     */
    public function testExecuteWithLocalizedException()
    {
        $this->uiManager->expects($this->exactly(1))->method('delete')->willThrowException(
            $this->createMOck(LocalizedException::class)
        );
        $this->messageManager->expects($this->never())->method('addSuccessMessage');
        $this->messageManager->expects($this->once())->method('addErrorMessage');
        $this->assertEquals($this->redirectResult, $this->massDelete->execute());
    }

    /**
     * @covers \Umc\Crud\Controller\Adminhtml\MassDelete::execute
     * @covers \Umc\Crud\Controller\Adminhtml\MassDelete::__construct
     */
    public function testExecuteWithException()
    {
        $this->uiManager->method('delete')->willThrowException(new \Exception());
        $this->messageManager->expects($this->never())->method('addSuccessMessage');
        $this->messageManager->expects($this->once())->method('addErrorMessage');
        $this->assertEquals($this->redirectResult, $this->massDelete->execute());
    }
}
