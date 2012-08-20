<?php

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.model' );
jimport('joomla.html.pagination');

/**
 * SphinxSearch search model.
 *
 * @package     SphinxSearch
 * @subpackage  com_sphinxsearch
 */
class SphinxSearchModelSearch extends JModel
{
	/**
     * Plugin type to work with.
     *
     * All SphinxSearch plugins should have this type.
	 *
	 * @const   string
	 */
    const PLUGINTYPE = 'sphinxsearch';

	/**
     * Search results.
	 *
	 * @var    array
	 */
    private $_matches;

	/**
     * Number of search results.
	 *
	 * @var    int
	 */
    private $_total;

	/**
	 * Array of params to prepare search.
	 *
	 * @var    array
	 */
    private $_params;

    /*
    // XXX: Example of state use. (2012-08-20 Mon 05:59 PM (NOVT), Dmitri Perunov)         
    public function __construct()
    {
        parent::__construct();

        $application = JFactory::getApplication("site");

        $this->setState('limit', $application->getUserStateFromRequest('com_sphinxsearch.limit', 'limit', 10, 'int'));
        $this->setState('limitstart', JRequest::getVar('limitstart', 0, '', 'int'));
    }
     */

    /**
     * Getter for total search results.
     *
     * @return  mixed   Int on success, false if called before search.
     */
    public function getTotal()
    {
        if (!isset($this->_total)) {
            return false;
        }

        return $this->_total;
    }

    /**
     * Getter for parameters.
     *
     * @return  array
     */
    public function getParams()
    {
        if (!isset($this->_params)) {
            return false;
        }

        return $this->_params;
    }

    /**
     * Getter for pagination.
     *
     * @param   int  $offset
     * @param   int  $limit
     * @return  object
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
     * @param   string  $query
     * @return  mixed   Array on success.
     */
    public function getResults($query)
    {
        if (!isset($this->_matches)) {
            if (!$this->_search($query))
            {
                return false;
            }
        }

        return $this->_matches;
    }

    /**
     * Perform search.
     *
     * @param   string   $query
     * @return  bool
     */
    private function _search($query) 
    {
        // TODO: Make some sanitization action on input. (2012-07-26 Thu 04:19 PM (NOVT), Dmitri Perunov)
        if (!is_string($query)) {
            return false;
        }


        $plugins = JPluginHelper::getPlugin($this::PLUGINTYPE);
        if (empty($plugins)) {
            // TODO: Return message to admin part that no sphinx
            // plg enabled or installed. (2012-07-30 Mon 06:57 PM (NOVT), Dmitri Perunov)
            return false;
        }

        foreach ($plugins as $plugin) {
            JPluginHelper::importPlugin($this::PLUGINTYPE, $plugin->name);
            $dispatcher = JDispatcher::getInstance();
            $params     = $dispatcher->trigger('onPrepareSphinxSearch');
            $results    = $dispatcher->trigger('onSphinxSearch', $query);

            // HACK: An associative array was returned in another array with
            // numeric index. WTF? (2012-08-07 Tue 04:59 PM (NOVT), Dmitri Perunov)
            $this->_matches     = &$results[0]['matches'];
            //$this->_matches     = &$results;
            //$this->_searchTime  = &$results['time'];
            $this->_total       = &$results[0]['total'];
            //$this->_totalFound  = &$results['total_found'];
            $this->_params      = &$params[0];
        }

        return true;
    }
}
