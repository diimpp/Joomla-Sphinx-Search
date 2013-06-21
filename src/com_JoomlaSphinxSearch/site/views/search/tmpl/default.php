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
?>

<?php if ($this->params->get('show_page_heading', 1)) : ?>
<h1>
    <?php if ($this->escape($this->params->get('page_heading'))) :?>
        <?php echo $this->escape($this->params->get('page_heading')); ?>
    <?php else : ?>
        <?php echo $this->escape($this->params->get('page_title')); ?>
    <?php endif; ?>
</h1>
<?php endif; ?>
<br />
<?php echo $this->loadTemplate('form'); ?>

<?php 
if (!empty($this->results)) : ?>
    <div class="sphinx-total-results">
        <?php echo $this->pagination->getResultsCounter(); ?>
    </div>
<form>
    <input type="hidden" name="searchword" value="<?php echo $this->query; ?>" />
    <input type="hidden" name="limitstart" value="<?php echo $this->pagination->limitstart; ?>" />
    <div class="pull-right">
        <?php echo $this->pagination->getLimitBox(); ?>
    </div>
</form>
<?php elseif (false === $this->results) : ?>
    <?php echo JText::_('COM_SPHINXSEARCH_FORM_INPUT_NO_INPUT'); ?>
<?php elseif (null === $this->results) : ?>
    <?php echo JText::_('COM_SPHINXSEARCH_FORM_INPUT_NO_RESULTS'); ?>
<?php endif; ?>

<?php 
switch ($this->subLayout) {
case 'custom':
    // Tricky include of another component sublayout as local one.
    require_once($this->subLayoutPath);
    break;
case 'default':
    echo $this->loadTemplate('default');
    break;
default:
    break;
}
?>

<div class="sphinx-pagination pagination pagination-centered">
    <?php echo $this->pagination->getPagesLinks(); ?>
</div>
