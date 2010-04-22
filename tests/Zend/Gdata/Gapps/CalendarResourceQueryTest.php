<?php
/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category   Zend
 * @package    Zend_Gdata_Gapps
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id $
 */

require_once 'Zend/Gdata/Gapps.php';
require_once 'Zend/Gdata/Gapps/CalendarResourceQuery.php';

/**
 * @category   Zend
 * @package    Zend_Gdata_Gapps
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @group      Zend_Gdata
 * @group      Zend_Gdata_Gapps
 */
class Zend_Gdata_Gapps_CalendarResourceQueryTest extends PHPUnit_Framework_TestCase
{

    public function setUp()
    {
        $this->query = new Zend_Gdata_Gapps_CalendarResourceQuery("example.com");
    }

    public function testDefaultQueryURIGeneration()
    {
        $this->query->setDomain("example.com");
        $this->assertEquals("https://apps-apis.google.com/a/feeds/calendar/resource/2.0/example.com",
                $this->query->getQueryUrl());
    }

    public function testCanSetQueryDomain()
    {
        $this->assertEquals("example.com", $this->query->getDomain());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/calendar/resource/2.0/example.com",
                $this->query->getQueryUrl());

        $this->query->setDomain("hello.world.baz");
        $this->assertEquals("hello.world.baz", $this->query->getDomain());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/calendar/resource/2.0/hello.world.baz",
                $this->query->getQueryUrl());
    }

    public function testCanSetResourceIdProperty()
    {
        $this->query->setResourceId("CR-NYC-14-12-BR");
        $this->assertEquals("CR-NYC-14-12-BR", $this->query->getResourceId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/calendar/resource/2.0/example.com/CR-NYC-14-12-BR",
                $this->query->getQueryUrl());

        $this->query->setResourceId(null);
        $this->assertEquals(null, $this->query->getResourceId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/calendar/resource/2.0/example.com",
                $this->query->getQueryUrl());
    }

    public function testCanSetStartResourceIdProperty()
    {
        $this->query->setStartResourceId("CR-NYC-14-12-BR");
        $this->assertEquals("CR-NYC-14-12-BR", $this->query->getStartResourceId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/calendar/resource/2.0/example.com?start=CR-NYC-14-12-BR",
                $this->query->getQueryUrl());

        $this->query->setStartResourceId(null);
        $this->assertEquals(null, $this->query->getStartResourceId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/calendar/resource/2.0/example.com",
                $this->query->getQueryUrl());
    }

    public function testCanSetAllParameters()
    {
        $this->query->setResourceId("CR-MTV-14-12-BR");
        $this->query->setStartResourceId("CR-NYC-14-12-BR");
        $this->assertEquals("example.com", $this->query->getDomain());
        $this->assertEquals("CR-MTV-14-12-BR", $this->query->getResourceId());
        $this->assertEquals("CR-NYC-14-12-BR", $this->query->getStartResourceId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/calendar/resource/2.0/example.com/CR-MTV-14-12-BR?start=CR-NYC-14-12-BR",
                $this->query->getQueryUrl());

        $this->query->setDomain("my.domain.com");
        $this->query->setResourceId("OF-NYC-1-2-EX");
        $this->query->setStartResourceId("CU-NYC-1-2-LE");
        $this->assertEquals("my.domain.com", $this->query->getDomain());
        $this->assertEquals("OF-NYC-1-2-EX", $this->query->getResourceId());
        $this->assertEquals("CU-NYC-1-2-LE", $this->query->getStartResourceId());
        $this->assertEquals("https://apps-apis.google.com/a/feeds/calendar/resource/2.0/my.domain.com/OF-NYC-1-2-EX?start=CU-NYC-1-2-LE",
                $this->query->getQueryUrl());
    }

}
