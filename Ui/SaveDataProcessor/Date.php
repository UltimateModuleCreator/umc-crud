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

class Date implements SaveDataProcessorInterface
{
    /**
     * @var array
     */
    private $fields;
    /**
     * @var \Zend_Filter_InputFactory
     */
    private $filterFactory;
    /**
     * @var \Magento\Framework\Stdlib\DateTime\Filter\Date
     */
    private $dateFilter;

    /**
     * Date constructor.
     * @param array $fields
     * @param \Zend_Filter_InputFactory $filterFactory
     * @param \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter
     */
    public function __construct(
        array $fields,
        \Zend_Filter_InputFactory $filterFactory,
        \Magento\Framework\Stdlib\DateTime\Filter\Date $dateFilter
    ) {
        $this->fields = $fields;
        $this->filterFactory = $filterFactory;
        $this->dateFilter = $dateFilter;
    }

    /**
     * @param array $data
     * @return array
     */
    public function modifyData(array $data): array
    {
        $filterRules = [];
        foreach ($this->fields as $dateField) {
            if (!array_key_exists($dateField, $data)) {
                continue;
            }
            if (!empty($data[$dateField])) {
                $filterRules[$dateField] = $this->dateFilter;
            }
        }
        /** @var \Zend_Filter_Input $filter */
        $filter = $this->filterFactory->create([
            'filterRules' => $filterRules,
            'validatorRules' => [],
            'data' => $data
        ]);
        return $filter->getUnescaped();
    }
}
