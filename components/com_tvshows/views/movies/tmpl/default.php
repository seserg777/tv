<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

// necessary libraries
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');

// sort ordering and direction
$listOrder = $this->state->get('list.ordering');
$listDirn = $this->state->get('list.direction');
$archived	= $this->state->get('filter.published') == 2 ? true : false;
$trashed	= $this->state->get('filter.published') == -2 ? true : false;
$user = JFactory::getUser();?>

<div class="ps-films">
	<div class="page-header">
		<h1><?php echo JText::_('COM_TVSHOWS_MOVIE_VIEW_MOVIES_TITLE'); ?></h1>
	</div>
	
	<div class="inner">
		<form action="<?php JRoute::_('index.php?option=com_mythings&view=mythings'); ?>" method="post" name="adminForm" id="adminForm">
			<?php //echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this));?>
			<ul>
				<?php $key = '';
				foreach ($this->items as $item) {
					$f_char = substr($item->title, 0, 1);

					if($key != $f_char || empty($key)){
						$key = $f_char;?>
						<li class="key" onclick="getItemsByletter(this);"><?php echo $f_char;?></li>
					<?php } ?>

					<!--<li>-->
						<!--<a href="<?php //echo TvshowsHelperRoute::getMovieRoute(null, $item->alias);?>">-->
							<?php //echo $this->escape($item->title); ?>
						<!--</a>-->
					<!--</li>-->

				<?php } ?>
			</ul>

			<input type="hidden" name="task" value=" " />
			<input type="hidden" name="boxchecked" value="0" />
			<!-- Sortierkriterien -->
			<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
			<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
			<?php echo JHtml::_('form.token'); ?>
		</form>
	</div>
</div>
<script>
	function getItemsByletter(el){
		var key = jQuery(el);
		var letter = key.text();
		
		jQuery.getJSON('index.php?option=com_tvshows&task=movies.getItemsByletter&<?php echo JSession::getFormToken() .'=1';?>',{letter: letter})
		.done(function(r){			
			if(r.success == true){
				if(r.data.length){
					for(var i in r.data){
						key.after('<li><a href="'+r.data[i]['link']+'">'+r.data[i]['title']+'</a></li>');
					}
				}
			} else {
				alert('Error request!');
			}
		});
	}
</script>