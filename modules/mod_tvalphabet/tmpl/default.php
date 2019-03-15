<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_banners
 *
 * @copyright   Copyright (C) 2005 - 2018 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;?>

<div class="ps_tvshows_alphabet<?php echo $moduleclass_sfx; ?>">
	<ul class="alphabet">
		<li><a href="#" class="letter">0-9</a></li>
      	<li><a href="#" class="letter">a</a></li>
      	<li><a href="#" class="letter">b</a></li>
      	<li><a href="#" class="letter">c</a></li>
      	<li><a href="#" class="letter">d</a></li>
      	<li><a href="#" class="letter">e</a></li>
      	<li><a href="#" class="letter">f</a></li>
      	<li><a href="#" class="letter">g</a></li>
      	<li><a href="#" class="letter">h</a></li>
      	<li><a href="#" class="letter">i</a></li>
      	<li><a href="#" class="letter">j</a></li>
      	<li><a href="#" class="letter">k</a></li>
      	<li><a href="#" class="letter">l</a></li>
      	<li><a href="#" class="letter">m</a></li>
      	<li><a href="#" class="letter">n</a></li>
      	<li><a href="#" class="letter">o</a></li>
      	<li><a href="#" class="letter">p</a></li>
      	<li><a href="#" class="letter">q</a></li>
      	<li><a href="#" class="letter">r</a></li>
      	<li><a href="#" class="letter">s</a></li>
      	<li><a href="#" class="letter">t</a></li>
      	<li><a href="#" class="letter">u</a></li>
      	<li><a href="#" class="letter">v</a></li>
      	<li><a href="#" class="letter">w</a></li>
      	<li><a href="#" class="letter">x</a></li>
      	<li><a href="#" class="letter">y</a></li>
      	<li><a href="#" class="letter">z</a></li>
	</ul>

      <div class="result hide">
            <div class="row">
                  <div class="col-lg-2 left">
						<div class="letter-info">
							<i></i>
							<span class="count">
								<?php echo JText::_('MOD_TV_ALPHABET_WE_FOUND');?> <b></b> <?php echo JText::_('MOD_TV_ALPHABET_TVSHOWS_FOR_YOU');?>
							</span>
						</div>
                  </div>

                  <div class="col-lg-10 right">
                        
                  </div>
            </div>
      </div>
</div>

<script>
      const request = (letter) => {
           return jQuery.getJSON('index.php?option=com_ajax&module=tvalphabet&method=get&format=json&<?php echo JSession::getFormToken() .'=1';?>',{letter: letter})
      }

      jQuery(document).ready(function(){
            const module = jQuery('[class*="ps_tvshows_alphabet"]');
            const result_block = jQuery('[class*="ps_tvshows_alphabet"] .result');
            
            module.on('click', '.letter', function(event) {
                  event.preventDefault();
                  
                  loading_play(module);

                  let letter = jQuery(this).text();
                  let films = request(letter);

                  result_block.find('.right').html('');
                  jQuery(this).parent().addClass('active').siblings().removeClass('active');

                  films
                  .done(function(r) {

                        result_block.removeClass('hide');
                        result_block.find('.left').find('i').text(letter);
                        result_block.find('.left').find('.count').find('b').text(r.films.length);
                        result_block.find('.right').html('<ul class="list"></ul>');

                        for (let i in r.films) {
                              result_block.find('.right').find('.list').append(`<li><a href="${r.films[i].link}">${r.films[i].title}</a><span> - ${r.films[i].count}</span></li>`)
                        }

                        loading_stop(module);
                  })
                  .fail(function() {
                      console.log('Ошибка Ajax запроса!');
                  });                 
            });
      });
</script>