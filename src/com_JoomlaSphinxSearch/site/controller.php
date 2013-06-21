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

jimport('joomla.application.component.controller');

/**
 * Sphinx Search Base Controller class.
 */
class SphinxSearchController extends JController
{
    /**
     * Search action.
     *
     * @return void
     */
    public function search()
    {
        // Check for request forgeries.
        JRequest::checkToken('post') or jexit(JText::_('JINVALID_TOKEN'));

        // Initialise variables.
        $app        = JFactory::getApplication();
        $menus      = $app->getMenu();

        // Get the form.
        jimport('joomla.form.form');
        JForm::addFormPath(JPATH_COMPONENT . '/models/forms');
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
