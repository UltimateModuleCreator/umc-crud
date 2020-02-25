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

use Magento\Framework\UrlInterface;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Umc\Crud\Ui\EntityUiConfig;

class Back implements ButtonProviderInterface
{
    /**
     * @var UrlInterface
     */
    private $url;
    /**
     * @var EntityUiConfig|null
     */
    private $uiConfig;

    /**
     * Back constructor.
     * @param UrlInterface $url
     * @param null|EntityUiConfig $uiConfig
     */
    public function __construct(UrlInterface $url, ?EntityUiConfig $uiConfig = null)
    {
        $this->url = $url;
        $this->uiConfig = $uiConfig;
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => $this->getLabel(),
            'on_click' => sprintf("location.href = '%s';", $this->url->getUrl('*/*/')),
            'class' => 'back',
            'sort_order' => 10
        ];
    }

    /**
     * @return string
     */
    private function getLabel()
    {
        return $this->uiConfig ? $this->uiConfig->getBackLabel() : __('Back')->render();
    }
}
