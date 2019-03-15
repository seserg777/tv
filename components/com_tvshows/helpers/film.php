<?php defined("_JEXEC") or die("Restricted access");

abstract class TvshowsHelperFilm {
	public static function customBtn($item, $link, $text, $attributes = array()){
		
		$text = str_replace('{seasons_enumeration}', $item->count_seasons, str_replace('{film_name}', $item->title, $text)); 		
		if(isset($item->implodetags) && !empty($item->implodetags)){
			$text = str_replace('{film_genres}', $item->implodetags, $text);
		}
		$text = str_replace('{film_creators}', $item->creators, $text);
		$text = str_replace('{film_channel}', $item->channel, $text);
		
		echo JHTML::_('link', $link, $text, $attributes);
	}
}