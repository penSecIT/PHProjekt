<?php
/**
 * Unit test
 *
 * This software is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License version 3 as published by the Free Software Foundation
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 *
 * @category   PHProjekt
 * @package    UnitTests
 * @subpackage Gantt
 * @copyright  Copyright (c) 2010 Mayflower GmbH (http://www.mayflower.de)
 * @license    LGPL v3 (See LICENSE file)
 * @link       http://www.phprojekt.com
 * @since      File available since Release 6.0
 * @version    Release: @package_version@
 * @author     Mariano La Penna <mariano.lapenna@mayflower.de>
 */

require_once 'PHPUnit/Framework.php';

/**
 * Tests Gantt Model class
 *
 * @category   PHProjekt
 * @package    UnitTests
 * @subpackage Gantt
 * @copyright  Copyright (c) 2010 Mayflower GmbH (http://www.mayflower.de)
 * @license    LGPL v3 (See LICENSE file)
 * @link       http://www.phprojekt.com
 * @since      File available since Release 6.0
 * @version    Release: @package_version@
 * @author     Mariano La Penna <mariano.lapenna@mayflower.de>
 * @group      gantt
 * @group      model
 * @group      gantt-model
 */
class Gantt_Models_Gantt_Test extends PHPUnit_Framework_TestCase
{
    private $_model = null;

    /**
     * setUp method for PHPUnit
     */
    public function setUp()
    {
        $this->_model = new Gantt_Models_Gantt();
    }

    /**
     * Test creating a item -in fact via via default model
     */
    public function testFetchAll()
    {
        $model    = clone($this->_model);
        $response = $model->fetchAll(null);
        $this->assertEquals(array(), $response);
    }
}
