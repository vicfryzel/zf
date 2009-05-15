<?php

require_once dirname(__FILE__)."/../../../TestHelper.php";

require_once "CollectionTest.php";
require_once "EntityTest.php";

class Zend_Entity_Mapper_LazyLoad_AllTests
{
    static public function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('Zend_Entity_Mapper_LazyLoad Tests');
        $suite->addTestSuite('Zend_Entity_Mapper_LazyLoad_CollectionTest');
        $suite->addTestSuite('Zend_Entity_Mapper_LazyLoad_EntityTest');

        return $suite;
    }
}