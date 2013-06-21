<?php
/**
 * Part of Joomla Sphinx Search Component Package
 *
 * @package Joomla-Sphinx-Search-component
 * @copyright Copyright (C) 2012-2013 Dmitri Perunov <dmitri.perunov@gmail.com>
 * @license GNU General Public License, Version 3; see LICENSE
 */

// No direct access to this file
defined('_JEXEC') or die('Restricted access');

/**
 * Installer script class of SphinxSearch component
 */
class Com_SphinxSearchInstallerScript
{
    /**
     * Method to install the component
     *
     * @param string $parent is the class calling this method
     *
     * @return void
     */
    public function install($parent)
    {
        $parent->getParent()->setRedirectURL(
            'index.php?option=com_sphinxsearch'
        );
    }

    /**
     * Method to uninstall the component
     *
     * @param string $parent is the class calling this method
     *
     * @return void
     */
    public function uninstall($parent)
    {
        echo '<p>' . JText::_('COM_SPHINXSEARCH_UNINSTALL_TEXT') . '</p>';
    }

    /**
     * Method to update the component
     *
     * @param string $parent is the class calling this method
     *
     * @return void
     */
    public function update($parent)
    {
        // $parent is the class calling this method
        echo '<p>' . JText::_('COM_SPHINXSEARCH_UPDATE_TEXT') . '</p>';
    }

    /**
     * Method to run before an install/update/uninstall method
     *
     * @param string $type is the type of change (install, update
     * or discover_install)
     * @param string $parent is the class calling this method
     *
     * @return void
     */
    public function preflight($type, $parent)
    {
        echo '<p>' . JText::_('COM_SPHINXSEARCH_PREFLIGHT_' . $type . '_TEXT')
            . '</p>';
    }

    /**
     * Method to run after an install/update/uninstall method
     *
     * @param string $type is the type of change (install, update
     * or discover_install)
     * @param string $parent is the class calling this method
     *
     * @return void
     */
    public function postflight($type, $parent)
    {
        echo '<p>' . JText::_('COM_SPHINXSEARCH_POSTFLIGHT_' . $type . '_TEXT')
            . '</p>';
    }
}
