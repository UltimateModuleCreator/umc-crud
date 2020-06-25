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
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Magento\Framework\DB\Select;
use Magento\Framework\DataObject;
use Magento\Framework\Data\Collection\Db\FetchStrategyInterface;
use Magento\Framework\Data\Collection\EntityFactoryInterface;
use Magento\Framework\EntityManager\EntityMetadataInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Umc\Crud\Model\ResourceModel\Collection\StoreAwareAbstractCollection;
use Umc\Crud\Model\ResourceModel\Store;

class StoreAwareAbstractCollectionTest extends TestCase
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
     * @var Store | MockObject
     */
    private $storeResource;
    /**
     * @var MetadataPool | MockObject
     */
    private $metadataPool;
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
     * @var AdapterInterface | MockObject
     */
    private $conn;
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
     * @var StoreAwareAbstractCollection
     */
    private $storeAwareAbstractCollection;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->entityFactory = $this->createMock(EntityFactoryInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->fetchStrategy = $this->createMock(FetchStrategyInterface::class);
        $this->eventManager = $this->createMock(ManagerInterface::class);
        $this->storeResource = $this->createMock(Store::class);
        $this->metadataPool = $this->createMock(MetadataPool::class);
        $this->connection = $this->createMock(AdapterInterface::class);
        $this->resource = $this->createMock(AbstractDb::class);
        $this->searchCriteria = $this->createMock(SearchCriteriaInterface::class);
        $this->item = $this->createMock(DataObject::class);
        $this->conn = $this->createMock(AdapterInterface::class);
        $this->select = $this->createMock(Select::class);
        $this->join = $this->createMock(JoinDataInterface::class);
        $this->extensionAttributesJoinProcessor = $this->createMock(JoinProcessorInterface::class);
        $this->resource->method('getConnection')->willReturn($this->connection);
        $this->connection->method('select')->willReturn($this->select);
        $this->storeAwareAbstractCollection = new StoreAwareAbstractCollection(
            $this->entityFactory,
            $this->logger,
            $this->fetchStrategy,
            $this->eventManager,
            'main_table',
            'event_prefix',
            'object',
            'resourceModel',
            DataObject::class,
            $this->storeResource,
            $this->metadataPool,
            'InterfaceName',
            'store_table',
            $this->connection,
            $this->resource
        );
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\Collection\StoreAwareAbstractCollection::addStoreFilter
     * @covers \Umc\Crud\Model\ResourceModel\Collection\StoreAwareAbstractCollection::__construct
     */
    public function testAddStoreFilter()
    {
        $this->storeResource->expects($this->once())->method('addStoreFilter');
        $this->assertEquals(
            $this->storeAwareAbstractCollection,
            $this->storeAwareAbstractCollection->addStoreFilter(3)
        );
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\Collection\StoreAwareAbstractCollection::addFieldToFilter
     * @covers \Umc\Crud\Model\ResourceModel\Collection\StoreAwareAbstractCollection::addStoreFilter
     * @covers \Umc\Crud\Model\ResourceModel\Collection\StoreAwareAbstractCollection::__construct
     */
    public function testAddFieldToFilter()
    {
        $this->storeResource->expects($this->once())->method('addStoreFilter');
        $this->storeAwareAbstractCollection->addFieldToFilter('store_id', 'condition');
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\Collection\StoreAwareAbstractCollection::addFieldToFilter
     * @covers \Umc\Crud\Model\ResourceModel\Collection\StoreAwareAbstractCollection::__construct
     */
    public function testAddFieldToFilterNotStore()
    {
        $this->storeResource->expects($this->never())->method('addStoreFilter');
        $this->storeAwareAbstractCollection->addFieldToFilter('dummy', 'condition');
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\Collection\StoreAwareAbstractCollection::_renderFiltersBefore
     * @covers \Umc\Crud\Model\ResourceModel\Collection\StoreAwareAbstractCollection::_afterLoad
     * @covers \Umc\Crud\Model\ResourceModel\Collection\StoreAwareAbstractCollection::__construct
     */
    public function testLoad()
    {
        $metadata = $this->createMock(EntityMetadataInterface::class);
        $metadata->method('getLinkField')->willReturn('field');
        $this->metadataPool->method('getMetadata')->with('InterfaceName')->willReturn($metadata);
        $this->storeResource->expects($this->once())->method('addStoresToCollection');
        $this->storeResource->expects($this->once())->method('joinStoreRelationTable');
        $this->storeAwareAbstractCollection->load();
    }
}
