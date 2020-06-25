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

use Magento\Framework\Code\Generator\CodeGeneratorInterface;
use Magento\Framework\Code\Generator\DefinedClasses;
use Magento\Framework\Code\Generator\Io;
use Magento\Framework\DataObject;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use ReflectionParameter;
use Umc\Crud\Generator\ListRepo;
use Umc\Crud\Generator\NameMatcher;
use Umc\Crud\Model\ResourceModel\StoreAwareAbstractModel;

class ListRepoTest extends TestCase
{
    /**
     * @var NameMatcher | MockObject
     */
    private $nameMatcher;
    /**
     * @var Io | MockObject
     */
    private $ioObject;
    /**
     * @var CodeGeneratorInterface | MockObject
     */
    private $classGenerator;
    /**
     * @var DefinedClasses | MockObject
     */
    private $definedClasses;
    /**
     * @var ReflectionParameter | MockObject
     */
    private $parameter;
    /**
     * @var ListRepo
     */
    private $listRepo;

    /**
     * setup tests
     */
    protected function setUp(): void
    {
        $this->nameMatcher = $this->createMock(NameMatcher::class);
        $this->ioObject = $this->createMock(Io::class);
        $this->classGenerator = $this->createMock(CodeGeneratorInterface::class);
        $this->definedClasses = $this->createMock(DefinedClasses::class);
        $this->parameter = $this->createMock(ReflectionParameter::class);
        $this->listRepo = new ListRepo(
            $this->nameMatcher,
            DataObject::class,
            '\Result\Class',
            $this->ioObject,
            $this->classGenerator,
            $this->definedClasses
        );
    }

    /**
     * @covers \Umc\Crud\Generator\ListRepo
     */
    public function testGenerate()
    {
        $this->ioObject->expects($this->once())->method('generateResultFileName')->willReturn('filename.php');
        $this->nameMatcher->expects($this->once())->method('getListRepoInterface');
        $this->nameMatcher->expects($this->any())->method('getSearchResultsClass');
        $this->nameMatcher->expects($this->any())->method('getSearchResultFactory')->willReturn('searchResults');
        $this->nameMatcher->expects($this->any())->method('getCollectionFactoryClass');
        $this->definedClasses->method('isClassLoadable')->willReturn(true);
        $this->ioObject->method('makeResultFileDirectory')->willReturn(true);
        $this->ioObject->method('fileExists')->willReturn(true);
        $this->classGenerator->expects($this->once())->method('setName')->willReturnSelf();
        $this->classGenerator->expects($this->once())->method('addProperties')->willReturnSelf();
        $this->classGenerator->expects($this->once())->method('addMethods')->willReturnSelf();
        $this->classGenerator->expects($this->once())->method('setClassDocBlock')->willReturnSelf();
        $this->classGenerator->expects($this->once())->method('generate')->willReturn('generated code');
        $this->assertEquals('filename.php', $this->listRepo->generate());
    }

    /**
     * @covers \Umc\Crud\Generator\ListRepo
     */
    public function testGenerateStoreAwareModel()
    {
        $this->ioObject->expects($this->once())->method('generateResultFileName')->willReturn('filename.php');
        $this->nameMatcher->expects($this->once())->method('getListRepoInterface');
        $this->nameMatcher->expects($this->any())->method('getSearchResultsClass');
        $this->nameMatcher->expects($this->any())->method('getSearchResultFactory')->willReturn('searchResults');
        $this->nameMatcher->expects($this->any())->method('getCollectionFactoryClass');
        $class = $this->createMock(StoreAwareAbstractModel::class);
        $this->nameMatcher->method('getResourceClassName')->willReturn(get_class($class));
        $this->definedClasses->method('isClassLoadable')->willReturn(true);
        $this->ioObject->method('makeResultFileDirectory')->willReturn(true);
        $this->ioObject->method('fileExists')->willReturn(true);
        $this->classGenerator->expects($this->once())->method('setName')->willReturnSelf();
        $this->classGenerator->expects($this->once())->method('addProperties')->willReturnSelf();
        $this->classGenerator->expects($this->once())->method('addMethods')->willReturnSelf();
        $this->classGenerator->expects($this->once())->method('setClassDocBlock')->willReturnSelf();
        $this->classGenerator->expects($this->once())->method('generate')->willReturn('generated code');
        $this->assertEquals('filename.php', $this->listRepo->generate());
    }
}
