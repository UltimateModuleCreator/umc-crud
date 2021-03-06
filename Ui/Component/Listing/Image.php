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

namespace Umc\Crud\Ui\Component\Listing;

use Umc\Crud\Model\FileInfo;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Umc\Crud\Ui\EntityUiConfig;

class Image extends Column
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var EntityUiConfig
     */
    private $uiConfig;
    /**
     * @var FileInfo
     */
    private $fileInfo;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param StoreManagerInterface $storeManager
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        StoreManagerInterface $storeManager,
        EntityUiConfig $uiConfig,
        FileInfo $fileInfo,
        array $components = [],
        array $data = []
    ) {
        $this->storeManager = $storeManager;
        $this->uiConfig = $uiConfig;
        $this->fileInfo = $fileInfo;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * @param array $dataSource
     * @return array
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');
            foreach ($dataSource['data']['items'] as & $item) {
                $url = $this->getUrl($item[$fieldName] ?? '');
                $item[$fieldName . '_src'] = $url;
                $item[$fieldName . '_alt'] = $this->getAlt($item) ?: '';
                $item[$fieldName . '_orig_src'] = $url;
                $item[$fieldName . '_link'] = $this->getEditUrl($item);
            }
        }
        return $dataSource;
    }

    /**
     * @param $value
     * @return string
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getUrl($value)
    {
        if (!$value) {
            return '';
        }
        if ($this->fileInfo->isBeginsWithMediaDirectoryPath($value)) {
            return $value;
        }
        $store = $this->storeManager->getStore();
        $mediaBaseUrl = $store->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );
        return $mediaBaseUrl . ltrim($this->fileInfo->getBaseFilePath(), '/') . '/' . ltrim($value, '/');
    }

    /**
     * @param $item
     * @return string
     */
    private function getEditUrl($item)
    {
        $base = $this->uiConfig->getEditUrlPath();
        $idParam = $this->uiConfig->getRequestParamName();
        $params = [$idParam => $item[$idParam] ?? null];
        return $this->context->getUrl($base, $params);
    }

    /**
     * @param array $row
     *
     * @return null|string
     */
    private function getAlt($row)
    {
        $altField = $this->uiConfig->getNameAttribute();
        return $altField && isset($row[$altField]) ? $row[$altField] : null;
    }
}
