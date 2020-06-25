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

namespace Umc\Crud\Test\Unit\Model\ResourceModel;

use Magento\Framework\EntityManager\EntityManager;
use Magento\Framework\EntityManager\EntityMetadataInterface;
use Magento\Framework\EntityManager\MetadataPool;
use Magento\Framework\Model\AbstractModel as FrameworkAbstractModel;
use Magento\Framework\Model\ResourceModel\Db\Context;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Model\ResourceModel\AbstractModel;

class AbstractModelTest extends TestCase
{
    /**
     * @var Context | MockObject
     */
    private $context;
    /**
     * @var EntityManager | MockObject
     */
    private $entityManager;
    /**
     * @var MetadataPool | MockObject
     */
    private $metadataPool;
    /**
     * @var FrameworkAbstractModel | MockObject
     */
    private $object;
    /**
     * @var EntityMetadataInterface | MockObject
     */
    private $metadata;
    /**
     * @var object
     */
    private $abstractModelClass;
    /**
     * @var AbstractModel
     */
    private $abstractModel;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->context = $this->createMock(Context::class);
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->metadataPool = $this->createMock(MetadataPool::class);
        $this->object = $this->createMock(FrameworkAbstractModel::class);
        $this->metadata = $this->createMock(EntityMetadataInterface::class);
        $this->abstractModelClass = new class (
            $this->context,
            $this->entityManager,
            $this->metadataPool,
            'InterfaceName'
        ) extends AbstractModel
        {
            //phpcs:disable PSR2.Methods.MethodDeclaration.Underscore,PSR12.Methods.MethodDeclaration.Underscore
            protected function _construct()
            {
            }
            //phpcs:enable
        };
        $this->abstractModel = new $this->abstractModelClass(
            $this->context,
            $this->entityManager,
            $this->metadataPool,
            'InterfaceName'
        );
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\AbstractModel::save
     * @covers \Umc\Crud\Model\ResourceModel\AbstractModel::__construct
     */
    public function testSave()
    {
        $this->entityManager->expects($this->once())->method('save');
        $this->assertEquals($this->abstractModel, $this->abstractModel->save($this->object));
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\AbstractModel::delete
     * @covers \Umc\Crud\Model\ResourceModel\AbstractModel::__construct
     */
    public function testDelete()
    {
        $this->entityManager->expects($this->once())->method('delete');
        $this->assertEquals($this->abstractModel, $this->abstractModel->delete($this->object));
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\AbstractModel::load
     * @covers \Umc\Crud\Model\ResourceModel\AbstractModel::__construct
     */
    public function testLoad()
    {
        $this->entityManager->expects($this->once())->method('load');
        $this->assertEquals($this->abstractModel, $this->abstractModel->load($this->object, 1));
    }

    /**
     * @covers \Umc\Crud\Model\ResourceModel\AbstractModel::getConnection
     * @covers \Umc\Crud\Model\ResourceModel\AbstractModel::__construct
     */
    public function testGetConnection()
    {
        $this->metadataPool->expects($this->once())->method('getMetadata')->with('InterfaceName')
            ->willReturn($this->metadata);
        $this->metadata->expects($this->once())->method('getEntityConnection')->willReturn('connection');
        $this->assertEquals('connection', $this->abstractModel->getConnection());
    }
}
