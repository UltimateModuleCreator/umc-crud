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

namespace Umc\Crud\Model;

use Magento\Framework\Filesystem\Io\File;

class FileChecker
{
    /**
     * @var File
     */
    private $file;

    /**
     * FileChecker constructor.
     * @param File $file
     */
    public function __construct(File $file)
    {
        $this->file = $file;
    }

    /**
     * @param $destinationFile
     * @param int $sparseLevel
     * @return string
     */
    public function getNewFileName($destinationFile, $sparseLevel = 2)
    {
        $fileInfo = $this->file->getPathInfo($destinationFile);
        if ($this->file->fileExists($destinationFile)) {
            $index = 1;
            $baseName = $fileInfo['filename'] . '.' . $fileInfo['extension'];
            while ($this->file->fileExists($fileInfo['dirname'] . '/' . $baseName)) {
                $baseName = $fileInfo['filename'] . '_' . $index . '.' . $fileInfo['extension'];
                $index++;
            }
            return $baseName;
        } else {
            $prefix = $sparseLevel > 0 ? '/' : '';
            $fileName = $fileInfo['filename'];
            for ($i = 0; $i < $sparseLevel; $i++) {
                $prefix .= ($fileName[$i] ?? '_') . '/';
            }
            return $prefix . $fileInfo['basename'];
        }
    }
}
