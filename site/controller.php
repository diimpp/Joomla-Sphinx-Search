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

// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport('joomla.application.component.controller');

/**
 * 
 */
class SphinxSearchController extends JController
{
    function search()
    {
	$model = $this->getModel("search");
	$this->setRedirect($model->buildQueryURL($_POST));
    }
    /**
     * Method to display the view
     *
     * @access    public
     */
    function display()
    {
        $default = "basic";

	$viewName = JRequest::getWord("view", $default);

	$modelName = $viewName;

	if ($modelName == $default) {
            $modelName = "search";
	}

	$model = $this->getModel($modelName);

	$view = $this->getView($viewName, JRequest::getWord("format", "html"));
	$view->setModel($model, true);

	if (($viewName == "" || $viewName == $default) && trim(JRequest::getString("q", null))) {
            $model->setQueryParams(JRequest::get("get"));
            $view->setLayout("results");
	}
        
        $view->display();
    }

}
