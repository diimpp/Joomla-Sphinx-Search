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

jimport('joomla.registry.registry');
jimport('joomla.filesystem.file');

class SphinxSearchModelConfiguration extends JModel
{
    public $configuration;

    public function __construct()
    {
        parent::__construct();
        require_once($this->getConfig());
        $this->configuration = new SphinxSearchConfig();
    }

    /**
     * Gets the configuration file path.
     *
     * @return The configuration file path.
     */
    public function getConfig()
    {
        return JPATH_COMPONENT_ADMINISTRATOR . DS . "configuration.php";
    }

    public function getParam($name)
    {
        return $this->configuration->$name;
    }

    public function save($array)
    {
        require_once($this->getConfig());

        $config = new JRegistry('sphinxconfig');
        $config_array = array();

        $config_array["hostname"] = JArrayHelper::getValue($array, "hostname");
        $config_array["port"] = JArrayHelper::getValue($array, "port");
        $config_array["index"] = JArrayHelper::getValue($array, "index");
        $config->loadArray($config_array);

        // TODO: Add no direct access. (2012-07-25 Wed 05:41 PM (NOVT), Dmitri Perunov)
        JFile::write($this->getConfig(), $config->toString("PHP", array("class"=>"SphinxSearchConfig")));

        $this->configuration = new SphinxSearchConfig();
    }
}
