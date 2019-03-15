<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

// necessary libraries
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('formbehavior.chosen', 'select');
//echo '<pre>';var_dump($this->item);
?>

<script type="text/javascript">
	Joomla.submitbutton = function(task){
		if (task == 'season.cancel' || document.formvalidator.isValid(document.id('season-form'))){
			Joomla.submitform(task, document.getElementById('season-form'));
		}
	}
</script>

<form action="<?php echo JRoute::_('index.php?option=com_tvshows&id=' . (int)$this->item->id); ?>" method="post" name="adminForm" id="season-form" class="form-validate">
	
	<div class="form-inline form-inline-header">
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('title'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('title'); ?></div>
		</div>
		<div class="control-group">
			<div class="control-label"><?php echo $this->form->getLabel('alias'); ?></div>
			<div class="controls"><?php echo $this->form->getInput('alias'); ?></div>
		</div>
	</div>

	<div class="form-horizontal">
	<?php echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'details')); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'details', 'Season', $this->item->id, true); ?>
		<div class="row-fluid">
			<div class="span9">
				<div class="row-fluid form-horizontal-desktop">			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('film'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('film'); ?></div>
			</div>	
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('season_number'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('season_number'); ?></div>
			</div>				
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('custom_btn1_anchor'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('custom_btn1_anchor'); ?></div>
			</div>	
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('custom_btn1_url'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('custom_btn1_url'); ?></div>
			</div>				
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('bage'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('bage'); ?></div>
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('description'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('description'); ?></div>
			</div>	
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('links'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('links'); ?></div>
			</div>			
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('next_episode_time'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('next_episode_time'); ?></div>
			</div>
			<div class="control-group">
				<div class="control-label"><?php echo $this->form->getLabel('main_image'); ?></div>
				<div class="controls"><?php echo $this->form->getInput('main_image'); ?></div>
			</div>
				</div>
			</div>
			<div class="span3">
				<?php echo JLayoutHelper::render('joomla.edit.global', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>

		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'publishing', JText::_('JGLOBAL_FIELDSET_PUBLISHING', true)); ?>
		<div class="row-fluid form-horizontal-desktop">
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.publishingdata', $this); ?>
			</div>
			<div class="span6">
				<?php echo JLayoutHelper::render('joomla.edit.metadata', $this); ?>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
		<?php echo JLayoutHelper::render('joomla.edit.params', $this); ?>
				
		<?php echo JHtml::_('bootstrap.addTab', 'myTab', 'acl', 'ACL Configuration', true); ?>		
		<div class="row-fluid">
			<div class="span12">
				<fieldset class="panelform">
					<legend><?php echo $this->item->title ?></legend>
					<?php echo JHtml::_('sliders.start', 'permissions-sliders-'.$this->item->id, array('useCookie'=>1)); ?>
					<?php echo JHtml::_('sliders.panel', JText::_('ACL Configuration'), 'access-rules'); ?>
					<?php echo $this->form->getInput('rules'); ?>
					<?php echo JHtml::_('sliders.end'); ?>
				</fieldset>
			</div>
		</div>
		<?php echo JHtml::_('bootstrap.endTab'); ?>
		
	<?php echo JHtml::_('bootstrap.endTabSet'); ?>
	</div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>
</form>