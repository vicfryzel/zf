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
 * @package    Zend_Gdata
 * @subpackage Gapps
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id$
 */

/**
 * @see Zend_Gdata_Gapps_Query
 */
require_once('Zend/Gdata/Gapps/Query.php');

/**
 * Assists in constructing queries for Google Apps calendar resource entries.
 * Instances of this class can be provided in many places where a URL is
 * required.
 *
 * For information on submitting queries to a server, see the Google Apps
 * service class, Zend_Gdata_Gapps.
 *
 * @category   Zend
 * @package    Zend_Gdata
 * @subpackage Gapps
 * @copyright  Copyright (c) 2005-2010 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Gdata_Gapps_CalendarResourceQuery extends Zend_Gdata_Gapps_Query
{

    /**
     * If not null, indicates the id of the resource entry which should be
     * returned by this query.
     *
     * @var string
     */
    protected $_resourceId = null;

    /**
     * Create a new instance.
     *
     * @param string $domain (optional) The Google Apps-hosted domain to use
     *          when constructing query URIs.
     * @param string $resourceId (optional) ID for the resource to fetch.
     * @param string $startResourceId (optional) The resource ID to start the
     *         query at.
     */
    public function __construct($domain, $resourceId = null,
            $startResourceId = null)
    {
        parent::__construct($domain);
        $this->setResourceId($groupId);
        $this->setStartResourceId($startResourceId);
    }

    /**
     * Set the resource ID to query for. When set, only groups with a resource
     * ID of the given value will be retrieved, updated, or deleted.  Set this
     * to null to disable this parameter.
     *
     * @see getResourceId
     * @param string $value The resource ID of the resource to retrieve, update,
     *          or delete.  Should be null to disable this parameter.
     */
     public function setResourceId($value)
     {
         $this->_resourceId = $value;
     }

    /**
     * Get the resource ID currently set to query for.
     *
     * @see setResourceId
     * @return string The resource ID to query for, or null if this parameter 
     *         is not set.
     */
    public function getResourceId()
    {
        return $this->_resourceId;
    }

    /**
     * Set the first resource ID which should be displayed when retrieving
     * a list of resources.
     *
     * @param string $value The first resource ID to be returned, or null to
     *              disable.
     */
    public function setStartResourceId($value)
    {
        if ($value !== null) {
            $this->_params['start'] = $value;
        } else {
            unset($this->_params['start']);
        }
    }

    /**
     * Get the first resource ID which should be displayed when retrieving
     * a list of resources.
     *
     * @return string The first resource ID to be returned, or null if this
     *              parameter is disabled.
     */
    public function getStartResourceId()
    {
        if (array_key_exists('start', $this->_params)) {
            return $this->_params['start'];
        } else {
            return null;
        }
    }

    /**
     * Returns the base URL used to access the Calendar Resource Google Apps 
     * service. This must be defined here to override the default
     * implementation, as calendar resource feed URLs do not follow the URL
     * convention of other feeds. The domain is added to the URL later.
     *
     * @param string $domain (unused) Accepted here to conform to the base
     *          class definition, but is not used in this implementation.
     */
     public function getBaseUrl($domain = null) 
     {
         return Zend_Gdata_Gapps::APPS_BASE_FEED_URI;
     }

    /**
     * Returns the URL generated for this query, based on its current
     * parameters.
     *
     * @return string A URL generated based on the state of this query.
     */
    public function getQueryUrl()
    {

        $uri = $this->getBaseUrl();
        $uri .= Zend_Gdata_Gapps::APPS_CALENDAR_RESOURCE_PATH;
        $uri .= '/' . $this->domain;
        if ($this->getResourceId() !== null) {
            $uri .= '/' . urlencode($this->getResourceId());
        }
        $uri .= $this->getQueryString();
        return $uri;
    }

}
