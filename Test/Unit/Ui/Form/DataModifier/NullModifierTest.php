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

namespace Umc\Crud\Test\Unit\Ui\Form\DataModifier;

use Magento\Framework\Model\AbstractModel;
use PHPUnit\Framework\TestCase;
use Umc\Crud\Ui\Form\DataModifier\NullModifier;

class NullModifierTest extends TestCase
{
    /**
     * @covers \Umc\Crud\Ui\Form\DataModifier\NullModifier::modifyData
     */
    public function testModifyData()
    {
        $model = $this->createMock(AbstractModel::class);
        $data = ['dummy'];
        $this->assertEquals($data, (new NullModifier())->modifyData($model, $data));
    }
}
