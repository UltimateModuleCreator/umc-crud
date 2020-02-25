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

namespace Umc\Crud\Ui\Form\DataModifier;

use Magento\Framework\Model\AbstractModel;
use Magento\Framework\Serialize\Serializer\Json;
use Umc\Crud\Ui\Form\DataModifierInterface;

class DynamicRows implements DataModifierInterface
{
    /**
     * @var Json
     */
    private $serializer;
    /**
     * @var array
     */
    private $fields;

    /**
     * DynamicRows constructor.
     * @param Json $serializer
     * @param array $fields
     */
    public function __construct(Json $serializer, array $fields)
    {
        $this->serializer = $serializer;
        $this->fields = $fields;
    }

    /**
     * @param AbstractModel $model
     * @param array $data
     * @return array
     */
    public function modifyData(AbstractModel $model, array $data): array
    {
        foreach ($this->fields as $field) {
            if (array_key_exists($field, $data) && !is_array($data[$field])) {
                try {
                    $data[$field] = $this->serializer->unserialize($data[$field]);
                } catch (\Exception $e) {
                    $data[$field] = [];
                }
            }
        }
        return $data;
    }
}
