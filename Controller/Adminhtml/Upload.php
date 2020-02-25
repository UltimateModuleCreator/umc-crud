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

use Umc\Crud\Model\Uploader;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Upload extends Action
{
    /**
     * Uploader model
     * @var Uploader
     */
    private $uploader;

    /**
     * constructor
     * @param Context $context
     * @param Uploader $uploader
     */
    public function __construct(
        Context $context,
        Uploader $uploader
    ) {
        $this->uploader = $uploader;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        try {
            $result = $this->uploader->saveFileToTmpDir($this->getFieldName());
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        $response = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $response->setData($result);
        return $response;
    }

    /**
     * @return string
     */
    protected function getFieldName()
    {
        return $this->getRequest()->getParam('field');
    }
}
