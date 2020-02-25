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

namespace Umc\Crud\Test\Unit\Model\ResourceModel\Relation\Store;

use Magento\Framework\Model\AbstractModel;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Model\ResourceModel\Relation\Store\ReadHandler;
use Umc\Crud\Model\ResourceModel\StoreAwareAbstractModel;

class ReadHandlerTest extends TestCase
{
    /**
     * @var StoreAwareAbstractModel | MockObject
     */
    private $resource;
    /**
     * @var ReadHandler
     */
    private $readHandler;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->resource = $this->createMock(StoreAwareAbstractModel::class);
        $this->readHandler = new ReadHandler($this->resource);
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\Relation\Store\ReadHandler::execute
     * @covers \Umc\Crud\Model\ResourceModel\Relation\Store\ReadHandler::__construct
     */
    public function testExecute()
    {
        $entity = $this->createMock(AbstractModel::class);
        $entity->method('getId')->willReturn(1);
        $this->resource->expects($this->once())->method('lookupStoreIds')->willReturn([1, 3]);
        $entity->expects($this->once())->method('setData')->with('store_id', [1, 3]);
        $this->assertEquals($entity, $this->readHandler->execute($entity));
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\Relation\Store\ReadHandler::execute
     * @covers \Umc\Crud\Model\ResourceModel\Relation\Store\ReadHandler::__construct
     */
    public function testExecuteNoId()
    {
        $entity = $this->createMock(AbstractModel::class);
        $entity->method('getId')->willReturn(null);
        $this->resource->expects($this->never())->method('lookupStoreIds');
        $entity->expects($this->never())->method('setData');
        $this->assertEquals($entity, $this->readHandler->execute($entity));
    }
}
