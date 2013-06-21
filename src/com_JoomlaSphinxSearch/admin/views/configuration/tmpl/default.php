<?php
/**
 * Part of Joomla Sphinx Search Component Package
 *
 * @package Joomla-Sphinx-Search-component
 * @copyright Copyright (C) 2012-2013 Dmitri Perunov <dmitri.perunov@gmail.com>
 * @license GNU General Public License, Version 3; see LICENSE
 */

//no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

$application = JFactory::getApplication('administrator');
$document = JFactory::getDocument();
$document->addScriptDeclaration(
    '
    var adminOptions = new Object({
        testURL : "' . JURI::base() . 'administrator/index.php?option=com_sphinxsearch&task=test&format=raw"
});
');
$document->addScript(JURI::base() . 'media/com_sphinxsearch/js/jsphinxsearch.js');

JToolBarHelper::title(JText::_('SphinxSearch Configuration'), 'config.png');
JToolBarHelper::save();
JToolBarHelper::cancel();
?>

<form autocomplete="off" name="adminForm" method="post" action="index.php">
    <div id="config-document">
        <div id="page-site" style="display: block;">
            <table class="noshow">
            <tbody>
            <?php if (false == $this->sphinxRunning):?>
            <tr>
                <td width="65%">
                    <dl id="system-message">
                    <dt class="error">Error</dt>
                    <dd class="error message fade">
                        <ul><li>Sphinx Search is not running on <?php echo $this->getModel()->getParam('hostname'); ?>.
                            See <a href="http://www.ivinco.com/software/joomla-sphinx-search-component-tutorial/#installation">installation instruction</a>.
                            </li>
                        </ul>
                    </dd>
                    </dl>
                </td>
            </tr>
            <?php endif; ?>
            <tr>
                <td width="65%">
                    <fieldset class="adminform">
                        <legend>Component Settings</legend>

                        <table cellspacing="1" class="admintable">
            <tbody>
            <tr>
                            <td class="key">
                                <span class="editlinktip hasTip">Host name</span>
                            </td>
                            <td>
                                <input type="text" value="<?php echo $this->getModel()->getParam('hostname'); ?>" size="50" id="host" name="hostname" class="text_area"/>
                            </td>
                        </tr>
            <tr>
                            <td class="key">
                                <span class="editlinktip hasTip">Port</span>
                            </td>
                            <td>
                                <input type="text" value="<?php echo $this->getModel()->getParam('port'); ?>" size="50" id="port" name="port" class="text_area"/>
                            </td>
            </tr>
            <tr>
                            <td class="key">
                                <span class="editlinktip hasTip">Index name</span>
                            </td>
                            <td>
                                <input type="text" value="<?php echo $this->getModel()->getParam('index'); ?>" size="50" id="path" name="index" class="text_area"/>
                            </td>
            </tr>
                        </tbody>
            </table>
                    </fieldset>
        </td>
            </tr>
            </tbody>
            </table>
    </div>
    </div>
<div class="clr"></div>
    <input type="hidden" value="com_sphinxsearch" name="option"/>
    <input type="hidden" value="" name="task"/>
    <input type="hidden" value="configuration" name="view"/>
</form>
