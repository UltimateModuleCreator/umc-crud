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

namespace Umc\Crud\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Umc\Crud\Ui\EntityUiConfig;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * @var EntityUiConfig
     */
    private $uiConfig;

    /**
     * Index constructor.
     * @param Context $context
     * @param EntityUiConfig $uiConfig
     */
    public function __construct(Context $context, EntityUiConfig $uiConfig)
    {
        parent::__construct($context);
        $this->uiConfig = $uiConfig;
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $listMenuItem = $this->uiConfig->getMenuItem();
        if ($listMenuItem) {
            $resultPage->setActiveMenu($listMenuItem);
        }
        $pageTitle = $this->uiConfig->getListPageTitle();
        if ($pageTitle) {
            $resultPage->getConfig()->getTitle()->prepend($pageTitle);
        }
        return $resultPage;
    }
}
