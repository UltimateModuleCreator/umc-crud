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
use Umc\Crud\Ui\EntityUiManagerInterface;

class Edit extends Action implements HttpGetActionInterface
{
    /**
     * @var EntityUiManagerInterface
     */
    private $entityUiManager;
    /**
     * @var EntityUiConfig
     */
    private $uiConfig;

    /**
     * Edit constructor.
     * @param Context $context
     * @param EntityUiManagerInterface $entityUiManager
     * @param EntityUiConfig $uiConfig
     */
    public function __construct(Context $context, EntityUiManagerInterface $entityUiManager, EntityUiConfig $uiConfig)
    {
        $this->entityUiManager = $entityUiManager;
        $this->uiConfig = $uiConfig;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Backend\Model\View\Result\Page
     */
    public function execute()
    {
        $id = (int)$this->getRequest()->getParam($this->uiConfig->getRequestParamName());
        $entity = $this->entityUiManager->get($id);
        /** @var \Magento\Backend\Model\View\Result\Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $activeMenu = $this->uiConfig->getMenuItem();
        if ($activeMenu) {
            $resultPage->setActiveMenu($activeMenu);
        }
        $resultPage->getConfig()->getTitle()->prepend($this->uiConfig->getListPageTitle());
        if (!$entity->getId()) {
            $resultPage->getConfig()->getTitle()->prepend($this->uiConfig->getNewLabel());
        } else {
            $resultPage->getConfig()->getTitle()->prepend($entity->getData($this->uiConfig->getNameAttribute()));
        }
        return $resultPage;
    }
}
