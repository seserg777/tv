<?php defined("_JEXEC") or die("Restricted access");

abstract class TvshowsHelperMovie {
	public static function customBtn($item, $link, $text, $attributes = array()){
		$text = TvshowsHelperMovie::replacer($item, $text);
		
		echo JHTML::_('link', $link, $text, $attributes);
	}
	
	public static function replacer($item, $text){
		if(isset($item->title) && !empty($item->title)){
			$text = str_replace('{movie_name}', $item->title, $text);
		}
		
		if(isset($item->implodetags) && !empty($item->implodetags)){
			$text = str_replace('{movie_genres}', $item->implodetags, $text);
		}
		
		if(isset($item->producers) && !empty($item->producers)){
			$text = str_replace('{movie_producers}', $item->producers, $text);
		}
		
		if(isset($item->production_companies) && !empty($item->production_companies)){
			$text = str_replace('{movie_production_companies}', $item->production_companies, $text);
		}
		
		if(isset($item->budget) && !empty($item->budget)){
			$text = str_replace('{movie_budget}', $item->budget, $text);
		}
		
		return $text;
	}
}