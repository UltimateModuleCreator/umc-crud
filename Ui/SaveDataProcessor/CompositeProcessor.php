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

use Umc\Crud\Ui\SaveDataProcessorInterface;

class CompositeProcessor implements SaveDataProcessorInterface
{
    /**
     * @var SaveDataProcessorInterface[]
     */
    private $modifiers;

    /**
     * CompositeModifier constructor.
     * @param SaveDataProcessorInterface[] $modifiers
     */
    public function __construct(array $modifiers)
    {
        foreach ($modifiers as $modifier) {
            if (!($modifier instanceof SaveDataProcessorInterface)) {
                throw new \InvalidArgumentException(
                    "Data modifier must be instance of " . SaveDataProcessorInterface::class
                );
            }
        }
        $this->modifiers = $modifiers;
    }

    public function modifyData(array $data): array
    {
        foreach ($this->modifiers as $modifier) {
            $data = $modifier->modifyData($data);
        }
        return $data;
    }
}
