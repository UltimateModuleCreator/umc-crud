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

namespace Umc\Crud\Test\Unit\Controller\Adminhtml\Heartbeat;

use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\ResultFactory;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Controller\Adminhtml\NewAction;

class NewActionTest extends TestCase
{
    /**
     * @covers \Umc\Crud\Controller\Adminhtml\NewAction::execute
     */
    public function testExecute()
    {
        $context = $this->createMock(Context::class);
        $resultFactory = $this->createMock(ResultFactory::class);
        $forward = $this->createMock(Forward::class);
        $context->expects($this->once())->method('getResultFactory')->willReturn($resultFactory);
        $resultFactory->expects($this->once())->method('create')->willReturn($forward);
        $newAction = new NewAction($context);
        $forward->expects($this->once())->method('forward')->with('edit');
        $this->assertEquals($forward, $newAction->execute());
    }
}
