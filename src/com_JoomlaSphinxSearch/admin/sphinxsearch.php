<?php
/**
 * Part of Joomla Sphinx Search Component Package
 *
 * @package Joomla-Sphinx-Search-component
 * @copyright Copyright (C) 2012-2013 Dmitri Perunov <dmitri.perunov@gmail.com>
 * @license GNU General Public License, Version 3; see LICENSE
 */

//no direct access
defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once(JPATH_COMPONENT.DS.'controller.php');

// We need a sphinx api, so we should find it first.
jimport('joomla.filesystem.file');
define('SPHINXAPI_FILENAME', 'sphinxapi.php');
define('SPHINXAPI_LOCAL', JPATH_COMPONENT . '/' . SPHINXAPI_FILENAME);
define('SPHINXAPI_JLIB', JPATH_LIBRARIES . '/sphinx/' . SPHINXAPI_FILENAME);
define('SPHINXAPI_SYSTEM', '/usr/share/sphinx/api/' . SPHINXAPI_FILENAME);
if (JFile::exists(SPHINXAPI_LOCAL)) {
    require_once(SPHINXAPI_LOCAL);
} elseif (JFile::exists(SPHINXAPI_JLIB)) {
    require_once(SPHINXAPI_JLIB);
} elseif (JFile::exists(SPHINXAPI_SYSTEM)) {
    require_once(SPHINXAPI_SYSTEM);
}

// Require specific controller if requested
if(true == ($controller = JRequest::getWord('controller'))) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';

    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }

}

$classname = 'SphinxSearchController'.$controller;
$controller = new $classname();
$controller->execute(JRequest::getVar('task'));
$controller->redirect();
