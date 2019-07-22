<?php defined("_JEXEC") or die("Restricted access");

//use Joomla\Registry\Registry;

abstract class TvshowsHelperSeason {
	public static function handleEpisodes($text){		
		/*$title_array=array(
			'k2s.cc' => 'Keep2Share',
			'publish2.me' => 'Publish2',
			'turbobit.net'=>'turbobit.net'
		);*/
		$params = JComponentHelper::getParams('com_tvshows');
		$fileshare_domains = $params->get('fileshare_domains');
		$fileshare_domains = json_decode($fileshare_domains);
		
		$title_array = array();
		
		foreach($fileshare_domains->domain as $k => $v){
			$title_array[$v] = $fileshare_domains->name[$k];
		}
		
		$text = preg_split('/\r\n|[\r\n]/', $text);
		
		if(count($text)){
			foreach($text as $k => $v){
				if(empty($v)){
					unset($text[$k]);
				} else {
					$exp1 = explode('-', $v);
					if(count($exp1)){
						$text[$k] = array();
						$text[$k]['size'] = $exp1['1'];
						$text[$k]['url'] = $exp1['0'];
						
						$parse_url = parse_url($exp1['0']);
						$exp_parse_url = explode('/', $parse_url['path']);
						
						if(array_key_exists($parse_url['host'], $title_array)){
							$text[$k]['hosted'] = $title_array[$parse_url['host']];
						}
						
						if(count($exp_parse_url)){
							foreach($exp_parse_url as $key => $value){
								if(stristr($value, '.') != false){
									$exp_episode_data = explode('.', $value);
									if(count($exp_episode_data)){
										$text[$k]['film'] = $exp_episode_data['0'];
										
										$last_counter = 3;
										
										for($i = 1;$i < count($exp_episode_data);$i++){
											preg_match('/S(.*)E/', $exp_episode_data[$i], $matches);
											if(count($matches) > 1){
												//echo '<pre>';
												//var_dump($exp_episode_data[$i]);
												//var_dump($matches[1][0]);
												$last_counter = $i;
												if($matches[1][0] == '0'){
													$matches[1] = ltrim($matches[1], '0');
												}
												$text[$k]['season'] = $matches[1];
											}
										}
										
										for($i = 1;$i < count($exp_episode_data);$i++){
											preg_match("/E([^@E*]*)/", $exp_episode_data[$i], $matches);
											if(count($matches) > 1){
												if($matches[1][0] == '0'){
													$matches[1] = ltrim($matches[1], '0');
												}
												$text[$k]['episode'] = $matches[1];
											}
										}
										
										//echo '<pre>';
										//var_dump($v);
										//var_dump($exp_episode_data);
										//var_dump(count($exp_episode_data));
										//var_dump($last_counter);
										//var_dump(count($exp_episode_data) - $last_counter);
										//var_dump($exp_episode_data[$last_counter+1]);
										//var_dump($exp_episode_data[$last_counter+2]);
										//var_dump(count($exp_episode_data) - 3 > 1);
										//echo '----------------------------------------------------';
										//echo '</pre>';
										//if(count($exp_episode_data) > 3){
										if(count($exp_episode_data) - $last_counter > 2){
											$text[$k]['format'] = $exp_episode_data[$last_counter+2];
											$text[$k]['quality'] = $exp_episode_data[$last_counter+1];
										} else {
											$text[$k]['format'] = $exp_episode_data[$last_counter+1];
											$text[$k]['quality'] = null;
										}
									}
								}
							}
						}
					}
				}
			}
		} else {
			$text = false;
		}
		
		//echo '<pre>';var_dump($text);
		
		$links = array();
		
		foreach($text as $episode){
			if(!isset($links[$episode['season']])){
				$links[$episode['season']] = array();
			}
			
			if(!isset($links[$episode['season']][$episode['episode']])){
				$links[$episode['season']][$episode['episode']] = array();
			}
			
			$links[$episode['season']][$episode['episode']][] = $episode;
		}
		
		ksort($links);
		foreach($links as $k => $v){
			ksort($links[$k]);
		}
		
		//echo '<pre>';var_dump($links);exit;
		return $links;
	}
	
	public static function checkCaptcha($response){
		$params = JComponentHelper::getParams('com_tvshows');
		$recaptcha_secret = $params->get('recaptcha-secret');
		
		$verifyResponse = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret='.$recaptcha_secret.'&response='.$response);
		$responseData = json_decode($verifyResponse);
        if($responseData->success){
			return true;
		} else {
			return false;
		}
	}
	
	public static function checkCaptchaV3($token){
		$jinput = JFactory::getApplication()->input;
		$params = JComponentHelper::getParams('com_tvshows');
		$recaptcha_secret = $params->get('recaptcha-secret-v3');
		$ip = $jinput->server->get('REMOTE_ADDR');
		
		try {
			jimport('joomla.http.factory');
			$http = JHttpFactory::getHttp();
			if ($request = $http->get('https://www.google.com/recaptcha/api/siteverify?secret='.urlencode($recaptcha_secret).'&response='.urlencode($token).'&remoteip='.urlencode($ip))) {
				$json = json_decode($request->body);
			}
		} catch (Exception $e) {
			JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			$invalid[] = $componentId[0];
			return false;
		}
		
		if (empty($json->success) || !$json->success) {			
			if (!empty($json) && isset($json->{'error-codes'}) && is_array($json->{'error-codes'})) {
				foreach ($json->{'error-codes'} as $code) {
					JFactory::getApplication()->enqueueMessage('Recaptcha_'.$code, 'error');
				}
			}
		} elseif ($json->success) {
			return true;
		}
	}
	
	public static function customBtn($item, $link, $text, $attributes = array()){
		$text = str_replace('{season_number}', $item->season_number, str_replace('{film_name}', $item->film->title, $text)); 
		if(isset($item->implodetags) && !empty($item->implodetags)){
			$text = str_replace('{film_genres}', $item->implodetags, $text);
		}
		$text = str_replace('{film_creators}', $item->film->creators, $text);
		$text = str_replace('{film_channel}', $item->film->channel, $text);
		$text = str_replace('{film_url}', TvshowsHelperRoute::getFilmRoute(null, $item->film->alias), $text);
		if(isset($item->count_seasons) && !empty($item->count_seasons)){
			$text = str_replace('{seasons_enumeration}', $item->count_seasons, $text);
		}
		
		echo JHTML::_('link', $link, $text, $attributes);
	}
	
	public static function getCaptchaType($type = 2){
		switch ($type) {
			case 1:
				$type = 'recaptcha';
				break;
				
			case 2:
				$type = 'recaptcha3';
				break;
				
			case 3:
				$type = 'keycaptcha';
				break;
		}
		
		return $type;
	}
	
	public static function includeCaptcha($recaptcha_type, $params){
		$doc = JFactory::getDocument();
		
		if($recaptcha_type == 'recaptcha'){
			$doc->addScript('//www.google.com/recaptcha/api.js');
		}
		if($recaptcha_type == 'recaptcha3'){
			$recaptcha_public_v3 = $params->get('recaptcha-public-v3', null);
			$doc->addScript('//www.google.com/recaptcha/api.js?render='.$recaptcha_public_v3);
		}
		if($recaptcha_type == 'keycaptcha'){
			
		}
	}
	
	
}?>