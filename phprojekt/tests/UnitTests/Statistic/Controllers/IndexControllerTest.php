<?php
/**
 * Unit test
 *
 * This software is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License version 2.1 as published by the Free Software Foundation
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * @copyright  Copyright (c) 2008 Mayflower GmbH (http://www.mayflower.de)
 * @license    LGPL 2.1 (See LICENSE file)
 * @version    $Id$
 * @link       http://www.phprojekt.com
 * @since      File available since Release 6.0
 */
require_once 'PHPUnit/Framework.php';

/**
 * Tests for Index Controller
 *
 * @copyright  Copyright (c) 2008 Mayflower GmbH (http://www.mayflower.de)
 * @license    LGPL 2.1 (See LICENSE file)
 * @version    Release: @package_version@
 * @link       http://www.phprojekt.com
 * @since      File available since Release 6.0
 * @author     Gustavo Solt <solt@mayflower.de>
 * @group      statistic
 * @group      controller
 * @group      statistic-controller
 */
class Statistic_IndexController_Test extends FrontInit
{
    /**
     * Test noraml call
     */
    public function testJsonGetStatistic()
    {
        $this->setRequestUrl('Statistic/index/jsonGetStatistic/');
        $this->request->setParam('startDate', '2009-05-01');
        $this->request->setParam('endDate', '2009-05-31');
        $this->request->setParam('nodeId', 1);

        $response = $this->getResponse();
        $this->assertContains('{"data":{"users":{"1":"david"},"projects":{"1":"Invisible Root","2":"....Project 1",'
            . '"4":"........Sub Project","6":"............Sub Sub Project 1","7":"............Sub Sub Project 2",'
            . '"5":"........Test Project"},"rows":{"1":{"1":120}}}})', $response);
    }

    /**
     * Test wrong call
     */
    public function testJsonGetStatisticWrong()
    {
        $this->setRequestUrl('Statistic/index/jsonGetStatistic/');
        $this->request->setParam('startDate', '2009-05-31');
        $this->request->setParam('endDate', '2009-05-01');
        $this->request->setParam('nodeId', 1);

        try {
            $this->front->dispatch($this->request, $this->response);
        } catch (Phprojekt_PublishedException $error) {
            $this->assertEquals("Period: End time can not be before Start time", $error->getMessage());
            return;
        }
    }
}
