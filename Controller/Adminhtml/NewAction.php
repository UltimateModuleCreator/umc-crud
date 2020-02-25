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

namespace Umc\Crud\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\ResultFactory;

class NewAction extends Action
{
    /**
     * @return Forward
     */
    public function execute()
    {
        /** @var Forward $forward */
        $forward = $this->resultFactory->create(ResultFactory::TYPE_FORWARD);
        $forward->forward('edit');
        return $forward;
    }
}
