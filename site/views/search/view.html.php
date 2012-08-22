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
        $prepare = $model->getParams();


        // Get menu alias.
        $menuAlias = $this->_getMenuAlias(
            &$menus, $prepare['extension'], $prepare['template']
        );


        // Get sublayout.
        $this->subLayoutPath = $this->_getLayoutPath($prepare['extension'],
            $prepare['template'], $prepare['layout']);
        $this->subLayout = 'default';
        if (false != $this->subLayoutPath) {
            $this->subLayout = 'custom';
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
