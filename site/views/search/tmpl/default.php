<?php

//no direct access
defined('_JEXEC') or die('Restricted access');
?>

<?php echo $this->loadTemplate('form'); ?>
<?php if (!empty($this->total)) : ?>
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
<?php else :
echo JText::_('COM_SPHINXSEARCH_FORM_INPUT_NO_RESULTS');
endif;
?>

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
    echo $this->loadTemplate('default');
    break;
}
?>

<div class="sphinx-pagination pagination pagination-centered">
    <?php echo $this->pagination->getPagesLinks(); ?>
</div>
