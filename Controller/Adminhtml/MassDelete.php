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
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Ui\Component\MassAction\Filter;
use Umc\Crud\Ui\CollectionProviderInterface;
use Umc\Crud\Ui\EntityUiConfig;
use Umc\Crud\Ui\EntityUiManagerInterface;

class MassDelete extends Action implements HttpPostActionInterface
{
    /**
     * @var Filter
     */
    private $filter;
    /**
     * @var CollectionProviderInterface
     */
    private $collectionProvider;
    /**
     * @var EntityUiConfig
     */
    private $uiConfig;
    /**
     * @var EntityUiManagerInterface
     */
    private $uiManager;

    /**
     * MassAction constructor.
     * @param Context $context
     * @param Filter $filter
     * @param CollectionProviderInterface $collectionProvider
     * @param EntityUiConfig $uiConfig
     * @param EntityUiManagerInterface $uiManager
     */
    public function __construct(
        Context $context,
        Filter $filter,
        CollectionProviderInterface $collectionProvider,
        EntityUiConfig $uiConfig,
        EntityUiManagerInterface $uiManager
    ) {
        $this->filter = $filter;
        $this->collectionProvider = $collectionProvider;
        $this->uiConfig = $uiConfig;
        $this->uiManager = $uiManager;
        parent::__construct($context);
    }

    /**
     * execute action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    public function execute()
    {
        try {
            $collection = $this->filter->getCollection($this->collectionProvider->getCollection());
            $collectionSize = $collection->getSize();
            foreach ($collection as $entity) {
                $this->uiManager->delete((int)$entity->getId());
            }
            $this->messageManager->addSuccessMessage(
                $this->uiConfig->getMassDeleteSuccessMessage($collectionSize)
            );
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addErrorMessage($this->uiConfig->getMassDeleteErrorMessage());
        }
        /** @var \Magento\Framework\Controller\Result\Redirect $redirectResult */
        $redirectResult = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $redirectResult->setPath('*/*/index');
        return $redirectResult;
    }
}
