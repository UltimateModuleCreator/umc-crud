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

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Code\Generator\CodeGeneratorInterface;
use Magento\Framework\Code\Generator\DefinedClasses;
use Magento\Framework\Code\Generator\EntityAbstract;
use Magento\Framework\Code\Generator\Io;
use Umc\Crud\Model\ResourceModel\StoreAwareAbstractModel;

class ListRepo extends EntityAbstract
{
    public const ENTITY_TYPE = 'ListRepo';
    /**
     * @var NameMatcher
     */
    private $nameMatcher;

    /**
     * ListRepo constructor.
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
        return [
            'name' => '__construct',
            'parameters' => [
                [
                    'name' => 'searchResultsFactory',
                    'type' => $this->nameMatcher->getSearchResultFactory($this->getSourceClassName()),
                ],
                [
                    'name' => 'collectionFactory',
                    'type' => $this->nameMatcher->getCollectionFactoryClass($this->getSourceClassName()),
                ]
            ],
            'body' => '    $this->searchResultsFactory = $searchResultsFactory; ' . "\n" .
                '$this->collectionFactory = $collectionFactory;',
            'docblock' => [
                'tags' => [
                    [
                        'name' => 'param',
                        'description' => '\\' . $this->nameMatcher->getSearchResultFactory($this->getSourceClassName())
                            . ' $searchResultsFactory',
                    ],
                    [
                        'name' => 'param',
                        'description' => '\\' . $this->nameMatcher->getCollectionFactoryClass($this->getSourceClassName())
                            . ' $collectionFactory',
                    ]
                ]
            ],
        ];
    }

    /**
     * @return string
     */
    protected function _generateCode()
    {
        try {
            $this->_classGenerator->setImplementedInterfaces([
                $this->nameMatcher->getListRepoInterface($this->getSourceClassName())
            ]);
            return parent::_generateCode();
        } catch (\Exception $e) {
            echo $e->getMessage();exit;
        }
    }

    /**
     * @return array
     */
    protected function _getClassMethods()
    {
        $sourceClass = $this->getSourceClassName();
        $getList = [
            'name' => 'getList',
            'parameters' => [
                [
                    'name' => 'searchCriteria',
                    'type' => '\\' . SearchCriteriaInterface::class,
                ],
            ],
            'body' => '    /** @var \\' . $this->nameMatcher->getSearchResultsClass($sourceClass) .
                ' $searchResults */' . "\n" .
                '$searchResults = $this->searchResultsFactory->create();' . "\n" .
                '$searchResults->setSearchCriteria($searchCriteria);' . "\n" .
                '/** @var \\' . $this->nameMatcher->getCollectionClass($sourceClass) . ' $collection */' . "\n" .
                '$collection = $this->collectionFactory->create();' . "\n" .
                'foreach ($searchCriteria->getFilterGroups() as $group) {' . "\n" .
                '    $this->addFilterGroupToCollection($group, $collection);' . "\n" .
                '}' . "\n" .
                '$sortOrders = $searchCriteria->getSortOrders();' . "\n" .
                'if ($sortOrders) { ' . "\n" .
                '    foreach ($searchCriteria->getSortOrders() as $sortOrder) {' . "\n" .
                '        $field = $sortOrder->getField();' . "\n" .
                '        $collection->addOrder(' . "\n" .
                '            $field,' . "\n" .
                '            ($sortOrder->getDirection() == \Magento\Framework\Api\SortOrder::SORT_ASC)' . "\n" .
                '                 ? \Magento\Framework\Api\SortOrder::SORT_ASC' . "\n" .
                '                : \Magento\Framework\Api\SortOrder::SORT_DESC' . "\n" .
                '        );' . "\n" .
                '     }' . "\n" .
                '}' . "\n" .
                '$collection->setCurPage($searchCriteria->getCurrentPage());' . "\n" .
                '$collection->setPageSize($searchCriteria->getPageSize());' . "\n" .
                '$searchResults->setTotalCount($collection->getSize());' . "\n" .
                '$searchResults->setItems($collection->getItems());' . "\n" .
                'return $searchResults;',
            'docblock' => [
                'tags' => [
                    [
                        'name' => 'param',
                        'description' => '\\' . SearchCriteriaInterface::class . ' $searchCriteria'
                    ],
                    [
                        'name' => 'return',
                        'description' => '\\' . $this->nameMatcher->getSearchResultsClass($sourceClass)
                    ],
                    [
                        'name' => 'throws',
                        'description' => '\\' . \Magento\Framework\Exception\LocalizedException::class
                    ]
                ],
            ],
        ];
        return [$this->_getDefaultConstructorDefinition(), $getList, $this->getAddFilterGroupToCollectionConfig()];
    }

