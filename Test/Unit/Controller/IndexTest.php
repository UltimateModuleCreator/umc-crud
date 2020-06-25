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
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\View\Page\Config;
use Magento\Framework\View\Page\Title;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Controller\Adminhtml\Index;
use Umc\Crud\Ui\EntityUiConfig;

class IndexTest extends TestCase
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
     * @var Page | MockObject
     */
    private $resultPage;
    /**
     * @var Config | MockObject
     */
    private $pageConfig;
    /**
     * @var Title | MockObject
     */
    private $pageTitle;
    /**
     * @var ResultFactory | MockObject
     */
    private $resultFactory;
    /**
     * @var Index
     */
    private $index;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->context = $this->createMock(Context::class);
        $this->uiConfig = $this->createMock(EntityUiConfig::class);
        $this->resultPage = $this->createMock(Page::class);
        $this->pageConfig = $this->createMock(Config::class);
        $this->pageTitle = $this->createMock(Title::class);
        $this->resultFactory = $this->createMock(ResultFactory::class);
        $this->context->method('getResultFactory')->willReturn($this->resultFactory);
        $this->pageConfig->method('getTitle')->willReturn($this->pageTitle);
        $this->resultFactory->method('create')->willReturn($this->resultPage);
        $this->resultPage->method('getConfig')->willReturn($this->pageConfig);
        $this->index = new Index(
            $this->context,
            $this->uiConfig
        );
    }

    /**
     * @covers \Umc\Crud\Controller\Adminhtml\Index::execute
     * @covers \Umc\Crud\Controller\Adminhtml\Index::_isAllowed
     * @covers \Umc\Crud\Controller\Adminhtml\Index::__construct
     */
    public function testExecute()
    {
        $this->uiConfig->expects($this->once())->method('getMenuItem')->willReturn('SelectedMenu');
        $this->uiConfig->expects($this->once())->method('getListPageTitle')->willReturn('PageTitle');
        $this->resultPage->expects($this->once())->method('setActiveMenu')->with('SelectedMenu');
        $this->pageTitle->expects($this->once())->method('prepend')->with('PageTitle');
        $this->assertEquals($this->resultPage, $this->index->execute());
    }
}
