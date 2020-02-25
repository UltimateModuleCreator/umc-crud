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

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\Option\ArrayInterface;

class ProductAttribute implements ArrayInterface
{
    /**
     * @var ProductAttributeRepositoryInterface
     */
    private $attributeRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var SortOrderBuilder
     */
    private $sortOrderBuilder;
    /**
     * @var array
     */
    private $options;
    /**
     * @var array
     */
    private $filters;

    /**
     * ProductAttribute constructor.
     * @param ProductAttributeRepositoryInterface $attributeRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param array $filters
     */
    public function __construct(
        ProductAttributeRepositoryInterface $attributeRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        array $filters = []
    ) {
        $this->attributeRepository = $attributeRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->filters = $filters;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        if ($this->options === null) {
            $this->sortOrderBuilder->setAscendingDirection();
            $this->sortOrderBuilder->setField(ProductAttributeInterface::FRONTEND_LABEL);
            $sortOrder = $this->sortOrderBuilder->create();
            $this->searchCriteriaBuilder->addSortOrder($sortOrder);
            $this->searchCriteriaBuilder->addFilter(ProductAttributeInterface::FRONTEND_LABEL, '', 'neq');
            foreach ($this->getValidFilters() as $filter) {
                $this->searchCriteriaBuilder->addFilter(
                    $filter['key'],
                    $filter['value'],
                    $filter['condition'] ?? 'eq'
                );
            }
            $this->options = array_map(
                function (ProductAttributeInterface $attribute) {
                    return [
                        'label' => $attribute->getDefaultFrontendLabel(),
                        'value' => $attribute->getAttributeCode()
                    ];
                },
                $this->attributeRepository->getList($this->searchCriteriaBuilder->create())->getItems()
            );
        }
        return $this->options;
    }

    /**
     * @return array
     */
    private function getValidFilters()
    {
        return array_filter(
            $this->filters,
            function ($filter) {
                return array_key_exists('key', $filter) && array_key_exists('value', $filter);
            }
        );
    }
}
