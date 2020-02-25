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

namespace Umc\Crud\Ui\SaveDataProcessor;

use Umc\Crud\Model\FileInfo;
use Umc\Crud\Model\Uploader;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Filesystem;
use Psr\Log\LoggerInterface;
use Umc\Crud\Ui\SaveDataProcessorInterface;

class Upload implements SaveDataProcessorInterface
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
     * @var Filesystem
     */
    private $filesystem;

    /**
     * Image constructor.
     * @param array $fields
     * @param Uploader $uploader
     * @param FileInfo $fileInfo
     * @param Filesystem $filesystem
     * @param LoggerInterface $logger
     */
    public function __construct(
        array $fields,
        Uploader $uploader,
        FileInfo $fileInfo,
        Filesystem $filesystem,
        LoggerInterface $logger
    ) {
        $this->fields = $fields;
        $this->uploader = $uploader;
        $this->fileInfo = $fileInfo;
        $this->filesystem = $filesystem;
        $this->logger = $logger;
    }

    /**
     * @param $value
     * @return bool
     */
    private function isTmpFileAvailable($value)
    {
        return is_array($value) && isset($value[0]['tmp_name']);
    }

    /**
     * @param $value
     * @return string
     */
    private function getUploadedImageName($value)
    {
        return (is_array($value) && isset($value[0]['file'])) ? $value[0]['file'] : '';
    }

    /**
     * @param array $data
     * @return array
     */
    public function modifyData(array $data): array
    {
        foreach ($this->fields as $field) {
            if (!array_key_exists($field, $data)) {
                continue;
            }
            $value = $data[$field] ?? '';
            if ($this->isTmpFileAvailable($value) && $imageName = $this->getUploadedImageName($value)) {
                try {
                    $data[$field] = $this->uploader->moveFileFromTmp($imageName);
                } catch (\Exception $e) {
                    $this->logger->critical($e);
                }
            } else {
                if ($this->fileResidesOutsideUploadDir($value)) {
                    // phpcs:ignore Magento2.Functions.DiscouragedFunction
                    $value[0]['url'] = parse_url($value[0]['url'], PHP_URL_PATH);
                    $value[0]['name'] = $value[0]['url'];
                }
                $data[$field] = $value[0]['url'] ?? '';
            }
        }
        return $data;
    }

    /**
     * @param $value
     * @return bool
     */
    private function fileResidesOutsideUploadDir($value)
    {
        if (!is_array($value) || !isset($value[0]['url'])) {
            return false;
        }

        $fileUrl = ltrim($value[0]['url'], '/');
        $baseMediaDir = $this->filesystem->getUri(DirectoryList::MEDIA);

        return $baseMediaDir && strpos($fileUrl, $baseMediaDir) !== false;
    }
}
