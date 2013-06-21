<?php
/**
 * Part of Joomla Sphinx Search Component Package
 *
 * @package Joomla-Sphinx-Search-component
 * @copyright Copyright (C) 2012-2013 Dmitri Perunov <dmitri.perunov@gmail.com>
 * @license GNU General Public License, Version 3; see LICENSE
 */

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.helper');

/**
 * Sphinx Search route helper class.
 */
class SphinxsearchHelperRoute
{
    private $app;
    private $menus;

    public function __construct()
    {
        $this->app   = JFactory::getApplication();
        $this->menus = $this->app->getMenu();
    }

    /**
     * Method to get the route for a search page.
     *
     * @return string The search route.
     */
    public static function getSearchRoute()
    {
        // Get the base route.
        $url        = 'index.php?option=com_sphinxsearch&view=search';
        $app        = JFactory::getApplication();
        $menus	    = $app->getMenu();
        $menuItem   = $menus->getItems('link', $url, true);

        return $menuItem->route;
    }

    /**
     * Method to get the route for an advanced search page.
     *
     * @param integer $f The search filter id. [optional]
     * @param string  $q The search query string. [optional]
     *
     * @return string The advanced search route.
     *
     * @since   2.5
     */
    public static function getAdvancedRoute($f = null, $q = null)
    {
        // Get the menu item id.
        $query = array('view' => 'advanced', 'q' => $q, 'f' => $f);
        $item = self::getItemid($query);

        // Get the base route.
        $uri = clone(JUri::getInstance('index.php?option=com_finder&view=advanced'));

        // Add the pre-defined search filter if present.
        if ($q !== null) {
            $uri->setVar('f', $f);
        }

        // Add the search query string if present.
        if ($q !== null) {
            $uri->setVar('q', $q);
        }

        // Add the menu item id if present.
        if ($item !== null) {
            $uri->setVar('Itemid', $item);
        }

        return $uri->toString(array('path', 'query'));
    }

    /**
     * Method to get the most appropriate menu item for the route based on the
     * supplied query needles.
     *
     * @param array $query An array of URL parameters.
     *
     * @return mixed An integer on success, null otherwise.
     *
     * @since   2.5
     */
    public static function getItemid($query)
    {
        static $items, $active;

        // Get the menu items for com_finder.
        if (!$items || !$active) {
            $app = JFactory::getApplication('site');
            $com = JComponentHelper::getComponent('com_finder');
            $menu = $app->getMenu();
            $active = $menu->getActive();
            $items = $menu->getItems('component_id', $com->id);
            $items = is_array($items) ? $items : array();
        }

        // Try to match the active view and filter.
        if ($active && @$active->query['view'] == @$query['view']
            && @$active->query['f'] == @$query['f']
        ) {
            return $active->id;
        }

        // Try to match the view, query, and filter.
        foreach ($items as $item) {
            if (@$item->query['view'] == @$query['view']
                && @$item->query['q'] == @$query['q']
                && @$item->query['f'] == @$query['f']
            ) {
                return $item->id;
            }
        }

        // Try to match the view and filter.
        foreach ($items as $item) {
            if (@$item->query['view'] == @$query['view']
                && @$item->query['f'] == @$query['f']
            ) {
                return $item->id;
            }
        }

        // Try to match the view.
        foreach ($items as $item) {
            if (@$item->query['view'] == @$query['view']) {
                return $item->id;
            }
        }

        return null;
    }
}
