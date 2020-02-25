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

namespace Umc\Crud\Generator;

use Magento\Framework\Code\Generator\CodeGeneratorInterface;
use Magento\Framework\Code\Generator\DefinedClasses;
use Magento\Framework\Code\Generator\EntityAbstract;
use Magento\Framework\Code\Generator\Io;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class UiCollectionProvider extends EntityAbstract
{
    /**
     * @var NameMatcher
     */
    private $nameMatcher;

    /**
     * UiCollectionProvider constructor.
     * @param null|NameMatcher $nameMatcher
     * @param null|string $sourceClassName
     * @param null|string $resultClassName
     * @param Io|null $ioObject
     * @param CodeGeneratorInterface|null $classGenerator
     * @param DefinedClasses|null $definedClasses
     */
    public function __construct(
        ?NameMatcher $nameMatcher = null,
        ?string $sourceClassName = null,
        ?string $resultClassName = null,
        Io $ioObject = null,
        CodeGeneratorInterface $classGenerator = null,
        DefinedClasses $definedClasses = null
    ) {
        $this->nameMatcher = $nameMatcher ?? new NameMatcher();
        parent::__construct($sourceClassName, $resultClassName, $ioObject, $classGenerator, $definedClasses);
    }

    /**
     * @return array
     * //phpcs:disable PSR2.Methods.MethodDeclaration.Underscore,PSR12.Methods.MethodDeclaration.Underscore
     */
    protected function _getClassProperties()
    {
        return [
            [
                'name' => 'factory',
                'visibility' => 'private',
                'docblock' => [
                    'tags' => [
                        [
                            'name' => 'var',
                            'description' => $this->nameMatcher->getCollectionFactoryClass($this->getSourceClassName())
                        ]
                    ],
                ],
            ]
        ];
    }

    /**
     * @return array
     */
    protected function _getDefaultConstructorDefinition()
    {
        return [
            'name' => '__construct',
            'parameters' => [
                [
                    'name' => 'factory',
                    'type' => $this->nameMatcher->getCollectionFactoryClass($this->getSourceClassName()),
                ]
            ],
            'body' => "\t" . '$this->factory = $factory;' . "\n",
            'docblock' => [],
        ];
    }

    /**
     * @return string
     */
    protected function _generateCode()
    {
        $this->_classGenerator->setImplementedInterfaces([
            \Umc\Crud\Ui\CollectionProviderInterface::class
        ]);
        return parent::_generateCode();
    }

    /**
     * @return array
     */
    protected function _getClassMethods()
    {
        $getCollection = [
            'name' => 'getCollection',
            'parameters' => [],
            'body' => "\t" . 'return $this->factory->create();' . "\n",
            'docblock' => [
                'tags' => [
                    ['name' => 'return', 'description' => '\\' . AbstractCollection::class]
                ],
            ],
        ];
        return [$this->_getDefaultConstructorDefinition(), $getCollection];
    }
    //phpcs:enable
}
