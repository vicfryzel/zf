<?php
/**
 * Mapper
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.
 * 
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@beberlei.de so we can send you a copy immediately.
 *
 * @category   Zend
 * @category   Zend_Entity
 * @copyright  Copyright (c) 2009 Benjamin Eberlei
 * @license    New BSD License
 */

class Zend_Entity_Mapper_Definition_ManyToOneRelation extends Zend_Entity_Mapper_Definition_AbstractRelation
{
    protected $_foreignKeyPropertyName = null;

    public function getForeignKeyPropertyName()
    {
        return $this->_foreignKeyPropertyName;
    }

    /**
     * Compile ManyToOne Relation Element
     *
     * @param Zend_Entity_Mapper_Definition_Entity $entityDef
     * @param Zend_Entity_Resource_Interface $map
     */
    public function compile(Zend_Entity_Mapper_Definition_Entity $entityDef, Zend_Entity_Resource_Interface $map)
    {
        parent::compile($entityDef, $map);

        $foreignDef = $map->getDefinitionByEntityName($this->getClass());
        $this->_foreignKeyPropertyName = $foreignDef->getPrimaryKey()->getPropertyName();

        if($this->getColumnName() == null) {
            $this->setColumnName(($this->getPropertyName()));
        }
    }
}