<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magentocommerce.com for more information.
 *
 * @category    Magento
 * @package     Mage_Core
 * @subpackage  unit_tests
 * @copyright   Copyright (c) 2012 X.commerce, Inc. (http://www.magentocommerce.com)
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Test class for Mage_Core_Model_Url
 */
class Mage_Core_Model_UrlTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Mage_Core_Model_Url
     */
    protected  $_model;

    public function setUp()
    {
        $this->_model = new Mage_Core_Model_Url();
    }

    public function testSetRoutePath()
    {
        $moduleFrontName = 'moduleFrontName';
        $controllerName = 'controllerName';
        $actionName = 'actionName';

        $this->assertNull($this->_model->getRouteName());
        $this->assertNull($this->_model->getControllerName());
        $this->assertNull($this->_model->getActionName());

        $this->_model->setRoutePath($moduleFrontName . '/' . $controllerName . '/' . $actionName);

        $this->assertNotNull($this->_model->getRouteName());
        $this->assertEquals($moduleFrontName, $this->_model->getRouteName());

        $this->assertNotNull($this->_model->getControllerName());
        $this->assertEquals($controllerName, $this->_model->getControllerName());

        $this->assertNotNull($this->_model->getActionName());
        $this->assertEquals($actionName, $this->_model->getActionName());
    }

    public function testSetRoutePathWhenAsteriskUses()
    {
        $moduleFrontName = 'moduleFrontName';
        $controllerName = 'controllerName';
        $actionName = 'actionName';

        $requestMock = $this->getMock('Mage_Core_Controller_Request_Http');

        $requestMock->expects($this->once())->method('getRequestedRouteName')
            ->will($this->returnValue($moduleFrontName));
        $requestMock->expects($this->once())->method('getRequestedControllerName')
            ->will($this->returnValue($controllerName));
        $requestMock->expects($this->once())->method('getRequestedActionName')
            ->will($this->returnValue($actionName));

        $this->_model->setRequest($requestMock);

        $this->_model->setRoutePath('*/*/*');

        $this->assertEquals($moduleFrontName, $this->_model->getRouteName());
        $this->assertEquals($controllerName, $this->_model->getControllerName());
        $this->assertEquals($actionName, $this->_model->getActionName());
    }

    public function testSetRoutePathWhenRouteParamsExists()
    {
        $this->assertNull($this->_model->getControllerName());
        $this->assertNull($this->_model->getActionName());

        $this->_model->setRoutePath('m/c/a/p1/v1/p2/v2');

        $this->assertNotNull($this->_model->getControllerName());
        $this->assertNotNull($this->_model->getActionName());
        $this->assertNotEmpty($this->_model->getRouteParams());

        $this->assertArrayHasKey('p1', $this->_model->getRouteParams());
        $this->assertArrayHasKey('p2', $this->_model->getRouteParams());

        $this->assertEquals('v1', $this->_model->getRouteParam('p1'));
        $this->assertEquals('v2', $this->_model->getRouteParam('p2'));
    }
}
