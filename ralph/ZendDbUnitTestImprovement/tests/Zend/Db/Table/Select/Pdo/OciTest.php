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
 * @package    Zend_Db
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

require_once 'Zend/Db/Table/Select/TestCommon.php';

PHPUnit_Util_Filter::addFileToFilter(__FILE__);

class Zend_Db_Table_Select_Pdo_OciTest extends Zend_Db_Table_Select_TestCommon
{

    /**
     * ZF-4330: this test must be done on string field
     */
    protected function _selectColumnWithColonQuotedParameter ()
    {
        $product_name = $this->sharedFixture->dbAdapter->quoteIdentifier('product_name');

        $select = $this->sharedFixture->dbAdapter->select()
                            ->from('zf_products')
                            ->where($product_name . ' = ?', "as'as:x");
        return $select;
    }

    /**
     * ZF-4330 : Oracle doesn't use 'AS' to identify table alias
     */
    public function testSelectFromSelectObject ()
    {
        $select = $this->_selectFromSelectObject();
        $query = $select->assemble();
        $cmp = 'SELECT ' . $this->sharedFixture->dbAdapter->quoteIdentifier('t') . '.* FROM (SELECT '
                         . $this->sharedFixture->dbAdapter->quoteIdentifier('subqueryTable') . '.* FROM '
                         . $this->sharedFixture->dbAdapter->quoteIdentifier('subqueryTable') . ') '
                         . $this->sharedFixture->dbAdapter->quoteIdentifier('t');
        $this->assertEquals($query, $cmp);
    }

    public function getDriver()
    {
        return 'Pdo_Oci';
    }

}
