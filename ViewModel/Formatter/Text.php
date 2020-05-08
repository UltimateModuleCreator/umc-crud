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

use Magento\Framework\Escaper;

class Text implements FormatterInterface
{
    /**
     * @var Escaper
     */
    private $escaper;

    /**
     * Text constructor.
     * @param Escaper $escaper
     */
    public function __construct(Escaper $escaper)
    {
        $this->escaper = $escaper;
    }

    /**
     * @param $value
     * @param array $arguments
     * @return string
     */
    public function formatHtml($value, $arguments = []): string
    {
        return $this->escaper->escapeHtml($value);
    }
}
