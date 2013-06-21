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
<?php if ($this->get('Total') > 0) : ?>
    <div class="sphinx-total-results">
        <?php echo $this->pagination->getResultsCounter(); ?>
    </div>
<?php endif; ?>
<form>
    <input type="hidden" name="searchword" value="<?php echo $this->query; ?>" />
    <input type="hidden" name="limitstart" value="<?php echo $this->pagination->limitstart; ?>" />
    <?php echo $this->pagination->getLimitBox(); ?>
</form>

<br /><br />
    <?php if ($this->get('Total') == 0) : ?>
            <div class="sphinx-no-results"><?php echo JText::_('No results found.'); ?></div>
        <?php else:?>
            <div class="sphinx-results">
            <?php foreach ($this->results as $result) : ?>
                    <div class="sphinx-result">
                            <div class="sphinx-result-title"><a href="<?php echo $this->unitcatalogMenuAlias . '/' . $result->alias; ?>"><?php echo $result->name; ?></a></div>
                    </div>
            <?php endforeach; ?>
            </div>

        <?php endif; ?>
<br /><br />
