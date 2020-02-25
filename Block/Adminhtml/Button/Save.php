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

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;
use Magento\Ui\Component\Control\Container;
use Umc\Crud\Ui\EntityUiConfig;

class Save implements ButtonProviderInterface
{
    /**
     * @var EntityUiConfig
     */
    private $uiConfig;

    /**
     * Save constructor.
     * @param EntityUiConfig $uiConfig
     */
    public function __construct(EntityUiConfig $uiConfig)
    {
        $this->uiConfig = $uiConfig;
    }

    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => $this->uiConfig->getSaveLabel(),
            'class' => 'save primary',
            'data_attribute' => [
                'mage-init' => [
                    'buttonAdapter' => [
                        'actions' => [
                            [
                                'targetName' => $this->uiConfig->getSaveFormTarget(),
                                'actionName' => 'save',
                                'params' => [
                                    true,
                                    [
                                        'back' => 'continue'
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ],
            'class_name' => ($this->hasOptions())
                ? Container::SPLIT_BUTTON
                : Container::DEFAULT_CONTROL,
            'options' => $this->getOptions(),
        ];
    }

    /**
     * @return bool
     */
    private function hasOptions(): bool
    {
        return $this->uiConfig->getAllowSaveAndClose() || $this->uiConfig->getAllowSaveAndDuplicate();
    }

    /**
     * @return array
     */
    private function getOptions(): array
    {
        $options = [];
        if ($this->uiConfig->getAllowSaveAndDuplicate()) {
            $options[] = [
                'label' => $this->uiConfig->getSaveAndDuplicateLabel(),
                'id_hard' => 'save_and_duplicate',
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => $this->uiConfig->getSaveFormTarget(),
                                    'actionName' => 'save',
                                    'params' => [
                                        true,
                                        [
                                            'back' => 'duplicate'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }
        if ($this->uiConfig->getAllowSaveAndClose()) {
            $options[] = [
                'id_hard' => 'save_and_close',
                'label' => $this->uiConfig->getSaveAndCloseLabel(),
                'data_attribute' => [
                    'mage-init' => [
                        'buttonAdapter' => [
                            'actions' => [
                                [
                                    'targetName' => $this->uiConfig->getSaveFormTarget(),
                                    'actionName' => 'save',
                                    'params' => [
                                        true,
                                        [
                                            'back' => 'close'
                                        ]
                                    ]
                                ]
                            ]
                        ]
                    ]
                ]
            ];
        }
        return $options;
    }
}
