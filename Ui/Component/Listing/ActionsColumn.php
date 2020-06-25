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

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;
use Umc\Crud\Ui\EntityUiConfig;

class ActionsColumn extends Column
{
    /**
     * @var UrlInterface
     */
    private $urlBuilder;
    /**
     * @var EntityUiConfig
     */
    private $uiConfig;

    /**
     * ActionsColumn constructor.
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param UrlInterface $urlBuilder
     * @param EntityUiConfig $uiConfig
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        UrlInterface $urlBuilder,
        EntityUiConfig $uiConfig,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->uiConfig = $uiConfig;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        $param = $this->uiConfig->getRequestParamName();
        $nameAttribute = $this->uiConfig->getNameAttribute();
        foreach ($dataSource['data']['items'] as & $item) {
            $params = [$param => $item[$param] ?? null];
            $item[$this->getData('name')] = [
                'edit' => [
                    'href' => $this->urlBuilder->getUrl($this->uiConfig->getEditUrlPath(), $params),
                    'label' => __('Edit')->render()
                ],
                'delete' => [
                    'href' => $this->urlBuilder->getUrl($this->uiConfig->getDeleteUrlPath(), $params),
                    'label' => __('Delete'),
                    'confirm' => [
                        'title' => __('Delete %1', $item[$nameAttribute] ?? '')->render(),
                        'message' => $this->uiConfig->getDeleteMessage()
                    ],
                    'post' => true
                ]
            ];
        }
        return $dataSource;
    }
}
