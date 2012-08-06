<?php

defined('_JEXEC') or die;

/**
 * Function to parse route.
 *
 * @param   array   $query    URL query.
 * @return  array
 */
function SphinxSearchBuildRoute(&$query)
{
    $segments = array();
    if(isset($query['view']))
    {
        //$segments[] = $query['view'];
        unset($query['view']);
    }
    if(isset($query['id']))
    {
        $segments[] = $query['id'];
        unset($query['id']);
    };

    return $segments;
}

/**
 * Function to do actual routing.
 *
 * @param   array    $segments    Parts of URL query.
 * @return  array
 */
function SphinxSearchParseRoute($segments)
{
    $vars = array();
    $app  = JFactory::getApplication();
    $menu = $app->getMenu();
    $item = $menu->getActive();

    $count = count($segments);
    switch($item->query['view'])
    {
    case 'search':
        $id = &$segments[$count-1];
        $vars['id']   = $id;
        $vars['view'] = 'search';
        break;
    }
    return $vars;
}
