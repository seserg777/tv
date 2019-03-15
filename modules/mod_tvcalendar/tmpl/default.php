<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_tags_similar
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$doc = JFactory::getDocument();
//$doc->addScript('/modules/mod_tvcalendar/assets/js/moment.min.js');
//$doc->addScript('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js');
$doc->addStylesheet('//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css');?>

<div class="ps_tvcalendar<?php echo $moduleclass_sfx; ?>">
	<div id='calendar'></div>
</div>

<script src="/modules/mod_tvcalendar/assets/js/moment.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
<script>
	const requestEvents = (start, end) => {
       	return jQuery.getJSON('index.php?option=com_ajax&module=tvcalendar&method=get&format=json', {
            start: start,
            end: end
        });
  	}

	jQuery(document).ready(function(){
		const calendar = jQuery('#calendar').fullCalendar({
	  		viewRender: function (view, element) {
	  			let events = requestEvents(view.start.format(), view.end.format());

	  			events
              	.done(function(r) {
                    jQuery('#calendar').fullCalendar( 'renderEvents', r.events );
              	})
              	.fail(function() {
                  	console.log('Ошибка Ajax запроса!');
              	})
              	.always(function() {
                  	console.log('Ajax запрос завершен');
              	}); 
        	},
			windowResize: function(view) {
				if (screen.width < 640){
					jQuery('#calendar').fullCalendar( 'changeView', 'basicDay' );
				} else {
					jQuery('#calendar').fullCalendar( 'changeView', 'month' );
				}
			}
		});
		
		if (screen.width < 640){
			jQuery('#calendar').fullCalendar( 'changeView', 'basicDay' );
		} else {
			jQuery('#calendar').fullCalendar( 'changeView', 'month' );
		}
	});
</script>