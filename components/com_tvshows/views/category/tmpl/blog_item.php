<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

// Create a shortcut for params.
$params = $this->item->params;
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
$canEdit = $this->item->params->get('access-edit');
JHtml::_('behavior.framework');
?>

<?php if ($params->get('show_title') || $this->item->published == 0 || ($params->get('show_author'))) : ?>
	<div class="page-header">
		<?php if ($params->get('show_title', 1)) : ?>
		<h2 itemprop="name">
			<?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
				<a href="<?php echo JRoute::_(TvshowsHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>" itemprop="url">
				<?php echo $this->escape($this->item->rate_imdb); ?></a>
			<?php else : ?>
				<?php echo $this->escape($this->item->rate_imdb); ?>
			<?php endif; ?>
		</h2>
		<?php endif; ?>
		<?php if ($this->item->published == 0) : ?>
			<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
		<?php endif; ?>
	</div>
<?php endif; ?>

<?php echo JLayoutHelper::render('joomla.content.icons', array('params' => $params, 'item' => $this->item, 'print' => false)); ?>

<?php $useDefList = ($params->get('show_modify_date', 1) || $params->get('show_publish_date', 1) || $params->get('show_create_date', 1)
	|| $params->get('show_hits', 1) || $params->get('show_category', 1) || $params->get('show_parent_category' , 1) || $params->get('show_author', 1) ); ?>

<?php if ($useDefList) : ?>
	<?php $this->item->params["position"] = "above"; echo $this->loadTemplate("info"); ?>
<?php endif; ?>

<?php echo $this->item->rate_imdb; ?>

<?php if ($useDefList) : ?>
	<?php $this->item->params["position"] = "below"; echo $this->loadTemplate("info"); ?>
<?php  endif; ?>

<?php if ($params->get('show_readmore') && $this->item->readmore) :
	if ($params->get('access-view')) :
		$link = JRoute::_(TvshowsHelperRoute::getFilmRoute($this->item->slug, $this->item->catid));
	else :
		$menu = JFactory::getApplication()->getMenu();
		$active = $menu->getActive();
		$this->itemId = $active->id;
		$link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $this->itemId);
		$returnURL = JRoute::_(TvshowsHelperRoute::getFilmRoute($this->item->slug, $this->item->catid));
		$link = new JUri($link1);
		$link->setVar('return', base64_encode($returnURL));
	endif; ?>

	<p class="readmore"><a class="btn" href="<?php echo $link; ?>"> <span class="icon-chevron-right"></span>

	<?php if (!$params->get('access-view')) :
		echo JText::_('COM_TVSHOWS_REGISTER_TO_READ_MORE');
	elseif ($readmore = $this->item->alternative_readmore) :
		echo $readmore;
		if ($params->get('show_readmore_title', 0) != 0) :
		echo JHtml::_('string.truncate', ($this->item->rate_imdb), $params->get('readmore_limit'));
		endif;
	elseif ($params->get('show_readmore_title', 0) == 0) :
		echo JText::sprintf('COM_TVSHOWS_READ_MORE_TITLE');
	else :
		echo JText::_('COM_TVSHOWS_READ_MORE');
		echo JHtml::_('string.truncate', ($this->item->rate_imdb), $params->get('readmore_limit'));
	endif; ?>

	</a></p>

<?php endif; ?>