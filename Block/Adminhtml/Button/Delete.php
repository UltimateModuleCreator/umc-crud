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

namespace Umc\Crud\Block\Adminhtml\Button;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Umc\Crud\Ui\EntityUiConfig;
use Umc\Crud\Ui\EntityUiManagerInterface;

class Delete implements ButtonProviderInterface
{
    /**
     * @var RequestInterface
     */
    private $request;
    /**
     * @var EntityUiManagerInterface
     */
    private $entityUiManager;
    /**
     * @var UrlInterface
     */
    private $url;
    /**
     * @var EntityUiConfig
     */
    private $uiConfig;

    /**
     * Delete constructor.
     * @param RequestInterface $request
     * @param EntityUiManagerInterface $entityUiManager
     * @param EntityUiConfig $uiConfig
     * @param UrlInterface $url
     */
    public function __construct(
        RequestInterface $request,
        EntityUiManagerInterface $entityUiManager,
        EntityUiConfig $uiConfig,
        UrlInterface $url
    ) {
        $this->request = $request;
        $this->entityUiManager = $entityUiManager;
        $this->uiConfig = $uiConfig;
        $this->url = $url;
    }

    /**
     * @inheritDoc
     */
    public function getButtonData()
    {
        $data = [];
        if ($this->getEntityId()) {
            $data = [
                'label' => $this->uiConfig->getDeleteLabel(),
                'class' => 'delete',
                'on_click' => 'deleteConfirm(\'' .
                    $this->uiConfig->getDeleteMessage() . '\', \'' . $this->getDeleteUrl() . '\', {"data": {}})',
                'sort_order' => 20,
            ];
        }
        return $data;
    }

    /**
     * @return int|null
     */
    private function getEntityId(): ?int
    {
        try {
            return $this->entityUiManager->get(
                (int)$this->request->getParam($this->uiConfig->getRequestParamName(), 0)
            )->getId();
        } catch (NoSuchEntityException $e) {
            return null;
        }
    }

    /**
     * @return string
     */
    private function getDeleteUrl(): string
    {
        return $this->url->getUrl(
            '*/*/delete',
            [$this->uiConfig->getRequestParamName() => $this->getEntityId()]
        );
    }
}