    /**
     * @return array
     */
    protected function _getClassProperties()
    {
        $searchResultsFactory = [
            'name' => 'searchResultsFactory',
            'visibility' => 'private',
            'docblock' => [
                'tags' => [
                    [
                        'name' => 'var',
                        'description' => '\\' . $this->nameMatcher->getSearchResultFactory($this->getSourceClassName())
                    ]
                ],
            ],
        ];
        $collectionFactory = [
            'name' => 'collectionFactory',
            'visibility' => 'private',
            'docblock' => [
                'tags' => [
                    [
                        'name' => 'var',
                        'description' => '\\' . $this->nameMatcher->getCollectionFactoryClass(
                            $this->getSourceClassName()
                        )
                    ]
                ],
            ],
        ];

        return [$searchResultsFactory, $collectionFactory];
    }
    //phpcs: enable

    /**
     * @return array
     */
    private function getAddFilterGroupToCollectionConfig()
    {
        $sourceClass = $this->getSourceClassName();
        $body = '    $fields = [];' . "\n";
        $body .= '$conditions = []; ' . "\n";
        $resourceModel = $this->nameMatcher->getResourceClassName($sourceClass);
        $body .= 'foreach ($filterGroup->getFilters() as $filter) {' . "\n";
        if (is_subclass_of($resourceModel, StoreAwareAbstractModel::class, true)) {
            $body .= '    if ($filter->getField() === \'store_id\') {' . "\n";
            $body .= '       $collection->addStoreFilter($filter->getValue(), true);' . "\n";
            $body .= '    } else {' . "\n";
            $body .= $this->getFilterConditions(8);
            $body .= '    }';
        } else {
            $body .= $this->getFilterConditions(4);
        }
        $body .= '}' . "\n";
        $body .= 'if ($fields) {' . "\n";
        $body .= '    $collection->addFieldToFilter($fields, $conditions);' . "\n";
        $body .= '}' . "\n";
        $body .= 'return $this;' . "\n";
        return [
            'name' => 'addFilterGroupToCollection',
            'parameters' => [
                [
                    'name' => 'filterGroup',
                    'type' => '\\' . \Magento\Framework\Api\Search\FilterGroup::class,
                ],
                [
                    'name' => 'collection',
                    'type' => '\\' . $this->nameMatcher->getCollectionClass($sourceClass),
                ],
            ],
            'body' => $body,
            'docblock' => [
                'tags' => [
                    [
                        'name' => 'param',
                        'description' => '\\' . \Magento\Framework\Api\Search\FilterGroup::class . ' $filterGroup'
                    ],
                    [
                        'name' => 'param',
                        'description' => '\\' . $this->nameMatcher->getCollectionClass($sourceClass) . ' $collection'
                    ],
                    [
                        'name' => 'return',
                        'description' => '$this'
                    ],
                ],
            ],
        ];
    }

    /**
     * @param $indent
     * @return string
     */
    private function getFilterConditions($indent)
    {
        $padd = str_repeat(' ', $indent);
        $text = $padd . '$condition = $filter->getConditionType() ? $filter->getConditionType() : \'eq\';' . "\n";
        $text .= $padd . '$fields[] = $filter->getField();' . "\n";
        $text .= $padd . '$conditions[] = [$condition => $filter->getValue()];' . "\n";
        return $text;
    }

}
