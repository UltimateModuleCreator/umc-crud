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

namespace Umc\Crud\Ui\Form\DataModifier;

use Umc\Crud\Model\FileInfo;
use Umc\Crud\Model\Uploader;
use Umc\Crud\Ui\Form\DataModifierInterface;
use Magento\Framework\Model\AbstractModel;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Upload implements DataModifierInterface
{
    /**
     * @var array
     */
    private $fields;
    /**
     * @var Uploader
     */
    private $uploader;
    /**
     * @var LoggerInterface
     */
    private $logger;
    /**
     * @var FileInfo
     */
    private $fileInfo;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * Image constructor.
     * @param array $fields
     * @param Uploader $uploader
     * @param LoggerInterface $logger
     * @param FileInfo $fileInfo
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        array $fields,
        Uploader $uploader,
        LoggerInterface $logger,
        FileInfo $fileInfo,
        StoreManagerInterface $storeManager
    ) {
        $this->fields = $fields;
        $this->uploader = $uploader;
        $this->logger = $logger;
        $this->fileInfo = $fileInfo;
        $this->storeManager = $storeManager;
    }

    /**
     * @param AbstractModel $model
     * @param array $data
     * @return array
     * @throws \Magento\Framework\Exception\FileSystemException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function modifyData(AbstractModel $model, array $data): array
    {
        foreach ($this->fields as $field) {
            $value = $data[$field] ?? '';
            if ($value) {
                if ($this->fileInfo->isExist($value)) {
                    $stat = $this->fileInfo->getStat($value);
                    $mime = $this->fileInfo->getMimeType($value);
                    $beginsWithMediaDirectory = $this->fileInfo->isBeginsWithMediaDirectoryPath($value);
                    $url = ($beginsWithMediaDirectory) ? $value : $this->getUrl($value);
                    $data[$field] = [
                        0 => [
                            'name' => $value,
                            'url' => $url,
                            'size' => isset($stat) ? $stat['size'] : 0,
                            'type' => $mime
                        ]
                    ];
                }
            }
        }
        return $data;
    }

    /**
     * @param $file
     * @return bool|string
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getUrl($file)
    {
        if (is_string($file)) {
            $store = $this->storeManager->getStore();
            $mediaBaseUrl = $store->getBaseUrl(
                \Magento\Framework\UrlInterface::URL_TYPE_MEDIA
            );
            return $mediaBaseUrl . ltrim($this->fileInfo->getBaseFilePath(), '/') . '/' . ltrim($file, '/');
        } else {
            throw new \Magento\Framework\Exception\LocalizedException(
                __('Something went wrong while getting the file url.')
            );
        }
    }
}
