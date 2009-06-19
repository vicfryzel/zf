<?php

require_once 'Zend/Db/TestSuite/DbUtility/AbstractUtility.php';

require_once 'Zend/Db/TestSuite/DbUtility/SQLDialect/Db2.php';

class Zend_Db_TestSuite_DbUtility_Db2Utility extends Zend_Db_TestSuite_DbUtility_AbstractUtility 
{

    public function getDriverConfigurationAsParams()
    {
        $constants = array(
            'TESTS_ZEND_DB_ADAPTER_DB2_HOSTNAME' => 'host',
            'TESTS_ZEND_DB_ADAPTER_DB2_USERNAME' => 'username',
            'TESTS_ZEND_DB_ADAPTER_DB2_PASSWORD' => 'password',
            'TESTS_ZEND_DB_ADAPTER_DB2_DATABASE' => 'dbname',
            'TESTS_ZEND_DB_ADAPTER_DB2_PORT'     => 'port'
            );
        $params = parent::_getConstantAsParams($constants);

        if (isset($GLOBALS['TESTS_ZEND_DB_ADAPTER_DB2_DRIVER_OPTIONS'])) {
            $params['driver_options'] = $GLOBALS['TESTS_ZEND_DB_ADAPTER_DB2_DRIVER_OPTIONS'];
        }
        
        return $params;
    }
    
    public function getSchema()
    {
        $desc = $this->_dbAdapter->describeTable('zf_products');
        return $desc['product_id']['SCHEMA_NAME'];
    }
    
    public function getSQLDialect()
    {
        return new Zend_Db_TestSuite_DbUtility_SQLDialect_Db2();
    }

    protected function _getDefaultTableDataArray()
    {
    	$data = parent::_getDefaultTableDataArray();
    	$data['Documents'][0]['doc_blob'] = new Zend_Db_Expr(
    	   'BLOB(\'' . $data['Documents'][0]['doc_blob'] . '\')'
    	   );
        return $data;
    }
    
    protected function _executeRawQuery($sql)
    {
        $conn = $this->_dbAdapter->getConnection();
        try {
            $result = db2_exec($conn, $sql);
        } catch (Exception $e) {
            require_once 'Zend/Db/Exception.php';
            throw new Zend_Db_Exception("SQL error for \"$sql\": " . $e->getMessage());
        }
        
        if (!$result) {
            $e = db2_stmt_errormsg();
            require_once 'Zend/Db/Exception.php';
            throw new Zend_Db_Exception("SQL error for \"$sql\": $e");
        }
    }

}
