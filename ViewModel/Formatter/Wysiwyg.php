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

use Laminas\Filter\FilterInterface;

class Wysiwyg implements FormatterInterface
{
    /**
     * @var \Laminas\Filter\FilterInterface
     */
    private $filter;

    /**
     * Wysiwyg constructor.
     * @param \Laminas\Filter\FilterInterface $filter
     */
    public function __construct(FilterInterface $filter)
    {
        $this->filter = $filter;
    }

    /**
     * @param $value
     * @param array $arguments
     * @return string
     * @throws \Laminas\Filter\Exception\ExceptionInterface
     */
    public function formatHtml($value, $arguments = []): string
    {
        return $this->filter->filter($value);
    }
}
