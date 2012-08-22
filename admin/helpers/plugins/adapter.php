<?php
/**
 * @package     SphinxSearch
 * @subpackage  com_sphinxsearch
 */

defined('_JEXEC') or die;

/**
 * Adapter class for the Sphinxsearch plugins.
 *
 * @package     SphinxSearch
 * @subpackage  com_sphinxsearch
 */
abstract class SphinxSearchAdapter extends JPlugin
{
	/**
	 * The database object.
	 *
	 * @var object
	 */
    protected $_db;
    
    /**
     * Total search time in seconds.
     *
     * @var float
     */
    private $_searchTime;

    /**
     * An array with found document IDs as keys and their weight and
     * attributes values as values.
     *
     * @var array
     */
    private $_matches;

    /**
     * Total number of matches found and retrieved (depends on your settings).
     *
     * @var int
     */
    private $_total;

    /**
     * Total number of found documents matching the query.
     *
     * @var int
     */
    private $_totalFound;

    /**
     * Query to search.
     *
     * @var string
     */
    private $_query;

    /**
     * Sphinx index name.
     *
     * @var string
     */
    private $_index;

    /**
     * Sphinx Search object.
     *
     * @var object
     */
    private $_sphinx;

    /**
     * The extension name.
     *
     * @var string
     */
    protected $_extension;

    /**
     * Tha layout template folder.
     *
     * @var string
     */
    protected $_template;

    /**
     * The layout name.
     *
     * @var string
     */
    protected $_layout;


    /**
     * Event handler.
     *
     * Triggered by component search model.
     *
     * @param string $query
     * @return array
     */
    abstract public function onSphinxSearch($query);

    /**
     * Constructor.
     *
     * @param object $subject
     * @param array $config
     * @return void
     */
	public function __construct($subject, $config)
	{
		parent::__construct(&$subject, $config);

		$this->_db = JFactory::getDBO();

        // TODO: Replace with options from DB. (2012-08-02 Thu 04:14 PM (NOVT), Dmitri Perunov)
        $server         = 'localhost';
        $port           = (int) 9312;
        $this->_index   = 'test1';

        $this->_setSphinx($server, $port);
        $this->_setLimits();
	}

    /**
     * Event handler.
     *
     * Triggered by com_sphinxsearch search model.
     *
     * @param string $query
     * @return array
     */
    public function onPrepareSphinxSearch()
    {
        return array('extension' => $this->_extension,
            'template' => $this->_template, 'layout' => $this->_layout);
    }

    /**
     * Init sphinx client.
     *
     * @param string $server
     * @param int $port
     * @return bool
     */
    private function _setSphinx($server, $port)
    {
        if (!isset($this->_sphinx)) {
            $this->_sphinx = new SphinxClient();
            if (!$this->_sphinx->setServer($server, $port)) {
                // TODO: Make some named error about false creditionals
                //or server is not running. (2012-08-02 Thu 05:15 PM (NOVT), Dmitri Perunov)
                return false;
            }
        }

        return true;
    }

    /**
     * Setter of search limits.
     *
     * @param int $offset
     * @param int $limit
     * @return bool
     */
    protected function _setLimits($offset = 0, $limit = 10)
    {
        if(!$this->_sphinx->setLimits($offset, $limit)) {
            return false;
        }

        return true;
    }

    /**
     * Perform search.
     *
     * @param string $query search query
     * @return bool
     */
    private function _search($query)
    {
        if (empty($query)) {
            $this->_matches     = 0;
            $this->_searchTime  = 0;
            $this->_total       = 0;
            $this->_totalFound  = 0;

            return true;
        }

        // TODO: Do we really need trim()? (2012-08-02 Thu 08:08 PM (NOVT), Dmitri Perunov)
        // Check for Joomla platform variation of trim.
        $this->_query = trim($this->_sphinx->escapeString($query));

        $result = $this->_sphinx->query($query, $this->_index);

        if ($result === false) {
            //echo "Query failed: " . $this->_sphinx->GetLastError() . ".\n";\
            return false;
        } elseif ($this->_sphinx->getLastWarning()) {
            //echo "WARNING: " . $this->_sphinx->GetLastWarning() . "\n";
            return false;
        }

        $this->_matches     = &$result['matches'];
        $this->_searchTime  = &$result['time'];
        $this->_total       = &$result['total'];
        $this->_totalFound  = &$result['total_found'];

        return true;
    }

    /**
     * Get DB rows ids of search results.
     *
     * @param string $query search query
     * @return array
     */
    protected function _getIds($query)
    {
        if (!isset($this->_matches)) {
            if ($this->_search(&$query)) {
                return array_keys($this->_matches);
            }
        }

        return $this->_matches;
    }

    /**
     * Getter of total found number results.
     *
     * @param string $query search query
     * @return array
     */
    protected function _getTotal()
    {
        if (!isset($this->_total)) {
            return false;
        }

        return $this->_total;
    }

    /**
     * Setter for matching mode.
     *
     * SPH_MATCH_ALL        Match all query words (default mode).
     * SPH_MATCH_ANY        Match any of query words.
     * SPH_MATCH_PHRASE     Match query as a phrase, requiring perfect match.
     * SPH_MATCH_BOOLEAN    Match query as a boolean expression.
     * SPH_MATCH_EXTENDED   Match query as an expression in Sphinx internal 
     * query language.
     * SPH_MATCH_FULLSCAN   Enables fullscan.
     * SPH_MATCH_EXTENDED2  The same as SPH_MATCH_EXTENDED plus ranking and
     * quorum searching support.
     *
     * @param string $mode
     * @return bool
     */
    protected function _setMatchMode($mode)
    {
        switch ($mode) {
            case 'any':
                $this->_sphinx->setMatchMode(SPH_MATCH_ANY);
                break;
            case 'phrase':
                $this->_sphinx->setMatchMode(SPH_MATCH_PHRASE);
                break;
            case 'boolean':
                $this->_sphinx->setMatchMode(SPH_MATCH_BOOLEAN);
                break;
            case 'extended':
                $this->_sphinx->setMatchMode(SPH_MATCH_EXTENDED);
                break;
            case 'fullscan':
                $this->_sphinx->setMatchMode(SPH_MATCH_FULLSCAN);
                break;
            case 'extended2':
                $this->_sphinx->setMatchMode(SPH_MATCH_EXTENDED2);
                break;
            case 'all':
            default:
                $this->_sphinx->setMatchMode(SPH_MATCH_ALL);
                break;
        }

    }

    protected function _setOrder($order)
    {
    // TODO: Rewrite it. (2012-08-06 Mon 02:02 PM (NOVT), Dmitri Perunov)
    //    switch ($order) {
    //        case 'newest':
    //            $this->_sphinx->setSortMode(SPH_SORT_ATTR_DESC, 'created');
    //            break;
    //        case 'oldest':
    //            $this->_sphinx->setSortMode(SPH_SORT_ATTR_ASC, 'created');
    //            break;
    //        case 'popular':
    //            $this->_sphinx->setSortMode(SPH_SORT_ATTR_DESC, 'hits');
    //            break;
    //        case 'category':
    //            $this->_sphinx->setSortMode(SPH_SORT_ATTR_ASC, 'catid');
    //            break;
    //        case 'alpha':
    //            $this->_sphinx->setSortMode(SPH_SORT_ATTR_ASC, 'title');
    //            break;
    //        default:
    //            break;
    //    }
        return false;
    }
}
