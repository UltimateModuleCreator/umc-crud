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

namespace Umc\Crud\Test\Unit\Model\ResourceModel\Collection;

use Magento\Framework\Api\ExtensionAttribute\JoinDataInterface;
use Magento\Framework\Api\ExtensionAttribute\JoinProcessorInterface;
use Magento\Framework\Api\Search\AggregationInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\DataObject;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Umc\Crud\Model\ResourceModel\Collection\AbstractCollection;

class AbstractCollectionTest extends TestCase
{
    /**
     * @var EntityFactoryInterface | MockObject
     */
    private $entityFactory;
    /**
     * @var LoggerInterface | MockObject
     */
    private $logger;
    /**
     * @var FetchStrategyInterface | MockObject
     */
    private $fetchStrategy;
    /**
     * @var ManagerInterface | MockObject
     */
    private $eventManager;
    /**
     * @var AdapterInterface | MockObject
     */
    private $connection;
    /**
     * @var AbstractDb | MockObject
     */
    private $resource;
    /**
     * @var SearchCriteriaInterface | MockObject
     */
    private $searchCriteria;
    /**
     * @var DataObject | MockObject
     */
    private $item;
    /**
     * @var Select | MockObject
     */
    private $select;
    /**
     * @var JoinDataInterface | MockObject
     */
    private $join;
    /**
     * @var JoinProcessorInterface | MockObject
     */
    private $extensionAttributesJoinProcessor;
    /**
     * @var AbstractCollection
     */
    private $abstractCollection;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->entityFactory = $this->createMock(EntityFactoryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->fetchStrategy = $this->createMock(FetchStrategyInterface::class);
        $this->eventManager = $this->createMock(ManagerInterface::class);
        $this->connection = $this->createMock(AdapterInterface::class);
        $this->resource = $this->createMock(AbstractDb::class);
        $this->searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $this->item = $this->createMock(DataObject::class);
        $this->select = $this->createMock(Select::class);
        $this->join = $this->createMock(JoinDataInterface::class);
        $this->extensionAttributesJoinProcessor = $this->createMock(JoinProcessorInterface::class);
        $this->resource->method('getConnection')->willReturn($this->connection);
        $this->connection->method('select')->willReturn($this->select);
        $this->abstractCollection = new AbstractCollection(
            $this->entityFactory,
            $this->logger,
            $this->fetchStrategy,
            $this->eventManager,
            'main_table',
            'event_prefix',
            'object',
            'resourceModel',
            DataObject::class,
            $this->connection,
            $this->resource
        );
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\Collection\AbstractCollection::getSelectCountSql
     * @covers \Umc\Crud\Model\ResourceModel\Collection\AbstractCollection::__construct
     */
    public function testGetSelectCountSql()
    {
        $this->select->expects($this->at(6))->method('reset')->with('group');
        $this->abstractCollection->getSelectCountSql();
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\Collection\AbstractCollection::setItems
     * @covers \Umc\Crud\Model\ResourceModel\Collection\AbstractCollection::__construct
     */
    public function testSetItems()
    {
        $this->assertEquals($this->abstractCollection, $this->abstractCollection->setItems());
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\Collection\AbstractCollection::setAggregations
     * @covers \Umc\Crud\Model\ResourceModel\Collection\AbstractCollection::getAggregations
     * @covers \Umc\Crud\Model\ResourceModel\Collection\AbstractCollection::__construct
     */
    public function testGetAggregations()
    {
        $aggregations = $this->createMock(AggregationInterface::class);
        $this->abstractCollection->setAggregations($aggregations);
        $this->assertEquals($aggregations, $this->abstractCollection->getAggregations());
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\Collection\AbstractCollection::getSearchCriteria
     * @covers \Umc\Crud\Model\ResourceModel\Collection\AbstractCollection::__construct
     */
    public function testGetSearchCriteria()
    {
        $this->assertNull($this->abstractCollection->getSearchCriteria());
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\Collection\AbstractCollection::setSearchCriteria
     * @covers \Umc\Crud\Model\ResourceModel\Collection\AbstractCollection::__construct
     */
    public function testSetSearchCriteria()
    {
        $this->assertEquals(
            $this->abstractCollection,
            $this->abstractCollection->setSearchCriteria($this->searchCriteria)
        );
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\Collection\AbstractCollection::getTotalCount
     * @covers \Umc\Crud\Model\ResourceModel\Collection\AbstractCollection::__construct
     */
    public function testGetTotalCount()
    {
        $this->connection->method('fetchOne')->willReturn(9);
        $this->assertEquals(9, $this->abstractCollection->getTotalCount());
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\Collection\AbstractCollection::setTotalCount
     * @covers \Umc\Crud\Model\ResourceModel\Collection\AbstractCollection::__construct
     */
    public function testSetTotalCount()
    {
        $this->assertEquals($this->abstractCollection, $this->abstractCollection->setTotalCount(9));
    }
}
