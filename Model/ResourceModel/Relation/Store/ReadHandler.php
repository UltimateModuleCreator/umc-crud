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

namespace Umc\Crud\Model\ResourceModel\Relation\Store;

use Magento\Framework\EntityManager\Operation\ExtensionInterface;
use Umc\Crud\Model\ResourceModel\StoreAwareAbstractModel;

class ReadHandler implements ExtensionInterface
{
    /**
     * @var StoreAwareAbstractModel
     */
    private $resource;
    /**
     * @var string
     */
    private $storeIdField;

    /**
     * ReadHandler constructor.
     * @param StoreAwareAbstractModel $resource
     * @param string $storeIdField
     */
    public function __construct(StoreAwareAbstractModel $resource, string $storeIdField = 'store_id')
    {
        $this->resource = $resource;
        $this->storeIdField = $storeIdField;
    }

    /**
     * @param object $entity
     * @param array $arguments
     * @return bool|object
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute($entity, $arguments = [])
    {
        if ($entity->getId()) {
            $stores = $this->resource->lookupStoreIds((int)$entity->getId());
            $entity->setData($this->storeIdField, $stores);
        }
        return $entity;
    }
}
