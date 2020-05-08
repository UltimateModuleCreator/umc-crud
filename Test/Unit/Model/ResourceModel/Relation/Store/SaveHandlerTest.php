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

use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\EntityManager\EntityMetadataInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\AbstractModel;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Model\ResourceModel\Relation\Store\SaveHandler;
use Umc\Crud\Model\ResourceModel\StoreAwareAbstractModel;

class SaveHandlerTest extends TestCase
{
    /**
     * @var MetadataPool | MockObject
     */
    private $metadataPool;
    /**
     * @var StoreAwareAbstractModel | MockObject
     */
    private $resource;
    /**
     * @var SaveHandler
     */
    private $saveHandler;
    /**
     * @var EntityMetadataInterface | MockObject
     */
    private $metadata;
    /**
     * @var AdapterInterface | MockObject
     */
    private $connection;
    /**
     * @var AbstractModel | MockObject
     */
    private $entity;

    /**
     * setup tests
     */
    protected function setUp()
    {
        $this->metadataPool = $this->createMock(MetadataPool::class);
        $this->resource = $this->createMock(StoreAwareAbstractModel::class);
        $this->metadata = $this->createMock(EntityMetadataInterface::class);
        $this->metadataPool->method('getMetadata')->willReturn($this->metadata);
        $this->connection = $this->createMock(AdapterInterface::class);
        $this->resource->method('getConnection')->willReturn($this->connection);
        $this->entity = $this->createMock(AbstractModel::class);
        $this->saveHandler = new SaveHandler(
            $this->metadataPool,
            $this->resource,
            'entityType',
            'store_table',
            'store_id'
        );
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\Relation\Store\SaveHandler::execute
     * @covers \Umc\Crud\Model\ResourceModel\Relation\Store\SaveHandler::__construct
     */
    public function testExecute()
    {
        $this->metadata->method('getLinkField')->willReturn('entity_id');
        $this->resource->method('lookupStoreIds')->willReturn([1, 2, 3]);
        $this->entity->method('getData')->willReturnMap([
            ['store_id', null, [1, 2, 4, 5]],
            ['entity_id', null, 1]
        ]);
        $this->connection->expects($this->once())->method('delete');
        $this->connection->expects($this->once())->method('insertMultiple');
        $this->saveHandler->execute($this->entity);
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\Relation\Store\SaveHandler::execute
     * @covers \Umc\Crud\Model\ResourceModel\Relation\Store\SaveHandler::__construct
     */
    public function testExecuteNoInsert()
    {
        $this->resource->method('lookupStoreIds')->willReturn([1, 2, 3]);
        $this->entity->method('getData')->willReturnMap([
            ['store_id', null, [1, 2]],
            ['entity_id', null, 1]
        ]);
        $this->connection->expects($this->once())->method('delete');
        $this->connection->expects($this->never())->method('insertMultiple');
        $this->saveHandler->execute($this->entity);
    }
}
