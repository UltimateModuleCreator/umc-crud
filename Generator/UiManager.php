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
use Magento\Framework\Model\AbstractModel;

class UiManager extends EntityAbstract
{
    public const ENTITY_TYPE = 'UiManager';

    /**
     * @var NameMatcher
     */
    private $nameMatcher;

    /**
     * UiManager constructor.
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
    protected function _getDefaultConstructorDefinition()
    {
        $interfaceName = $this->nameMatcher->getRepositoryInterfaceName($this->getSourceClassName());
        return [
            'name' => '__construct',
            'parameters' => [
                [
                    'name' => 'repository',
                    'type' => $interfaceName,
                ],
                [
                    'name' => 'factory',
                    'type' => $this->getSourceClassName() . 'Factory',
                ]
            ],
            'body' => "\t" . '$this->repository = $repository; ' . "\n" . '$this->factory = $factory;',
            'docblock' => [
                'tags' => [
                    [
                        'name' => 'param',
                        'description' => $interfaceName . ' $repository'
                    ],
                    [
                        'name' => 'param',
                        'description' => $this->getSourceClassName() . 'Factory' . ' $factory'
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    protected function _getClassProperties()
    {
        $repository = [
            'name' => 'repository',
            'visibility' => 'private',
            'docblock' => [
                'tags' => [
                    ['name' => 'var',
                        'description' => '\\' . $this->nameMatcher->getInterfaceName($this->getSourceClassName())
                    ]
                ],
            ],
        ];
        $factory = [
            'name' => 'factory',
            'visibility' => 'factory',
            'docblock' => [
                'tags' => [['name' => 'var', 'description' => '\\' . $this->getSourceClassName() . 'Factory']],
            ],
        ];

        return [$repository, $factory];
    }

    /**
     * @return array
     */
    protected function _getClassMethods()
    {
        $construct = $this->_getDefaultConstructorDefinition();

        $create = [
            'name' => 'get',
            'parameters' => [
                [
                    'name' => 'id', 'type' => '?int'
                ]
            ],
            'body' => 'return ($id)' . "\n" .
                '    ? $this->repository->get($id)' . "\n" .
                '    : $this->factory->create();',
            'docblock' => [
                'tags' => [
                    [
                        'name' => 'param',
                        'description' => 'int|null $id'
                    ],
                    [
                        'name' => 'return',
                        'description' => '\\' . \Magento\Framework\Model\AbstractModel::class .
                            ' | ' . $this->getSourceClassNameWithoutNamespace()
                    ],
                    [
                        'name' => 'throws',
                        'description' => '\\' . \Magento\Framework\Exception\NoSuchEntityException::class
                    ]
                ],
            ],
        ];

        $save = [
            'name' => 'save',
            'parameters' => [
                [
                    'name' => 'entity',
                    'type' => AbstractModel::class
                ]
            ],
            'body' => '$this->repository->save($entity);',
            'docblock' => [
                'tags' => [
                    [
                        'name' => 'param',
                        'description' => AbstractModel::class . ' $entity'
                    ],
                    [
                        'name' => 'throws',
                        'description' => '\\' . \Magento\Framework\Exception\LocalizedException::class
                    ]
                ],
            ],
        ];

        $delete = [
            'name' => 'delete',
            'parameters' => [
                [
                    'name' => 'id', 'type' => 'int'
                ]
            ],
            'body' => '$this->repository->deleteById($id);',
            'docblock' => [
                'tags' => [
                    [
                        'name' => 'param',
                        'description' => 'int $id'
                    ],
                    [
                        'name' => 'throws',
                        'description' => '\\' . \Magento\Framework\Exception\NoSuchEntityException::class
                    ],
                    [
                        'name' => 'throws',
                        'description' => '\\' . \Magento\Framework\Exception\CouldNotDeleteException::class
                    ],
                    [
                        'name' => 'throws',
                        'description' => '\\' . \Magento\Framework\Exception\LocalizedException::class
                    ]
                ],
            ],
        ];

        return [$construct, $create, $save, $delete];
    }

    /**
     * @return string
     */
    protected function _generateCode()
    {
        $this->_classGenerator->setImplementedInterfaces([\Umc\Crud\Ui\EntityUiManagerInterface::class]);
        return parent::_generateCode();
    }
    //phpcs:enable
}
