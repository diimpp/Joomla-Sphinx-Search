<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.controller');

/**
 * Sphinx Search Controller.
 *
 * @package     SphinxSearch
 * @subpackage  com_sphinxsearch
 */
class SphinxSearchController extends JController
{
    public function search()
    {
        // Check for request forgeries.
        JRequest::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app        = JFactory::getApplication();
        $menus      = $app->getMenu();

        // Get the form.
        jimport('joomla.form.form');
        JForm::addFormPath(JPATH_COMPONENT . DS . 'models' . DS . 'forms');
        $form = JForm::getInstance('input', 'input', array('array' => true), true);

        $post = JRequest::get('post');
        $post = $form->filter($post);

        //$post['ordering']      = JRequest::getWord('ordering', null, 'post');
        //$post['searchphrase']  = JRequest::getWord('searchphrase', 'all', 'post');
        //$post['limit']         = JRequest::getInt('limit', null, 'get');


        // Redirect back to the previous screen.
        $uri = JURI::getInstance();
        $uri->setQuery($post);
        $this->setRedirect(JRoute::_($uri->toString()));


        parent::display();
    }
}
