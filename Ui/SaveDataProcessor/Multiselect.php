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

namespace Umc\Crud\Ui\SaveDataProcessor;

use Umc\Crud\Ui\SaveDataProcessorInterface;

class Multiselect implements SaveDataProcessorInterface
{
    /**
     * @var array
     */
    private $fields;

    /**
     * Mutiselect constructor.
     * @param array $fields
     */
    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    /**
     * @param array $data
     * @return array
     */
    public function modifyData(array $data): array
    {
        foreach ($this->fields as $field) {
            if (!array_key_exists($field, $data)) {
                continue;
            }
            $value = $data[$field] ?? [];
            if (is_array($value)) {
                $data[$field] = implode(',', $value);
            }
        }
        return $data;
    }
}
