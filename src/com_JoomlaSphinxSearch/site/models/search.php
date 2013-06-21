<?php
/**
 * Part of Joomla Sphinx Search Component Package
 *
 * @package Joomla-Sphinx-Search-component
 * @copyright Copyright (C) 2012-2013 Dmitri Perunov <dmitri.perunov@gmail.com>
 * @license GNU General Public License, Version 3; see LICENSE
 */

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');
jimport('joomla.html.pagination');

/**
 * SphinxSearch search model class.
 */
class SphinxSearchModelSearch extends JModel
{
    /**
     * Plugin type to work with.
     *
     * All SphinxSearch plugins should have this type.
     *
     * @const string
     */
    const PLUGINTYPE = 'sphinxsearch';

    /**
     * Search results.
     *
     * @var array
     */
    private $matches;

    /**
     * Number of search results.
     *
     * @var int
     */
    private $total;

    /**
     * Array of params to prepare search.
     *
     * @var array
     */
    private $params;

    /*
    // XXX: Example of state use. (Dmitri Perunov)
    public function __construct()
    {
        parent::__construct();

        $application = JFactory::getApplication("site");

        $this->setState('limit',
            $application->getUserStateFromRequest('com_sphinxsearch.limit',
            'limit', 10, 'int')
        );
        $this->setState('limitstart',
            JRequest::getVar('limitstart', 0, '', 'int'));
    }
     */

    /**
     * Getter for total search results.
     *
     * @return mixed Int on success, false if called before search.
     */
    public function getTotal()
    {
        if (!isset($this->total)) {
            return false;
        }

        return $this->total;
    }

    /**
     * Getter for parameters.
     *
     * @return array
     */
    public function getParams()
    {
        if (!isset($this->params)) {
            return false;
        }

        return $this->params;
    }

    /**
     * Getter for pagination.
     *
     * @param int $offset
     * @param int $limit
     *
     * @return object
     */
    public function getPagination($offset = 0, $limit = 10)
    {
        if (!isset($this->pagination)) {
            $this->pagination = new JPagination($this->getTotal(), $offset, $limit);
        }

        return $this->pagination;
    }

    /**
     * Getter for search results.
     *
     * @param string $query
     *
     * @return mixed Array on success.
     */
    public function getResults($query)
    {
        if (!isset($this->matches)) {
            if (!$this->search($query)) {
                return false;
            }
        }

        return $this->matches;
    }

    /**
     * Perform search.
     *
     * @param string $query
     *
     * @return bool
     */
    private function search($query)
    {
        // TODO: Make some sanitization action on input. (Dmitri Perunov)
        if (empty($query) || !is_string($query)) {
            return false;
        }

        $plugins = JPluginHelper::getPlugin($this::PLUGINTYPE);
        if (empty($plugins)) {
            // TODO: Return message to admin part that no sphinx plg
            // enabled or installed. (Dmitri Perunov)
            return false;
        }

        foreach ($plugins as $plugin) {
            JPluginHelper::importPlugin($this::PLUGINTYPE, $plugin->name);
            $dispatcher = JDispatcher::getInstance();
            $params     = $dispatcher->trigger('onPrepareSphinxSearch');
            $results    = $dispatcher->trigger('onSphinxSearch', $query);
            // HACK: An associative array was returned in another array with
            // numeric index. WTF? (2012-08-07 Tue 04:59 PM (NOVT), Dmitri Perunov)
            $this->matches     = &$results[0]['matches'];
            //$this->searchTime  = &$results[0]['time'];
            $this->total       = &$results[0]['total'];
            //$this->totalFound  = &$results[0]['total_found'];
            $this->params      = &$params[0];
        }

        return true;
    }
}
