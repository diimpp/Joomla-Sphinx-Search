<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the SphinxSearch Component
 *
 * @package     SphinxSearch
 * @subpackage  com_sphinxsearch
 */
class SphinxSearchViewSearch extends JView
{
    function display($tpl = null)
    {
        // Initialise variables.
        $app        = JFactory::getApplication();
        $document   = JFactory::getDocument();
        $dispatcher = JDispatcher::getInstance();
        $pathway    = $app->getPathway();
        $menus      = $app->getMenu();
        $menu       = $menus->getActive();
        $params     = $app->getParams();


        // Because the application sets a default page title, we need to get it
        // right from the menu item itself.
        if (is_object($menu)) {
            $menu_params = new JRegistry;
            $menu_params->loadString($menu->params);
            if (!$menu_params->get('page_title')) {
                $params->set('page_title',  JText::_('COM_SPHINXSEARCH_SEARCH'));
            }   
        }   
        else {
            $params->set('page_title',  JText::_('COM_SPHINXSEARCH_SEARCH'));
        }   

        $title = $params->get('page_title');
        if ($app->getCfg('sitename_pagetitles', 0) == 1) {
            $title = JText::sprintf('JPAGETITLE', $app->getCfg('sitename'), $title);
        }   
        elseif ($app->getCfg('sitename_pagetitles', 0) == 2) {
            $title = JText::sprintf('JPAGETITLE', $title, $app->getCfg('sitename'));
        }   
        $this->document->setTitle($title);

        if ($params->get('menu-meta_description'))
        {   
            $this->document->setDescription($params->get('menu-meta_description'));
        }   

        if ($params->get('menu-meta_keywords'))
        {   
            $this->document->setMetadata('keywords', $params->get('menu-meta_keywords'));
        }   

        if ($params->get('robots'))
        {
            $this->document->setMetadata('robots', $params->get('robots'));
        }


        $model      = $this->getModel('search');
        $query      = JRequest::getVar('searchword', '', 'get', 'string');


        $results    = $model->getResults($query);
        $total      = $model->getTotal();

        $offset = JRequest::getVar('limitstart', NULL, 'get', 'int');
        if (NULL == $offset) {
            $offset = JRequest::getVar('start', 0, 'get', 'int');
        }
        $limit = JRequest::getVar('limit', 10, 'get', 'int');
        $this->pagination = $model->getPagination($offset, $limit);

        
        // Form.
        jimport('joomla.form.form');
        JForm::addFormPath(JPATH_COMPONENT . DS . 'models' . DS . 'forms');
        $this->form = JForm::getInstance(
            'jform', 'input', array('array' => true), true
        );
        $this->form->setFieldAttribute('searchword', 'default', $query);


        // Get auxiliary data from plugin.
        $prmtrs = $model->getParams();
        if ($prmtrs) {
            // Get menu alias.
            $menuAlias = $this->_getMenuAlias(
                &$menus, $prmtrs['extension'], $prmtrs['template']
            );


            // Get sublayout.
            $subLayoutPath = $this->_getLayoutPath($prmtrs['extension'],
                $prmtrs['template'], $prmtrs['layout']);
            $subLayout = 'default';
            if (false != $subLayoutPath) {
                $subLayout = 'custom';
            }
        }


        // Breadcrumbs.
        if ($query) {
            $pathway->addItem($query);
        }


        // Assign variable to layouts.
        $this->assignRef('query', $query);
        $this->assignRef('results', $results);
        $this->assignRef('menuAlias', $menuAlias);
        $this->assignRef('total', $total);
        $this->assignRef('subLayout', $subLayout);
        $this->assignRef('subLayoutPath', $subLayoutPath);
        $this->assignRef('params', $params);


    	//$document->addStyleSheet(JURI::base() . 'media/com_sphinxsearch/css/sphinxsearch.css');


        parent::display($tpl);
    }

    /** 
     * Method to get alias of menu.
     *
     * @param   object  $menus
     * @param   string  $extension
     * @param   string  $template
     * @return  string  Full path to the layout file to use.
     */
    private function _getMenuAlias(JMenuSite $menus, $extension, $template)
    {   
        if (!isset($extension) || !isset($template)) {
            return false;
        }

        $menu = $menus->getMenu();
        foreach ($menu as $menuCh) {
            if (
                'index.php?option=' . $extension  . '&view='
                . $template == $menuCh->link
            ) {
                return $menuCh->alias;
            }
        }

        return false;
    }   

    /** 
     * Method to get the layout file for search result object.
     *
     * @param   string  $extension
     * @param   string  $template
     * @param   string  $layout  The layout file to check.
     * @return  string  Full path to the layout file to use.
     */
    private function _getLayoutPath($extension, $template, $layout)
    {   
        if (!isset($extension) || !isset($template) || !isset($layout)) {
            return false;
        }

        $path = JPATH_BASE . DS . 'components' . DS . $extension
            . DS . 'views' . DS . $template . DS . 'tmpl';
        jimport('joomla.filesystem.file');
        $layout = JFile::makeSafe($layout . '.php');
        
        //jimport('joomla.filesystem.path');
        //$exist = JPath::find($path, $layout);
        $file = $path . DS . $layout;
        if (JFile::exists($file)) {
            return $file;
        }

        return false;
    }   
}
