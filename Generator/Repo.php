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

class Repo extends EntityAbstract
{
    public const ENTITY_TYPE = 'Repo';
    /**
     * @var NameMatcher
     */
    private $nameMatcher;

    /**
     * Repo constructor.
     * @param NameMatcher $nameMatcher
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
        return [
            'name' => '__construct',
            'parameters' => [
                [
                    'name' => 'factory',
                    'type' => $this->nameMatcher->getInterfaceFactoryClass($this->getSourceClassName()),
                ],
                [
                    'name' => 'resource',
                    'type' => $this->nameMatcher->getResourceClassName($this->getSourceClassName()),
                ]
            ],
            'body' => "\t" . '$this->factory = $factory; ' . "\n" . '$this->resource = $resource;',
            'docblock' => [],
        ];
    }

    /**
     * @return string
     */
    protected function _generateCode()
    {
        $this->_classGenerator->setImplementedInterfaces([
            $this->nameMatcher->getRepositoryInterfaceName($this->getSourceClassName())
        ]);
        return parent::_generateCode();
    }

    /**
     * @return array
     */
    protected function _getClassMethods()
    {
        return [
            $this->_getDefaultConstructorDefinition(),
            $this->getSaveMethodConfig(),
            $this->getGetMethodConfig(),
            $this->getDeleteMethodConfig(),
            $this->getDeleteByIdMethodConfig()
        ];
    }

    /**
     * @return array
     */
    protected function _getClassProperties()
    {
        $factory = [
            'name' => 'factory',
            'visibility' => 'private',
            'docblock' => [
                'tags' => [
                    [
                        'name' => 'var',
                        'description' => $this->nameMatcher->getInterfaceFactoryClass($this->getSourceClassName())
                    ]
                ],
            ],
        ];
        $resource = [
            'name' => 'resource',
            'visibility' => 'private',
            'docblock' => [
                'tags' => [
                    [
                        'name' => 'var',
                        'description' => $this->nameMatcher->getResourceClassName($this->getSourceClassName())
                    ]
                ],
            ],
        ];
        $cache = [
            'name' => 'cache',
            'default' => '[]',
            'visibility' => 'private',
            'docblock' => [
                'tags' => [
                    [
                        'name' => 'var',
                        'description' => $this->nameMatcher->getInterfaceName($this->getSourceClassName()) . '[]'
                    ]
                ],
            ],
        ];
        return [$factory, $resource, $cache];
    }
    //phpcs:enable

    /**
     * @return array
     */
    private function getGetMethodConfig()
    {
        return [
            'name' => 'get',
            'parameters' => [
                [
                    'name' => 'id', 'type' => 'int'
                ]
            ],
            'docblock' => [
                'tags' => [
                    [
                        'name' => 'param',
                        'description' => 'int|null $id'
                    ],
                    [
                        'name' => 'return',
                        'description' => $this->nameMatcher->getInterfaceName($this->getSourceClassName())
                    ],
                    [
                        'name' => 'throws',
                        'description' => '\\' . \Magento\Framework\Exception\LocalizedException::class
                    ],
                    [
                        'name' => 'throws',
                        'description' => '\\' . \Magento\Framework\Exception\NoSuchEntityException::class
                    ]
                ],
            ],
            'body' => '    if (!isset($this->cache[$id])) {' . "\n" .
                '    $entity = $this->factory->create();' . "\n" .
                '    $this->resource->load($entity, $id);' . "\n" .
                '    if (!$entity->getId()) {' . "\n" .
                '        throw new \Magento\Framework\Exception\NoSuchEntityException(' . "\n" .
                '            __(\'The Entity with the "%1" ID does not exist . \', $id)' . "\n" .
                '        );' . "\n" .
                '    }' . "\n" .
                '    $this->cache[$id] = $entity;' . "\n" .
                '}' . "\n" .
                'return $this->cache[$id];'
        ];
    }

    /**
     * @return array
     */
    private function getDeleteMethodConfig()
    {
        $interface = $this->nameMatcher->getInterfaceName($this->getSourceClassName());
        return [
            'name' => 'delete',
            'parameters' => [
                [
                    'name' => 'entity', 'type' => $interface
                ]
            ],
            'docblock' => [
                'tags' => [
                    [
                        'name' => 'param',
                        'description' =>  $interface . ' $entity'
                    ],
                    [
                        'name' => 'return',
                        'description' => 'bool'
                    ],
                    [
                        'name' => 'throws',
                        'description' => '\\' . \Magento\Framework\Exception\CouldNotDeleteException::class
                    ]
                ],
            ],
            'body' => '    try {' . "\n" .
                '    $this->resource->delete($entity);' . "\n" .
                '    unset($this->cache[$id]);' . "\n" .
                '} catch (\Exception $exception) {' . "\n" .
                '    throw new \Magento\Framework\Exception\CouldNotDeleteException(' . "\n" .
                '       __($exception->getMessage())' . "\n" .
                '    ); ' . "\n" .
                '}' . "\n" .
                'return true;'
        ];
    }

    private function getDeleteByIdMethodConfig()
    {
        return [
            'name' => 'deleteById',
            'parameters' => [
                [
                    'name' => 'id',
                    'type' => 'int'
                ]
            ],
            'docblock' => [
                'tags' => [
                    [
                        'name' => 'param',
                        'description' =>  ' int $id'
                    ],
                    [
                        'name' => 'return',
                        'description' => 'bool'
                    ],
                    [
                        'name' => 'throws',
                        'description' => '\\' . \Magento\Framework\Exception\CouldNotDeleteException::class
                    ],
                    [
                        'name' => 'throws',
                        'description' => '\\' . \Magento\Framework\Exception\LocalizedException::class
                    ],
                    [
                        'name' => 'throws',
                        'description' => '\\' . \Magento\Framework\Exception\NoSuchEntityException::class
                    ]

                ],
            ],
            'body' => '    return $this->delete($this->get($id));'
        ];
    }

    private function getSaveMethodConfig()
    {
        $interface = $this->nameMatcher->getInterfaceName($this->getSourceClassName());
        return [
            'name' => 'save',
            'parameters' => [
                [
                    'name' => 'entity',
                    'type' => $interface
                ]
            ],
            'docblock' => [
                'tags' => [
                    [
                        'name' => 'param',
                        'description' =>  $interface . ' $entity'
                    ],
                    [
                        'name' => 'return',
                        'description' => $interface
                    ],
                    [
                        'name' => 'throws',
                        'description' => '\\' . \Magento\Framework\Exception\CouldNotSaveException::class
                    ]
                ],
            ],
            'body' => '    try {' . "\n" .
                '    $this->resource->save($entity);' . "\n" .
                '} catch (\Exception $exception) {' . "\n" .
                '    throw new \Magento\Framework\Exception\CouldNotSaveException(' . "\n" .
                '        __($exception->getMessage())' . "\n" .
                '    );' . "\n" .
                '}' . "\n" .
                'return $entity;'
        ];
    }
}
