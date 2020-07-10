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

namespace Umc\Crud\Source\Catalog;

use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Eav\Api\Data\AttributeOptionInterface;
use Magento\Framework\Data\OptionSourceInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class ProductAttributeOptions implements OptionSourceInterface
{
    /**
     * @var ProductAttributeRepositoryInterface
     */
    private $attributeRepository;
    /**
     * @var string
     */
    private $attributeCode;
    /**
     * @var array
     */
    private $options;

    /**
     * ProductAttributeOptions constructor.
     * @param ProductAttributeRepositoryInterface $attributeRepository
     * @param string $attributeCode
     */
    public function __construct(ProductAttributeRepositoryInterface $attributeRepository, string $attributeCode)
    {
        $this->attributeRepository = $attributeRepository;
        $this->attributeCode = $attributeCode;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            try {
                $attribute = $this->attributeRepository->get($this->attributeCode);
                $this->options = array_map(
                    function (AttributeOptionInterface $option) {
                        return [
                            'value' => $option->getValue(),
                            'label' => $option->getLabel()
                        ];
                    },
                    $attribute->getOptions()
                );
            } catch (NoSuchEntityException $e) {
                $this->options = [];
            }
        }
        return $this->options;
    }
}
