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

namespace Umc\Crud\Test\Unit\Generator;

use PHPUnit\Framework\TestCase;
use Umc\Crud\Generator\NameMatcher;

class NameMatcherTest extends TestCase
{
    /**
     * @var NameMatcher
     */
    private $nameMatcher;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->nameMatcher = new NameMatcher();
    }

    /**
     * @covers \Umc\Crud\Generator\NameMatcher::getInterfaceName
     */
    public function testGetInterfaceName()
    {
        $expected = '\Namespace\Module\Api\Data\EntityInterface';
        $model = '\Namespace\Module\Model\Entity';
        $this->assertEquals($expected, $this->nameMatcher->getInterfaceName($model));
    }

    /**
     * @covers \Umc\Crud\Generator\NameMatcher::getInterfaceFactoryClass
     */
    public function testGetInterfaceFactoryClass()
    {
        $expected = '\Namespace\Module\Api\Data\EntityInterfaceFactory';
        $model = '\Namespace\Module\Model\Entity';
        $this->assertEquals($expected, $this->nameMatcher->getInterfaceFactoryClass($model));
    }

    /**
     * @covers \Umc\Crud\Generator\NameMatcher::getResourceClassName
     */
    public function testGetResourceClassName()
    {
        $expected = 'Namespace\Module\Model\ResourceModel\Entity';
        $model = '\Namespace\Module\Model\Entity';
        $this->assertEquals($expected, $this->nameMatcher->getResourceClassName($model));
    }

    /**
     * @covers \Umc\Crud\Generator\NameMatcher::getRepositoryInterfaceName
     */
    public function testGetRepositoryInterfaceName()
    {
        $expected = '\Namespace\Module\Api\EntityRepositoryInterface';
        $model = '\Namespace\Module\Model\Entity';
        $this->assertEquals($expected, $this->nameMatcher->getRepositoryInterfaceName($model));
    }

    /**
     * @covers \Umc\Crud\Generator\NameMatcher::getCollectionClass
     */
    public function testGetCollectionClass()
    {
        $expected = 'Namespace\Module\Model\ResourceModel\Entity\Collection';
        $model = '\Namespace\Module\Model\Entity';
        $this->assertEquals($expected, $this->nameMatcher->getCollectionClass($model));
    }

    /**
     * @covers \Umc\Crud\Generator\NameMatcher::getCollectionFactoryClass
     */
    public function testGetCollectionFactoryClass()
    {
        $expected = 'Namespace\Module\Model\ResourceModel\Entity\CollectionFactory';
        $model = '\Namespace\Module\Model\Entity';
        $this->assertEquals($expected, $this->nameMatcher->getCollectionFactoryClass($model));
    }

    /**
     * @covers \Umc\Crud\Generator\NameMatcher::getSearchResultsClass
     */
    public function testGetSearchResultsClass()
    {
        $expected = 'Namespace\Module\Api\Data\EntitySearchResultsInterface';
        $model = '\Namespace\Module\Model\Entity';
        $this->assertEquals($expected, $this->nameMatcher->getSearchResultsClass($model));
    }

    /**
     * @covers \Umc\Crud\Generator\NameMatcher::getSearchResultFactory
     */
    public function testGetSearchResultFactory()
    {
        $expected = 'Namespace\Module\Api\Data\EntitySearchResultsInterfaceFactory';
        $model = '\Namespace\Module\Model\Entity';
        $this->assertEquals($expected, $this->nameMatcher->getSearchResultFactory($model));
    }

    /**
     * @covers \Umc\Crud\Generator\NameMatcher::getListRepoInterface
     */
    public function testGetListRepoInterface()
    {
        $expected = '\Namespace\Module\Api\EntityListRepositoryInterface';
        $model = '\Namespace\Module\Model\Entity';
        $this->assertEquals($expected, $this->nameMatcher->getListRepoInterface($model));
    }
}
