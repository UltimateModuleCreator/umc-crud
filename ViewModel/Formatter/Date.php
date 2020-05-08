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

use Magento\Framework\Stdlib\DateTime\TimezoneInterface;

class Date implements FormatterInterface
{
    /**
     * formatting arguments
     */
    public const FORMAT = 'format';
    public const SHOW_TIME = 'show_time';
    public const TIMEZONE = 'timezone';

    /**
     * default date format
     * @var string
     */
    public const DEFAULT_FORMAT = \IntlDateFormatter::LONG;
    /**
     * @var TimezoneInterface
     */
    private $localeDate;

    /**
     * Date constructor.
     * @param TimezoneInterface $localeDate
     */
    public function __construct(TimezoneInterface $localeDate)
    {
        $this->localeDate = $localeDate;
    }

    /**
     * @param $value
     * @param array $arguments
     * @return string
     */
    public function formatHtml($value, $arguments = []): string
    {
        $format = $arguments[self::FORMAT] ?? self::DEFAULT_FORMAT;
        $showTime = $arguments[self::SHOW_TIME] ?? false;
        $timezone = $arguments[self::TIMEZONE] ?? null;
        $value = $value instanceof \DateTimeInterface ? $value : new \DateTime($value);
        return $this->localeDate->formatDateTime(
            $value,
            $format,
            $showTime ? $format : \IntlDateFormatter::NONE,
            null,
            $timezone
        );
    }
}
