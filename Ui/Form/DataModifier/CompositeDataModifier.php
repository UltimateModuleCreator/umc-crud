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

use Magento\Framework\Model\AbstractModel;
use Umc\Crud\Ui\Form\DataModifierInterface;

class CompositeDataModifier implements DataModifierInterface
{
    /**
     * @var DataModifierInterface[]
     */
    private $modifiers;

    /**
     * CompositeDataModifier constructor.
     * @param DataModifierInterface[] $modifiers
     */
    public function __construct(array $modifiers)
    {
        foreach ($modifiers as $modifier) {
            if (!($modifier instanceof DataModifierInterface)) {
                throw new \InvalidArgumentException(
                    "Form data modifier must implemenet " . DataModifierInterface::class
                );
            }
        }
        $this->modifiers = $modifiers;
    }

    /**
     * @param AbstractModel $model
     * @param array $data
     * @return array
     */
    public function modifyData(AbstractModel $model, array $data): array
    {
        foreach ($this->modifiers as $modifier) {
            $data = $modifier->modifyData($model, $data);
        }
        return $data;
    }
}
