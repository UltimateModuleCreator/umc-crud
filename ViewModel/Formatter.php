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

namespace Umc\Crud\ViewModel;

use Magento\Framework\Escaper;
use Umc\Crud\ViewModel\Formatter\FormatterInterface;

class Formatter implements FormatterInterface
{
    /**
     * @var FormatterInterface[]
     */
    private $formatterMap;
    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * Formatter constructor.
     * @param FormatterInterface[] $formatterMap
     * @param Escaper $escaper
     */
    public function __construct(array $formatterMap, Escaper $escaper)
    {
        foreach ($formatterMap as $formatter) {
            if (!($formatter instanceof FormatterInterface)) {
                throw new \InvalidArgumentException("Formatter must implement " . FormatterInterface::class);
            }
        }
        $this->formatterMap = $formatterMap;
        $this->escaper = $escaper;
    }

    /**
     * @param $value
     * @param array $arguments
     * @return string
     */
    public function formatHtml($value, $arguments = []): string
    {
        $type = $arguments['type'] ?? null;
        return $type === null
            ? $this->escaper->escapeHtml($value)
            : $this->getFormatter($type)->formatHtml($value, $arguments);
    }

    /**
     * @param $type
     * @return FormatterInterface|null
     */
    private function getFormatter($type)
    {
        $formatter = $this->formatterMap[$type] ?? null;
        if ($formatter === null) {
            throw new \InvalidArgumentException("Missing formatter for type {$type}");
        }
        return $formatter;
    }
}
