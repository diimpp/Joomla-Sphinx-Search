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
?>

<form action="<?php echo JRequest::getURI(); ?>" method="post" name="adminForm" class="sphinx-search-form">
    <div class="sphinx-query">
<?php
foreach ($this->form->getFieldset() as $form) {
    echo $form->input;
}
?>
        <button type="submit" class="btn btn-primary">
            <?php echo JText::_('COM_SPHINXSEARCH_FORM_INPUT_SUBMIT'); ?>
        </button>
    </div>

    <input type="hidden" name="option" value="<?php echo JRequest::getVar('option'); ?>" />
    <input type="hidden" name="task" value="search" />
    <?php echo JHtml::_('form.token'); ?>
</form>
