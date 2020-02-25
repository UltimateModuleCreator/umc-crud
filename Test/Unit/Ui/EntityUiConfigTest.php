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

namespace Umc\Crud\Test\Unit\Ui;

use PHPUnit\Framework\TestCase;
use Umc\Crud\Ui\EntityUiConfig;

class EntityUiConfigTest extends TestCase
{

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testConstruct()
    {
        $this->expectException(\InvalidArgumentException::class);
        new EntityUiConfig('Name\Too\Short');
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testConstructWrongName()
    {
        $this->expectException(\InvalidArgumentException::class);
        new EntityUiConfig('Name\Does\Not\End\With\Proper\Termination');
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getInterface
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetInterface()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface', $uiConfig->getInterface());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getBackLabel
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetBackLabel()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('Back', $uiConfig->getBackLabel());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['labels' => ['back' => 'Back to list']]
        );
        $this->assertEquals('Back to list', $uiConfig->getBackLabel());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getSaveLabel
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetSaveLabel()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('Save', $uiConfig->getSaveLabel());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['labels' => ['save' => 'Save Entity']]
        );
        $this->assertEquals('Save Entity', $uiConfig->getSaveLabel());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getSaveAndDuplicateLabel
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetSaveAndDuplicateLabel()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('Save & Duplicate', $uiConfig->getSaveAndDuplicateLabel());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['labels' => ['save_and_duplicate' => 'Save And clone it']]
        );
        $this->assertEquals('Save And clone it', $uiConfig->getSaveAndDuplicateLabel());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getSaveAndCloseLabel
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetSaveAndCloseLabel()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('Save & close', $uiConfig->getSaveAndCloseLabel());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['labels' => ['save_and_close' => 'Save And go to list']]
        );
        $this->assertEquals('Save And go to list', $uiConfig->getSaveAndCloseLabel());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getAllowSaveAndClose
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetAllowSaveAndClose()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertTrue($uiConfig->getAllowSaveAndClose());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['save' => ['allow_close' => false]]
        );
        $this->assertFalse($uiConfig->getAllowSaveAndClose());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getAllowSaveAndDuplicate
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetAllowSaveAndDuplicate()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertTrue($uiConfig->getAllowSaveAndDuplicate());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['save' => ['allow_duplicate' => false]]
        );
        $this->assertFalse($uiConfig->getAllowSaveAndDuplicate());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getSaveFormTarget
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetSaveFormTarget()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $expected = 'somemodule_some_entity_form.somemodule_some_entity_form';
        $this->assertEquals($expected, $uiConfig->getSaveFormTarget());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['save_form_target' => 'save_form_target']
        );
        $this->assertEquals('save_form_target', $uiConfig->getSaveFormTarget());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getDeleteLabel
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetDeleteLabel()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('Delete', $uiConfig->getDeleteLabel());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['labels' => ['delete' => 'Delete entity']]
        );
        $this->assertEquals('Delete entity', $uiConfig->getDeleteLabel());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getDeleteMessage
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetDeleteMessage()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('Are you sure you want to delete the item?', $uiConfig->getDeleteMessage());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['labels' => ['delete_message' => 'Really?']]
        );
        $this->assertEquals('Really?', $uiConfig->getDeleteMessage());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getDeletePopupTitle
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetDeletePopupTitle()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('Delete "${ $.$data.title }"', $uiConfig->getDeletePopupTitle());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['labels' => ['delete_title' => 'Confirm Delete "${ $.$data.%1 }"'], 'name_attribute' => 'name']
        );
        $this->assertEquals('Confirm Delete "${ $.$data.name }"', $uiConfig->getDeletePopupTitle());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getRequestParamName
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetRequestParamName()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('some_entity_id', $uiConfig->getRequestParamName());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['request_param' => 'request_param']
        );
        $this->assertEquals('request_param', $uiConfig->getRequestParamName());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getListPageTitle
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetListPageTitle()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('Some Entity', $uiConfig->getListPageTitle());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['list' => ['page_title' => 'Page Title']]
        );
        $this->assertEquals('Page Title', $uiConfig->getListPageTitle());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getMenuItem
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetMenuItem()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('SomeNamespace_SomeModule::somemodule_some_entity', $uiConfig->getMenuItem());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['menu' => 'Menu_Item']
        );
        $this->assertEquals('Menu_Item', $uiConfig->getMenuItem());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getDeleteSuccessMessage
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetDeleteSuccessMessage()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('Item was deleted successfully', $uiConfig->getDeleteSuccessMessage());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['messages' => ['delete' => ['success' => 'Successful delete']]]
        );
        $this->assertEquals('Successful delete', $uiConfig->getDeleteSuccessMessage());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getDeleteMissingEntityMessage
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetDeleteMissingEntityMessage()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('Item for delete was not found', $uiConfig->getDeleteMissingEntityMessage());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['messages' => ['delete' => ['missing_entity' => 'Missing entity to delete']]]
        );
        $this->assertEquals('Missing entity to delete', $uiConfig->getDeleteMissingEntityMessage());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getGeneralDeleteErrorMessage
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetGeneralDeleteErrorMessage()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('There was a problem deleting the item.', $uiConfig->getGeneralDeleteErrorMessage());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['messages' => ['delete' => ['error' => 'Something Happened']]]
        );
        $this->assertEquals('Something Happened', $uiConfig->getGeneralDeleteErrorMessage());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getSaveSuccessMessage
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetSaveSuccessMessage()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('Item was saved successfully.', $uiConfig->getSaveSuccessMessage());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['messages' => ['save' => ['success' => 'Save success']]]
        );
        $this->assertEquals('Save success', $uiConfig->getSaveSuccessMessage());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getSaveErrorMessage
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetSaveErrorMessage()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('There was a problem saving the item.', $uiConfig->getSaveErrorMessage());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['messages' => ['save' => ['error' => 'Save error']]]
        );
        $this->assertEquals('Save error', $uiConfig->getSaveErrorMessage());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getDuplicateSuccessMessage
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetDuplicateSuccessMessage()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('Item was duplicated successfully.', $uiConfig->getDuplicateSuccessMessage());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['messages' => ['save' => ['duplicate' => 'Duplication success']]]
        );
        $this->assertEquals('Duplication success', $uiConfig->getDuplicateSuccessMessage());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getMassDeleteSuccessMessage
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetMassDeleteSuccessMessage()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('5 items were successfully deleted', $uiConfig->getMassDeleteSuccessMessage(5));
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['messages' => ['mass_delete' => ['success' => '%1 items deleted']]]
        );
        $this->assertEquals('5 items deleted', $uiConfig->getMassDeleteSuccessMessage(5));
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getMassDeleteErrorMessage
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetMassDeleteErrorMessage()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('There was a problem deleting the items', $uiConfig->getMassDeleteErrorMessage());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['messages' => ['mass_delete' => ['error' => 'Mass delete error']]]
        );
        $this->assertEquals('Mass delete error', $uiConfig->getMassDeleteErrorMessage());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getNewLabel
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetNewLabel()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('Add new item', $uiConfig->getNewLabel());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['labels' => ['new' => 'Add new']]
        );
        $this->assertEquals('Add new', $uiConfig->getNewLabel());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getNameAttribute
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetNameAttribute()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('title', $uiConfig->getNameAttribute());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['name_attribute' => 'name']
        );
        $this->assertEquals('name', $uiConfig->getNameAttribute());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getPersistoryKey
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetPersistoryKey()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('some_entity', $uiConfig->getPersistoryKey());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['persistor_key' => 'persistor']
        );
        $this->assertEquals('persistor', $uiConfig->getPersistoryKey());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getEditUrlPath
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetEditUrlPath()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('somemodule/someentity/edit', $uiConfig->getEditUrlPath());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['edit_url' => 'edit_url']
        );
        $this->assertEquals('edit_url', $uiConfig->getEditUrlPath());
    }

    /**
     * @covers \Umc\Crud\Ui\EntityUiConfig::getDeleteUrlPath
     * @covers \Umc\Crud\Ui\EntityUiConfig::parseInterfaceName
     * @covers \Umc\Crud\Ui\EntityUiConfig::__construct
     */
    public function testGetDeleteUrlPath()
    {
        $uiConfig = new EntityUiConfig('SomeNamespace\SomeModule\Api\Data\SomeEntityInterface');
        $this->assertEquals('somemodule/someentity/delete', $uiConfig->getDeleteUrlPath());
        $uiConfig = new EntityUiConfig(
            'SomeNamespace\SomeModule\Api\Data\SomeEntityInterface',
            ['delete_url' => 'delete_url']
        );
        $this->assertEquals('delete_url', $uiConfig->getDeleteUrlPath());
    }
}
