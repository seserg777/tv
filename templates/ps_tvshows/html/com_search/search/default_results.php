<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>

<div class="search-results<?php echo $this->pageclass_sfx; ?>">
<?php foreach ($this->results as $k => $result) : ?>
	<?php if($k == 0 || $k%3==0){?>
		<div class="row-fluid">
	<?php } ?>

		<div class="span4 item">
			<div class="result-title">
				<?php if ($result->href) : ?>
					<a href="<?php echo JRoute::_($result->href); ?>"<?php if ($result->browsernav == 1) : ?> target="_blank"<?php endif; ?>>
						<?php echo $this->escape($result->title); ?>
					</a>
				<?php else : ?>
					<?php echo $this->escape($result->title); ?>
				<?php endif; ?>
			</div>	

			<div class="result-text">
				<?php echo $result->text; ?>
			</div>		
		</div>

	<?php if($k+1 == count($this->results) || ($k+1)%3==0){?>
		</div>
	<?php } ?>
<?php endforeach; ?>
</div>

<div class="pagination">
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>