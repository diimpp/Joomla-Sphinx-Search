<?php
/**
 *
 *
 * @author		Ivinco LTD.
 * @package		Ivinco
 * @subpackage	SphinxSearch
 * @copyright	Copyright (C) 2011 Ivinco Ltd. All rights reserved.
 * @license     This file is part of the SphinxSearch component for Joomla!.

   The SphinxSearch component for Joomla! is free software: you can redistribute it
   and/or modify it under the terms of the GNU General Public License as
   published by the Free Software Foundation, either version 3 of the License,
   or (at your option) any later version.

   The SphinxSearch component for Joomla! is distributed in the hope that it will be
   useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

   You should have received a copy of the GNU General Public License
   along with the SphinxSearch component for Joomla!.  If not, see
   <http://www.gnu.org/licenses/>.

 * Contributors
 * Please feel free to add your name and email (optional) here if you have
 * contributed any source code changes.
 * Name							Email
 * Ivinco					<opensource@ivinco.com>
 *
 */

//no direct access
defined('_JEXEC') or die('Restricted access');
?>
<?php if ($this->get("Total") > 0) : ?>
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
    <?php if ($this->get("Total") == 0) : ?>
            <div class="sphinx-no-results"><?php echo JText::_("No results found."); ?></div>
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
