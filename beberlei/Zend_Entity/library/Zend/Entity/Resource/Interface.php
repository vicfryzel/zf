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

interface Zend_Entity_Resource_Interface
{
    /**
     * Get an Entity Mapper Definition by the name of the Entity
     *
     * @param  string $entityName
     * @return Zend_Entity_Mapper_Definition_Entity
     */
    public function getDefinitionByEntityName($entityName);
}