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

use Magento\Framework\Serialize\Serializer\Json;
use Umc\Crud\Ui\SaveDataProcessorInterface;

class DynamicRows implements SaveDataProcessorInterface
{
    /**
     * @var Json
     */
    private $serializer;
    /**
     * @var array
     */
    private $fields = [];
    /**
     * @var bool
     */
    private $strict;

    /**
     * DynamicRows constructor.
     * @param Json $serializer
     * @param array $fields
     * @param bool $strict
     */
    public function __construct(Json $serializer, array $fields, bool $strict)
    {
        $this->serializer = $serializer;
        $this->fields = $fields;
        $this->strict = $strict;
    }

    /**
     * @param array $data
     * @return array
     */
    public function modifyData(array $data): array
    {
        foreach ($this->fields as $field) {
            if (!array_key_exists($field, $data) && $this->strict) {
                $data[$field] = [];
            }
            if (array_key_exists($field, $data) && is_array($data[$field])) {
                $data[$field] = $this->serializer->serialize($data[$field]);
            }
        }
        return $data;
    }
}
