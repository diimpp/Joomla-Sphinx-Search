<?php
/**
 *
 *
 * @author		Ivinco LTD.
 * @package		Ivinco
 * @subpackage	SphinxSearch
 * @copyright	Copyright (C) 2011 Ivinco Ltd. All rights reserved.
 * @license     This file is part of the SphinxSearch component for Joomla!.

   The SphinxSearch component for Joomla! is free software: you can redistribute it
   and/or modify it under the terms of the GNU General Public License as
   published by the Free Software Foundation, either version 3 of the License,
   or (at your option) any later version.

   The SphinxSearch component for Joomla! is distributed in the hope that it will be
   useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with the SphinxSearch component for Joomla!.  If not, see
   <http://www.gnu.org/licenses/>.

 * Contributors
 * Please feel free to add your name and email (optional) here if you have
 * contributed any source code changes.
 * Name							Email
 * Ivinco					<opensource@ivinco.com>
 *
 */

//no direct access
defined('_JEXEC') or die('Restricted access');

// Require the base controller
require_once(JPATH_COMPONENT.DS.'controller.php');

// Require sphinx search api
jimport('joomla.filesystem.file');
define('SPHINXAPI_RLTV', 'sphinxapi.php');
define('SPHINXAPI_LIB', JPATH_LIBRARIES . DS . 'sphinx' . DS . SPHINXAPI_RLTV);
define('SPHINXAPI_ABSLT', '/usr/share/sphinx/api/' . SPHINXAPI_RLTV);
if (JFile::exists(SPHINXAPI_RLTV)) {
    require_once(SPHINXAPI_RLTV);
} elseif (JFile::exists(SPHINXAPI_LIB)) {
    require_once(SPHINXAPI_LIB);
} elseif (JFile::exists(SPHINXAPI_ABSLT)) {
    require_once(SPHINXAPI_ABSLT);
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
