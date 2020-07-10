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

namespace Umc\Crud\ViewModel\Formatter;

use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Escaper;
use Magento\Framework\ObjectManagerInterface;

class Options implements FormatterInterface
{
    /**
     * @var ObjectManagerInterface
     */
    private $objectManager;
    /**
     * @var Escaper
     */
    private $escaper;
    /**
     * @var OptionSourceInterface[]
     */
    private $sources = [];

    /**
     * Options constructor.
     * @param ObjectManagerInterface $objectManager
     * @param Escaper $escaper
     */
    public function __construct(ObjectManagerInterface $objectManager, Escaper $escaper)
    {
        $this->objectManager = $objectManager;
        $this->escaper = $escaper;
    }

    /**
     * @param $value
     * @param array $arguments
     * @return string
     */
    public function formatHtml($value, $arguments = []): string
    {
        $source = $this->getSource($arguments);
        $options = $source ? $source->toOptionArray() : $this->getOptions($arguments);
        $value = is_array($value) ? $value : [$value];
        $texts = array_map(
            function ($item) {
                return $this->escaper->escapeHtml($item['label'] ?? '');
            },
            array_filter(
                $options,
                function ($item) use ($value) {
                    return isset($item['value']) && in_array($item['value'], $value);
                }
            )
        );
        return count($texts) > 0
            ? implode(', ', $texts)
            : (isset($arguments['default']) ? (string)$arguments['default'] : '');
    }

    /**
     * @param array $arguments
     * @return OptionSourceInterface|null
     */
    private function getSource(array $arguments): ?OptionSourceInterface
    {
        $sourceClass = $arguments['source'] ?? null;
        if (!$sourceClass) {
            return null;
        }
        if (!array_key_exists($sourceClass, $this->sources)) {
            $instance = $this->objectManager->get($sourceClass);
            if (!($instance instanceof OptionSourceInterface)) {
                throw new \InvalidArgumentException(
                    "Source model for options formatter should implement " . OptionSourceInterface::class
                );
            }
            $this->sources[$sourceClass] = $instance;
        }
        return $this->sources[$sourceClass];
    }

    /**
     * @param array $arguments
     * @return array|mixed
     */
    private function getOptions(array $arguments): array
    {
        return $arguments['options'] ?? [];
    }
}
