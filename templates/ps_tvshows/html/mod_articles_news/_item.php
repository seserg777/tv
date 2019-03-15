<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$images = $item->images;
if(isset($images) && !empty($images)){
	$images = json_decode($images);
}
//echo '<pre>';var_dump($item->created);?>
<div class="item row-fluid">
	<div class="span9">
		<p class="date"><?php echo JHtml::_('date',$item->created,'DATE_FORMAT_LC4');?></p>

		<?php $item_heading = $params->get('item_heading', 'h4');?>
		<?php if ($params->get('item_title')) : ?>
			<<?php echo $item_heading; ?> class="newsflash-title">
			<?php if ($item->link !== '' && $params->get('link_titles')) : ?>
				<a href="<?php echo $item->link; ?>">
					<?php echo $item->title; ?>
				</a>
			<?php else : ?>
				<?php echo $item->title; ?>
			<?php endif; ?>
			</<?php echo $item_heading; ?>>

		<?php endif; ?>

		<?php if (!$params->get('intro_only')) : ?>
			<?php echo $item->afterDisplayTitle; ?>
		<?php endif; ?>

		<?php echo $item->beforeDisplayContent; ?>

		<?php if ($params->get('show_introtext', '1')) : ?>
			<div class="text hyphenator"><?php echo $item->introtext; ?></div>
		<?php endif; ?>

		<?php echo $item->afterDisplayContent; ?>

		<?php if (isset($item->link) && $item->readmore != 0 && $params->get('readmore')) : ?>
			<?php echo '<a class="readmore" href="' . $item->link . '">' . $item->linkText . '</a>'; ?>
		<?php endif; ?>
	</div>

	<div class="span3">
		<?php if(isset($images) && !empty($images) && isset($images->image_intro) && !empty($images->image_intro)){?>
			<div class="image">
				<!--<img src="<?php echo $images->image_intro;?>" />-->
				<?php require_once JPATH_ROOT .'/templates/ps_ribka/html/mod_articles_news/SimpleImage.php';
			try {
				$image = new \claviska\SimpleImage();
				$img = $image
					->fromFile($images->image_intro) 
					->thumbnail(98, 98)
					->toString();
				echo '<img src="data:image/png;base64,'.base64_encode($img).'" alt="'.$item->title.'" />';
			} catch(Exception $err) {
				echo $err->getMessage();
			}?>
			</div>
		<?php } ?>
	</div>	
</div>