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

namespace Umc\Crud\Ui;

use Magento\Framework\Model\AbstractModel;

interface EntityUiManagerInterface
{
    /**
     * @param int|null $id
     * @return AbstractModel
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get(?int $id);

    /**
     * @param AbstractModel $entity
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(AbstractModel $entity);

    /**
     * @param int $id
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\CouldNotDeleteException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(int $id);
}
