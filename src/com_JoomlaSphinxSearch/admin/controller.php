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

jimport('joomla.application.component.controller');

/**
 * Base admin controller class.
 */
class SphinxSearchController extends JController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function save()
    {
        $model = $this->getModel(JRequest::getWord('view'));
        $model->save(JRequest::get('post'));
        $view = $this->getView(
            JRequest::getWord('view'), JRequest::getWord('format', 'html')
        );
        $view->setModel($model, true);
        $url = new JURI('index.php');
        $url->setVar('option', JRequest::getWord('option'));
        $url->setVar('view', JRequest::getWord('view'));
        $this->setRedirect(
            $url->toString(), JText::_('Configuration successfully saved.')
        );
    }

    function display()
    {
        $viewParam = JRequest::getWord('view');
        if (empty($viewParam)) {
            $viewParam = 'configuration';
        }
        $view = $this->getView($viewParam, JRequest::getWord('format', 'html'));
        if ('configuration' == $viewParam) {
            $model = $this->getModel($viewParam);
            $view->setModel($model, true);
            //check sphinx is up
            $view->sphinxRunning = $this->_checkSphinxConnection();
        }
        $view->display();
    }

    function _checkSphinxConnection()
    {
        $configuration = new SphinxSearchConfig();
        $client = new SphinxClient();
        $client->SetServer(
            $configuration->hostname, (int) $configuration->port
        );
        $client->Open();
        $error = $client->GetLastError();
        $running = false;
        if (empty($error)) {
            $running = true;
        }
        return $running;
    }
}
