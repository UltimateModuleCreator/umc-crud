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

class NameMatcher
{
    /**
     * @var string[][]
     */
    private $cache = [];

    /**
     * @param $class
     * @return string
     */
    public function getInterfaceName($class)
    {
        $class = trim($class, '\\');
        if (!isset($this->cache['interface'][$class])) {
            $parts = explode('\\', $class);
            $parts[2] = 'Api\Data'; //replace 'Model' with 'Api\Data';
            $this->cache['interface'][$class] = '\\' . implode('\\', $parts) . 'Interface';
        }
        return $this->cache['interface'][$class];
    }

    /**
     * @param $class
     * @return string
     */
    public function getInterfaceFactoryClass($class)
    {
        return $this->getInterfaceName($class) . 'Factory';
    }

    /**
     * @param $class
     * @return string
     */
    public function getResourceClassName($class)
    {
        $class = trim($class, '\\');
        if (!isset($this->cache['resource'][$class])) {
            $parts = explode('\\', $class);
            $parts[2] = 'Model\ResourceModel'; //replace 'Model' with 'Model\ResourceModel';
            $this->cache['resource'][$class] = '\\' . implode('\\', $parts);
        }
        return $this->cache['resource'][$class];
    }

    /**
     * @param $class
     * @return string
     */
    public function getRepositoryInterfaceName($class)
    {
        $class = trim($class, '\\');
        if (!isset($this->cache['repository_interface'][$class])) {
            $parts = explode('\\', $class);
            $parts[2] = 'Api'; //replace 'Model' with 'Api';
            $this->cache['repository_interface'][$class] = '\\' . implode('\\', $parts) . 'RepositoryInterface';
        }
        return $this->cache['repository_interface'][$class];
    }

    /**
     * @param $class
     * @return string
     */
    public function getCollectionClass($class)
    {
        return $this->getResourceClassName($class) . '\Collection';
    }

    /**
     * @param $class
     * @return string
     */
    public function getCollectionFactoryClass($class)
    {
        return $this->getCollectionClass($class) . 'Factory';
    }

    /**
     * @param $class
     * @return string
     */
    public function getSearchResultsClass($class)
    {
        $class = trim($class, '\\');
        if (!isset($this->cache['search_results'][$class])) {
            $parts = explode('\\', $class);
            $parts[2] = 'Api\Data'; //replace 'Model' with 'Api\Data';
            $this->cache['search_results'][$class] = '\\' . implode('\\', $parts) . 'SearchResultsInterface';
        }
        return $this->cache['search_results'][$class];
    }

    /**
     * @param $class
     * @return string
     */
    public function getSearchResultFactory($class)
    {
        return $this->getSearchResultsClass($class) . 'Factory';
    }

    /**
     * @param $class
     * @return string
     */
    public function getListRepoInterface($class)
    {
        $class = trim($class, '\\');
        if (!isset($this->cache['list_repository_interface'][$class])) {
            $parts = explode('\\', $class);
            $parts[2] = 'Api'; //replace 'Model' with 'Api';
            $this->cache['list_repository_interface'][$class] = '\\' . implode('\\', $parts)
                . 'ListRepositoryInterface';
        }
        return $this->cache['list_repository_interface'][$class];
    }
}
