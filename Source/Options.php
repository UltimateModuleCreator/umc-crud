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

namespace Umc\Crud\Source;

use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /**
     * @var array
     */
    private $options;
    /**
     * @var array
     */
    private $processed;

    /**
     * Options constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->processed === null) {
            $filteredOptions = array_filter(
                $this->options,
                function ($option) {
                    if (!is_array($option)) {
                        return false;
                    }
                    return array_key_exists('label', $option)
                        && array_key_exists('value', $option)
                        && (!array_key_exists('disabled', $option) || !$option['disabled']);
                }
            );
            $this->processed = array_values(
                array_map(
                    function ($option) {
                        return [
                            'label' => $option['label'],
                            'value' => $option['value']
                        ];
                    },
                    $filteredOptions
                )
            );
        }
        return $this->processed;
    }
}
