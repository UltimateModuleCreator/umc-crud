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

namespace Umc\Crud\ViewModel\Formatter;

use Umc\Crud\Model\FileInfo;
use Umc\Crud\Model\FileInfoFactory;
use Magento\Framework\Filesystem;
use Magento\Store\Model\StoreManagerInterface;

class File implements FormatterInterface
{
    /**
     * @var FileInfoFactory
     */
    private $fileInfoFactory;
    /**
     * @var Filesystem
     */
    private $filesystem;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var FileInfo[]
     */
    private $fileInfoCache = [];

    /**
     * Image constructor.
     * @param FileInfoFactory $fileInfoFactory
     * @param Filesystem $filesystem
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        FileInfoFactory $fileInfoFactory,
        Filesystem $filesystem,
        StoreManagerInterface $storeManager
    ) {
        $this->fileInfoFactory = $fileInfoFactory;
        $this->filesystem = $filesystem;
        $this->storeManager = $storeManager;
    }

    /**
     * @param $path
     * @return FileInfo
     */
    private function getFileInfo($path)
    {
        if (!array_key_exists($path, $this->fileInfoCache)) {
            $this->fileInfoCache[$path] = $this->fileInfoFactory->create(['filePath' => $path]);
        }
        return $this->fileInfoCache[$path];
    }

    /**
     * @param $value
     * @param array $arguments
     * @return string
     * @throws \Exception
     */
    public function formatHtml($value, $arguments = []): string
    {
        $path = $arguments['path'] ?? '';
        $fileInfo = $this->getFileInfo($path);
        $filePath = $fileInfo->getFilePath((string)$value);
        if (!$filePath) {
            return '';
        }
        $store = $this->storeManager->getStore();
        $mediaBaseUrl = $store->getBaseUrl(
            \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
        );
        return $mediaBaseUrl . trim($filePath, '/');
    }
}
