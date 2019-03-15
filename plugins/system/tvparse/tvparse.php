<?php defined('_JEXEC') or die;
use Joomla\CMS\Table\Table;
$language = JFactory::getLanguage();
$language->load('com_tvshows');
jimport('joomla.http.factory');

class PlgSystemTvparse extends JPlugin {
	
	protected $db = null;
	protected $year = null;
	protected $vote = null;
	protected $lang = null;
	protected $lang_rec = null;
	protected $film_table = null;
	protected $season_table = null;
	protected $movie_table = null;
	protected $apikey1 = 'c5e577a7dce8e6547a62a418ed978db5';
	
	public function getMovieById($id) {
		$this->movie_table->reset();
		if(!$this->movie_table->load($id)){
			return false;
		} else {
			return $this->movie_table;
		} 
	}
	
	public function getMovie($movie_id) {
		try {
			$http = JHttpFactory::getHttp();
			$url = 'https://api.themoviedb.org/3/movie/'.$movie_id.'?api_key='.$this->apikey1.'&language='.$this->lang_rec;
			$headers = array(
				"Accept:application/json"
			);
			if($request = $http->get($url, $headers)){
				$json = json_decode($request->body);
				return $json;
			} else {
				return 'Get movie request to API has no result';
			}
		} catch (Exception $e) {
			//JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			//return false;
			return 'Get movie request to API '.$e->getMessage();
		}
	}
	
	public function getMovieCredits($movie_id) {
		try {
			$http = JHttpFactory::getHttp();
			$url = 'https://api.themoviedb.org/3/movie/'.$movie_id.'/credits?api_key='.$this->apikey1.'&language='.$this->lang_rec;
			$headers = array(
				"Accept:application/json"
			);
			if($request = $http->get($url, $headers)){
				$json = json_decode($request->body);
				//var_dump($json);exit;
				return $json;
			} else {
				return 'Get movie credits request to API has no result';
			}
		} catch (Exception $e) {
			//JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			//return false;
			return 'Get movie credits request to API '.$e->getMessage();
		}
	}
	
	public function saveMovieProducers($movie_id){
		$credits = $this->getMovieCredits((int)$movie_id);

		$producers = array();
		foreach($credits->crew as $c){
			if($c->job == 'Producer'){
				$producers[] = $c->name;
			}
		}

		$this->movie_table->reset();
		$this->movie_table->load((int)$movie_id);
		$this->movie_table->producers = implode(', ', $producers);
		if(!$this->movie_table->store()){
			return 'Err1: '.$this->movie_table->getErrors();
		} else {
			return true;
		}
	}
	
	public function saveMovieImg($movie_id, $title, $type, $images, $quality){
		foreach($images as $img){
			$imgs[] = $this->uploadImage('https://image.tmdb.org/t/p/'.$quality.$img, $title);
		}
			
		if(count($imgs)){		
			$this->movie_table->reset();
			$this->movie_table->load((int)$movie_id);
			
			if($type == 'main_image'){
				$this->movie_table->main_image = $imgs[0];
			}
			
			if($type == 'images'){
				$this->movie_table->images = json_encode($imgs);
			}
			
			if($this->movie_table->store()){
				return true;
			} else {
				return 'Err3: movie images save error';
			}
		} else {
			return 'Err3: movie images upload error';
		}
	}
	
	public function parseMovieVideos($movie_id){
		try {
			$http = JHttpFactory::getHttp();
			$url = 'https://api.themoviedb.org/3/movie/'.$movie_id.'/videos?api_key='.$this->apikey1.'&language='.$this->lang_rec;
			$headers = array(
				"Accept:application/json"
			);
			if($request = $http->get($url, $headers)){
				$json = json_decode($request->body);
				//var_dump($json);exit;
				return $json;
			} else {
				return 'Get movie videos request to API has no result';
			}
		} catch (Exception $e) {
			//JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			//return false;
			return 'Get movie videos request to API '.$e->getMessage();
		}
	}
	
	public function saveMovieVideos($movie_id){
		$movieVideos = $this->parseMovieVideos((int)$movie_id);
									
		$videos = array();
		if(count($movieVideos->results)){
			foreach($movieVideos->results as $video){
				$videos[] = array('site' => $video->site, 'key' => $video->key);
			}
		} else {
			return true;
		}
		
		$this->movie_table->reset();
		$this->movie_table->load((int)$movie_id);
		$this->movie_table->videos = json_encode($videos);
			
		if($this->movie_table->store()){
			return true;
		} else {
			return 'Err3: movie videos save error';
		}
	}
	
	public function saveMovieInfo($movie_id, $poster_quality = 'w300', $screeencaps_quality = 'w300', $lang = 'en-US') {
		
		if(isset($lang) && !empty($lang)){
			$this->lang_rec = $lang;
		}
	
		$movie_exist = $this->getMovieById((int)$movie_id);
		
		if(!$movie_exist){	
			$result = $this->getMovie($movie_id);
			
			if(isset($result->status_code) && !empty($result->status_code)){
				return 'saveMovieInfo API Err#'.$result->status_code.' '.$result->status_message;
			}
			
			//$result->status_code = 34;
			$row = new stdClass();
			$row->id = (int)$movie_id;
			$row->title = $result->title;
			$row->alias = JFilterOutput::stringURLSafe($result->title);
			
			$component_params = JComponentHelper::getParams('com_tvshows');
			$movie_alias_words = $component_params->get('movie_alias_words');
			if(isset($movie_alias_words) && !empty($movie_alias_words)){
				$alias_words = explode(PHP_EOL, $movie_alias_words);
				if(count($alias_words)){
					$rand_keys = array_rand($alias_words, 1);
					$row->alias = $row->alias.'-'.JFilterOutput::stringURLSafe($alias_words[$rand_keys]);
				}
			}
			
			$row->published = 0;
			$insert = $this->db->insertObject('#__tvshows_movies', $row);
			if(!$insert){
				return 'Error insert movie info #1';
			} else {	
			
				$result->genres_ids = array();
				foreach($result->genres as $k => $v){
					if(!$this->getGenreById($v->id)){
						unset($result->genres[$k]);
					} else {
						$result->genres_ids[] = (string)$v->id;
					}
				}
				
				$this->movie_table->reset();
				$this->movie_table->load((int)$movie_id);
				
				$this->movie_table->catid = 2;
				$this->movie_table->imdbid = $result->imdb_id;
				$this->movie_table->rate_imdb = $result->vote_average;
				$this->movie_table->votes = $result->vote_count;
				$this->movie_table->producers = '';
				$this->movie_table->release_date = $result->release_date;
				$this->movie_table->language = $result->original_language;
				
				$production_companies = array();
				foreach($result->production_companies as $company){
					$production_companies[] = $company->name;
				}
				
				$this->movie_table->production_companies = implode(', ', $production_companies);
				$this->movie_table->main_image = '';
				$this->movie_table->description = $result->overview;
				$this->movie_table->images = '';
				$this->movie_table->modified = JFactory::getDate()->toSql();
				$this->movie_table->budget = $result->budget;
				
				$helper = new JHelperTags;
				$this->movie_table->tagsHelper = $helper;
				$this->movie_table->tagsHelper->typeAlias = 'com_tvshows.movie';
				$this->movie_table->tagsHelper->tags = $result->genres_ids;				
				
				if(!$this->movie_table->store()){
					return 'Err1: '.$this->movie_table->getErrors();
				} else {		
					$saveMovieProducers = $this->saveMovieProducers((int)$movie_id);
					if(!$saveMovieProducers){
						return $saveMovieProducers;
					}
					
					if(isset($result->poster_path) && !empty($result->poster_path)){
						$saveImg = $this->saveMovieImg((int)$movie_id, $result->title, 'main_image', array($result->poster_path), $poster_quality);
						if(!$saveImg){
							return $saveImg;
						}
					}
					
					if(isset($result->backdrop_path) && !empty($result->backdrop_path)){
						$saveImg = $this->saveMovieImg((int)$movie_id, $result->title, 'images', array($result->backdrop_path), $screeencaps_quality);
						if(!$saveImg){
							return $saveImg;
						}
					}
					
					$saveMovieVideos = $this->savemovieVideos((int)$movie_id);
					if(!$saveMovieVideos){
						return $saveMovieVideos;
					}
					
					return $this->movie_table;
				}
			}
		} else {
			$movie_exist->exist = 'Movie exist';
			return $movie_exist;
		}
	}
	
	public function getAllTvList(){
		$query = $this->db->getQuery(true);	
		$query
			->select($this->db->quoteName(array('id','title')))
			->from($this->db->quoteName('#__tvshows_film'));
		$this->db->setQuery($query);
		return $this->db->loadObjectList();
	}
	
	public static function arrayToCsvDownload($head, $data, $filename = "export.csv", $delimiter=";"){
		header('Content-Type: application/csv; charset=utf-8');
		header('Content-Disposition: attachment; filename="'.$filename.'";');

		ob_start();
		$f = fopen('php://output', 'w');
		fputcsv($f, $head, $delimiter);
		foreach ($data as $line) {
			fputcsv($f, $line, $delimiter);
		}
		//fclose($file);
		return ob_get_clean();
	}
	
	function getTvInfo($tv_id, $lang){
		if(!isset($this->lang_rec) || !empty($this->lang_rec)){
			$this->lang_rec = $lang;
		}
		
		$urlpage='https://api.themoviedb.org/3/tv/'.$tv_id.'?api_key='.$this->apikey1.'&language='.$this->lang_rec;
		if ($curlpage = curl_init()) {
			curl_setopt($curlpage, CURLOPT_URL, $urlpage);
			curl_setopt($curlpage, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curlpage, CURLOPT_ENCODING, "");
			curl_setopt($curlpage, CURLOPT_MAXREDIRS, 10);
			curl_setopt($curlpage, CURLOPT_TIMEOUT, 30);
			curl_setopt($curlpage, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
			curl_setopt($curlpage, CURLOPT_CUSTOMREQUEST, "GET");
			curl_setopt($curlpage, CURLOPT_HTTPHEADER, array("Accept:application/json"));
			$out = curl_exec($curlpage);
			curl_close($curlpage);
			//var_dump($out, $urlpage, file_get_contents($urlpage));
			$result = json_decode($out, true);
		}
		
		return $result;
	}
	
	function getFilmById($id){
		$this->film_table->reset();
		return $this->film_table->load($id);
	}
	
	function getSeasonById($id){
		$this->season_table->reset();
		return $this->season_table->load($id);
	}
	
	function uploadImage($path, $title = null) {
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		JLoader::register('MediaHelper', JPATH_ADMINISTRATOR . '/components/com_media/helpers/media.php');
		$mediaHelper = new JHelperMedia;
	
		if(MediaHelper::isImage($path)) {			
			if($title){
				$folderName = JFilterOutput::stringURLSafe($title);
				$folder = JPATH_SITE.'/images/tvshows/'.$folderName;
			
				if (!JFolder::exists(JPATH_SITE.'/images/tvshows')){JFolder::create(JPath::clean(JPATH_SITE.'/images/tvshows'));}
			
				if (!JFolder::exists($folder)){JFolder::create(JPath::clean($folder));}
				
				$ext =  JFile::getExt($path);
				$localFileName = uniqid().'.'.$ext;
				$localFile = JPath::clean($folder.'/'.$localFileName);
				
				$read = JFile::read($path);
				$write = JFile::write($localFile, $read);
				
				if($write){return '/images/tvshows/'.$folderName.'/'.$localFileName;} 
				else {return false;}
			}
		} else {
			//var_dump($path);
			die('err2');
			return false;
		}
	}
	
	function parseImages($tv_id) {		
		$urlpage='https://api.themoviedb.org/3/tv/'.$tv_id.'/images?api_key='.$this->apikey1.'&language='.$this->lang_rec;
		if ($curlpage = curl_init()) {
			curl_setopt($curlpage, CURLOPT_URL, $urlpage);
			curl_setopt($curlpage, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curlpage, CURLOPT_HTTPHEADER, array("Accept:application/json"));
			$out = curl_exec($curlpage);
			curl_close($curlpage);
			$result = json_decode($out, true);
		}
		return $result['backdrops'];
	}
	
	function getSeasonImages($tv_id, $season_number, $episode_number){	
		$urlpage='https://api.themoviedb.org/3/tv/'.$tv_id.'/season/'.$season_number.'/episode/'.$episode_number.'?api_key='.$this->apikey1.'&language='.$this->lang_rec;
		if ($curlpage = curl_init()) {
			curl_setopt($curlpage, CURLOPT_URL, $urlpage);
			curl_setopt($curlpage, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curlpage, CURLOPT_HTTPHEADER, array("Accept:application/json"));
			$out = curl_exec($curlpage);
			curl_close($curlpage);
			$result = json_decode($out, true);
		}
		
		if(isset($result['still_path']) && !empty($result['still_path'])){
			return $result['still_path'];
		} else {
			return false;
		}
	}
	
	function getSeasonVideos($tv_id, $season_number){	
		$urlpage='https://api.themoviedb.org/3/tv/'.$tv_id.'/season/'.$season_number.'/videos?api_key='.$this->apikey1.'&language='.$this->lang_rec;
		if ($curlpage = curl_init()) {
			curl_setopt($curlpage, CURLOPT_URL, $urlpage);
			curl_setopt($curlpage, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curlpage, CURLOPT_HTTPHEADER, array("Accept:application/json"));
			$out = curl_exec($curlpage);
			curl_close($curlpage);
			$result = json_decode($out, true);
		}
		return $result['results'];
	}
	
	function getGenreById($id){
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tags/tables');
		$table = JTable::getInstance('Tag', 'TagsTable');
		if(!$table->load($id)){return false;} 
		return $table->title;
	}
	
	function getGenreByName($title){
		$query = $this->db->getQuery(true);
		$query->select($his->db->quoteName('id'))->from($this->db->quoteName('#__tags'))->where($this->db->quoteName('title') . ' = '. $this->db->quote($title));
		$this->db->setQuery($query);
		return $this->db->loadResult();
	}
	
	function createGenre($id, $title){
		$row = new stdClass();
		$row->id = (int)$id;
		$row->title=$title;
		$row->parent_id=1;
		$row->level=1;
		$row->published=1;
		$row->lft=1;
		$row->rgt=1;
		$row->access=1;
		$row->language='*';
		$row->alias = JFilterOutput::stringURLSafe($title);
		$row->path = JFilterOutput::stringURLSafe($title);
		return $this->db->insertObject('#__tags', $row);
	}
	
	function getGenres($data){			
		$urlpage='https://api.themoviedb.org/3/genre/movie/list?api_key='.$this->apikey1;
		
		if ($curlpage = curl_init()) {
			curl_setopt($curlpage, CURLOPT_URL, $urlpage);
			curl_setopt($curlpage, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curlpage, CURLOPT_HTTPHEADER, array("Accept:application/json"));
			$out = curl_exec($curlpage);
			curl_close($curlpage);
			$result = json_decode($out, true);
		}
		
		if($data->create && isset($result['genres']) && count($result['genres'])){
			$data = $result;
			$create = array();
			
			foreach($result['genres'] as $k => $v){
				$tagExist = $this->getGenreById($v['id']);
				if(!$tagExist){$create[$v['id']] = $this->createGenre($v['id'], $v['name']);}
			}
			
			if (in_array(false, $create)) {
				$result = false;
			} else {
				$result = array();
				$result['success'] = true;
				$result['create_tags'] = count($create);
				$result['parse_tags'] = count($data['genres']);
			}
			
			return $result;
		} else {		
			return $result;
		}
	}
	
	function parseTvPage($page){		
		$urlpage='https://api.themoviedb.org/3/discover/tv?with_original_language='.$this->lang.'&vote_average.gte='.$this->vote.'&timezone=America%2FNew_York&first_air_date_year='.$this->year.'&sort_by=popularity.desc&language='.$this->lang_rec.'&api_key='.$this->apikey1.'&page='.$page;
		if ($curlpage = curl_init()) {
			curl_setopt($curlpage, CURLOPT_URL, $urlpage);
			curl_setopt($curlpage, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curlpage, CURLOPT_HTTPHEADER, array("Accept:application/json"));
			$out = curl_exec($curlpage);
			curl_close($curlpage);
			$result = json_decode($out, true);
		}
		
		return $result['results'];
	}
	
	function saveTvNextEpisode($tv_id){		
		$urlpage='https://api.themoviedb.org/3/tv/'.$tv_id.'?api_key='.$this->apikey1.'&language='.$this->lang_rec;
		if ($curlpage = curl_init()) {
			curl_setopt($curlpage, CURLOPT_URL, $urlpage);
			curl_setopt($curlpage, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curlpage, CURLOPT_HTTPHEADER, array("Accept:application/json"));
			$out = curl_exec($curlpage);
			curl_close($curlpage);
			$result = json_decode($out, true);
		}
		
		//var_dump($result);
		
		if($this->getFilmById((int)$tv_id)){	
			if(isset($result['next_episode_to_air']) && !empty($result['next_episode_to_air'])){
				$object = new stdClass();
				$object->id = (int)$tv_id;
				$object->next_episode_time = $result['next_episode_to_air']['air_date'];
				$object->next_episode_name = $result['next_episode_to_air']['name'];
				$object->next_episode_season_number = $result['next_episode_to_air']['season_number'];
				return $this->db->updateObject('#__tvshows_film', $object, 'id');
			} else {
				return 'Next episode info not found';
			}
		} else {
			return 'Film not exist';
		}
	}
	
	function saveTvInfo($tv_id, $lang){
		
		if(isset($lang) && !empty($lang)){
			$this->lang_rec = $lang;
		}
		
		$urlpage='https://api.themoviedb.org/3/tv/'.$tv_id.'?api_key='.$this->apikey1.'&language='.$this->lang_rec;
		if ($curlpage = curl_init()) {
			curl_setopt($curlpage, CURLOPT_URL, $urlpage);
			curl_setopt($curlpage, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curlpage, CURLOPT_HTTPHEADER, array("Accept:application/json"));
			$out = curl_exec($curlpage);
			curl_close($curlpage);
			$result = json_decode($out, true);
		}
		
		if(!$this->getFilmById((int)$tv_id)){	
			$row = new stdClass();
			$row->id = (int)$tv_id;
			$row->title = $result['name'];
			$row->alias = JFilterOutput::stringURLSafe($result['name']);
			
			$component_params = JComponentHelper::getParams('com_tvshows');
			$film_alias_words = $component_params->get('film_alias_words');
			if(isset($film_alias_words) && !empty($film_alias_words)){
				$alias_words = explode(PHP_EOL, $film_alias_words);
				if(count($alias_words)){
					$rand_keys = array_rand($alias_words, 1);
					$row->alias = $row->alias.'-'.JFilterOutput::stringURLSafe($alias_words[$rand_keys]);
				}
			}
			
			$row->published = 0;
			$insert = $this->db->insertObject('#__tvshows_film', $row);
			if(!$insert){
				return 'Error insert film info #1';
			} else {	
			
				$result['genres_ids'] = array();
				foreach($result['genres'] as $k => $v){
					if(!$this->getGenreById($v['id'])){
						unset($result['genres'][$k]);
					} else {
						$result['genres_ids'][] = (string)$v['id'];
					}
				}
				
				$this->film_table->reset();
				$this->film_table->load((int)$tv_id);
				
				$this->film_table->catid = 2;
				$this->film_table->rate_imdb = $result['vote_average'];
				$this->film_table->votes = $result['vote_count'];
				$this->film_table->awards = '';
				$this->film_table->creators = '';
				$this->film_table->directors = '';
				$this->film_table->release_date = $result['first_air_date'];
				$this->film_table->language = $result['original_language'];
				$this->film_table->cast = '';
				$this->film_table->channel = '';
				$this->film_table->main_image = '';
				$this->film_table->description = $result['overview'];
				$this->film_table->images = '';
				$this->film_table->modified = JFactory::getDate()->toSql();
				
				$helper = new JHelperTags;
				$this->film_table->tagsHelper = $helper;
				$this->film_table->tagsHelper->typeAlias = 'com_tvshows.film';
				$this->film_table->tagsHelper->tags = $result['genres_ids'];				
				
				//var_dump($result['genres_ids']);
				
				if(!$this->film_table->store()){
					var_dump('Err1: '.$this->film_table->getErrors());
				} else {			
					return true;
				}
			}
		} else {
			return 'Film exist';
		}
	}
	
	function saveTvExternal($tv_id){		
		$urlid='https://api.themoviedb.org/3/tv/'.$tv_id.'/external_ids?api_key='.$this->apikey1.'&language='.$this->lang_rec;     
		if ($curid = curl_init()) {
			curl_setopt($curid, CURLOPT_URL, $urlid);
			curl_setopt($curid, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curid, CURLOPT_HTTPHEADER, array("Accept:application/json"));
			$outid = curl_exec($curid);
			curl_close($curid);
			$outid = json_decode($outid, true);
		}
		
		if(count($outid)){		
			$this->film_table->reset();
			$this->film_table->load((int)$tv_id);
			$this->film_table->imdbid = $outid['imdb_id'];
			$this->film_table->tvdbid = $outid['tvdb_id'];
			
			$urlpage='https://api.themoviedb.org/3/tv/'.$tv_id.'?api_key='.$this->apikey1.'&language='.$this->lang_rec;
			if ($curlpage = curl_init()) {
				curl_setopt($curlpage, CURLOPT_URL, $urlpage);
				curl_setopt($curlpage, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curlpage, CURLOPT_HTTPHEADER, array("Accept:application/json"));
				$out = curl_exec($curlpage);
				curl_close($curlpage);
				$film = json_decode($out, true);
			}
			
			$film['genres_ids'] = array();
			foreach($film['genres'] as $k => $v){
				if(!$this->getGenreById($v['id'])){
					unset($film['genres'][$k]);
				} else {
					$film['genres_ids'][] = (string)$v['id'];
				}
			}
			
			$helper = new JHelperTags;
			$this->film_table->tagsHelper = $helper;
			$this->film_table->tagsHelper->typeAlias = 'com_tvshows.film';
			$this->film_table->tagsHelper->tags = $film['genres_ids'];			
			
			if(!$this->film_table->store()){
				var_dump('Err2: external save error');
			} else {
				return true;
			}
		}
		
		return false;
	}
	
	function saveTvImages($tv_id, $tv_name, $film_screeencaps_quality, $screencaps_count){		
		$urlpage='https://api.themoviedb.org/3/tv/'.$tv_id.'/images?api_key='.$this->apikey1.'&language='.$this->lang_rec;
		if ($curlpage = curl_init()) {
			curl_setopt($curlpage, CURLOPT_URL, $urlpage);
			curl_setopt($curlpage, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curlpage, CURLOPT_HTTPHEADER, array("Accept:application/json"));
			$out = curl_exec($curlpage);
			curl_close($curlpage);
			$result = json_decode($out, true);
		}
		
		if(isset($result['backdrops']) && count($result['backdrops'])){
			$images = array();
			
			foreach($result['backdrops'] as $img){
				//$images[] = $this->uploadImage('https://image.tmdb.org/t/p/original'.$img['file_path'], $tv_name);
				if(count($images) < $screencaps_count){
					$images[] = $this->uploadImage('https://image.tmdb.org/t/p/'.$film_screeencaps_quality.$img['file_path'], $tv_name);
				}
			}
			
			if(count($images)){		
				$this->film_table->reset();
				$this->film_table->load((int)$tv_id);
				$this->film_table->images = json_encode($images);
				if($this->film_table->store()){
					return true;
				} else {
					var_dump('Err3: tv images save error');
				}
			}
		}
		
		return false;
	}
	
	function saveTvMoreInfo($tv_id){		
		$urlserial='https://api.themoviedb.org/3/tv/'.$tv_id.'?api_key='.$this->apikey1.'&language='.$this->lang_rec;
		if ($curser = curl_init()) {
			curl_setopt($curser, CURLOPT_URL, $urlserial);
			curl_setopt($curser, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curser, CURLOPT_HTTPHEADER, array(
				"Accept:application/json"
			));
			$outserial = curl_exec($curser);
			curl_close($curser);
			$result = json_decode($outserial, true);
		}		
		
		$creators = array();
		if(isset($result['created_by']) && count($result['created_by'])){
			foreach($result['created_by'] as $creator){
				$creators[] = $creator['name'];
			}
		}
					
		$channel = array();
		if(isset($result['networks']) && count($result['networks'])){
			foreach($result['networks'] as $creator){
				$channel[] = $creator['name'];
			}
		}
					
		if(count($creators) || count($channel)){	
			$this->film_table->reset();
			$this->film_table->load((int)$tv_id);
			$this->film_table->creators = implode(',', $creators);
			$this->film_table->channel = implode(',', $channel);
			
			$result['genres_ids'] = array();
			foreach($result['genres'] as $k => $v){
				if(!$this->getGenreById($v['id'])){
					unset($result['genres'][$k]);
				} else {
					$result['genres_ids'][] = (string)$v['id'];
				}
			}
			
			$helper = new JHelperTags;
			$this->film_table->tagsHelper = $helper;
			$this->film_table->tagsHelper->typeAlias = 'com_tvshows.film';
			$this->film_table->tagsHelper->tags = $result['genres_ids'];	
			
			if($this->film_table->store()){
				return true;
			} else {
				var_dump('Err3: tv more info save error');
			}
		}
		
		return false;
	}
	
	function getFimsSeasonsCount($tv_id){
		$query = $this->db->getQuery(true);
		$query
			->select('COUNT(*)')
			->from($this->db->quoteName('#__tvshows_season'))
			->where($this->db->quoteName('film') . ' = '. $this->db->quote($tv_id));
		$this->db->setQuery($query);
		return $this->db->loadResult();
	}
	
	function saveTvSeasons($tv_id, $screencaps_count, $season_poster_quality, $season_screeencaps_quality){		
		$urlserial='https://api.themoviedb.org/3/tv/'.$tv_id.'?api_key='.$this->apikey1.'&language='.$this->lang_rec;
		if ($curser = curl_init()) {
			curl_setopt($curser, CURLOPT_URL, $urlserial);
			curl_setopt($curser, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curser, CURLOPT_HTTPHEADER, array(
				"Accept:application/json"
			));
			$outserial = curl_exec($curser);
			curl_close($curser);
			$result = json_decode($outserial, true);
		}		
		
		
		if(isset($result['seasons']) && count($result['seasons'])){
			if($this->getFimsSeasonsCount($tv_id) == count($result['seasons'])){
				return 'No new seasons';
			} else {
				$saved = array();
				$notsaved = array();
				foreach($result['seasons'] as $season){
					//var_dump($season);
					if(!$this->getSeasonById((int)$season['id'])){
						if($season['poster_path']){
							$seasonMainImage = $this->uploadImage('https://image.tmdb.org/t/p/'.$season_poster_quality.$season['poster_path'], $result['name']);
						}
						
						$row = new stdClass();
						$row->id = (int)$season['id'];
						$row->title = str_replace('Season', JText::_('COM_TVSHOWS_PARSE_SEASON_LABEL'), $season['name']);
						$row->alias = $tv_id.'-'.JFilterOutput::stringURLSafe($row->title);
						
						$component_params = JComponentHelper::getParams('com_tvshows');
						$season_alias_words = $component_params->get('season_alias_words');
						if(isset($season_alias_words) && !empty($season_alias_words)){
							$alias_words = explode(PHP_EOL, $season_alias_words);
							if(count($alias_words)){
								$rand_keys = array_rand($alias_words, 1);
								$row->alias = $row->alias.'-'.JFilterOutput::stringURLSafe($alias_words[$rand_keys]);
							}
						}
						
						$row->published = 0;
						$row->description = $season['overview'];
						$row->film = (int)$tv_id;
						$row->season_number = $season['season_number'];
						$row->modified = JFactory::getDate()->toSql();
						if(isset($seasonMainImage) && !empty($seasonMainImage)){
							$row->main_image = $seasonMainImage;
						}
						$row->episode_count = $season['episode_count'];
									
						if(isset($season['episode_count']) && !empty($season['episode_count'])){
							$seasonImagesArray = array();
							$seasonImages = array();
							for($i = 1;$i <= $season['episode_count'];$i++){
								$seasonImage = $this->getSeasonImages((int)$tv_id, $season['season_number'], $i);
								if($seasonImage){
									$seasonImages[] = $seasonImage;
								}
							}
							
							if(count($seasonImages) > $screencaps_count){$seasonImages = array_slice($seasonImages, 0, $screencaps_count);}
							
							//foreach($seasonImages as $img){$seasonImagesArray[] = $this->uploadImage('https://image.tmdb.org/t/p/original'.$img, $result['name']);}
							foreach($seasonImages as $img){$seasonImagesArray[] = $this->uploadImage('https://image.tmdb.org/t/p/'.$season_screeencaps_quality.$img, $result['name']);}
							
							$row->images = json_encode($seasonImagesArray);
						}
									
						$seasonVideos = $this->getSeasonVideos((int)$tv_id, $season['season_number']);
									
						if(count($seasonVideos)){
							$videos = array();
							foreach($seasonVideos as $video){
								$videos[] = array('site' => $video['site'], 'key' => $video['key']);
							}
							$row->videos = json_encode($videos);
						}
									
						$insert = $this->db->insertObject('#__tvshows_season', $row);
						if($insert){
							$saved[] = $season['id'];
						} else {
							$notsaved[] = $season['id'];
						}
					}
				}
				
				if(!count($notsaved)){
					return true;
				} else {
					return 'Seasons was not all '.implode(',', $notsaved);
				}
			}
		} else {
			return 'Seasons was not save because empty';
		}
	}
	
	protected function getAllSeasons(){
		$query = $this->db->getQuery(true);
		$query
			->select($this->db->quoteName(array('film', 'season_number', 'title')))
			->from($this->db->quoteName('#__tvshows_season'));
		$this->db->setQuery($query);
		return $this->db->loadObjectList();
	}
	
	protected function saveTvSeasonEpisode($tv_id, $season_number){
		try {
			$http = JHttpFactory::getHttp();
			$url = 'https://api.themoviedb.org/3/tv/'.$tv_id.'/season/'.$season_number.'?api_key='.$this->apikey1.'&language='.$this->lang_rec;
			$headers = array(
				"Accept:application/json"
			);
			if($request = $http->get($url, $headers)){
				$json = json_decode($request->body);
				//var_dump($json->episodes);exit;
				if(isset($json->episodes) && !empty($json->episodes)){
					$result = array();
					foreach($json->episodes as $k => $v){
						//var_dump($v->name);
						try {
							
							$query = $this->db->getQuery(true);
							$query
								->select($this->db->quoteName('name'))
								->from($this->db->quoteName('#__tvshows_episodes'))
								->where($this->db->quoteName('tv_id') . ' = '.$this->db->quote($tv_id))
								->where($this->db->quoteName('episode_number') . ' = '.$this->db->quote($v->episode_number))
								->where($this->db->quoteName('season_number') . ' = '.$this->db->quote($season_number));
							$this->db->setQuery($query);
							$exist = $this->db->loadResult();
							
							if(!$exist){
								$row = new stdClass();
								$row->tv_id = $tv_id;
								$row->season_number = $season_number;
								$row->episode_number = $v->episode_number;
								$row->name = $v->name;
								$insert = $this->db->insertObject('#__tvshows_episodes', $row);
								$result[] = $v->name;
							} else {
								$result[] = $exist;
							}
						} catch (Exception $e) {
							JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
							return false;
						}
					}
					//exit;
					return $result;
				}
			} else {
				return 'Seasons episodes request to API has no result';
			}
		} catch (Exception $e) {
			//JFactory::getApplication()->enqueueMessage($e->getMessage(), 'error');
			//return false;
			return 'Seasons episodes request to API '.$e->getMessage();
		}
	}
	
	function firstStep($data){		
		//echo 'firstStep';
		$this->year=$data->cp_year;
        $this->vote= $data->cp_vote;
        $this->lang= $data->cp_lang;
        $this->lang_rec= $data->cp_lang_rec;
		
		$urlyear='https://api.themoviedb.org/3/discover/tv?with_original_language='.$this->lang.'&vote_average.gte='.$this->vote.'&timezone=America%2FNew_York&first_air_date_year='.$this->year.'&sort_by=popularity.desc&language='.$this->lang_rec.'&api_key='.$this->apikey1;
		
		if ($curlshow = curl_init()) {
			curl_setopt($curlshow, CURLOPT_URL, $urlyear);
			curl_setopt($curlshow, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curlshow, CURLOPT_HTTPHEADER, array(
				"Accept:application/json"
			));
			$out = curl_exec($curlshow);
			curl_close($curlshow);
			$obj5 = json_decode($out, true);
		}
		
		//var_dump($obj5);
		
		return array('total_results' => $obj5['total_results'], 'total_pages' => $obj5['total_pages']);
	}
	
	function parseAllPagesCount($data){
		//echo 'parseAllPagesCount';
		$firstStep = $this->firstStep($data);
		
		$result['total_results'] = $firstStep['total_results'];
		$result['total_pages'] = $firstStep['total_pages'];
		
		return $result;	
	}
	
	function getTvs(){
		$query = $this->db->getQuery(true);
		$query
			->select($this->db->quoteName(array('id', 'title')))
			->from($this->db->quoteName('#__tvshows_film'))
			//->where($this->db->quoteName('published') . ' = 1')
			->order('id ASC');
		$this->db->setQuery($query);
		return $this->db->loadObjectList();
	}
	
	function getTvNextSeriesInfo($tv_id){		
		$urlserial='https://api.themoviedb.org/3/tv/'.$tv_id.'?api_key='.$this->apikey1.'&language='.$this->lang_rec;
		if ($curser = curl_init()) {
			curl_setopt($curser, CURLOPT_URL, $urlserial);
			curl_setopt($curser, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curser, CURLOPT_HTTPHEADER, array(
				"Accept:application/json"
			));
			$outserial = curl_exec($curser);
			curl_close($curser);
			$result = json_decode($outserial, true);
		}		
		
		if(isset($result['next_episode_to_air']) && !empty($result['next_episode_to_air'])){
			$object = new stdClass();
			$object->id = (int)$tv_id;
			$object->next_episode_time = $result['next_episode_to_air']['air_date'];
			$object->next_episode_name = $result['next_episode_to_air']['name'];
			$object->next_episode_season_number = $result['next_episode_to_air']['season_number'];
			return $this->db->updateObject('#__tvshows_film', $object, 'id');
		} else {
			return false;
		}
	}
	
	function replaceSeasonInfo($tv_id, $season_number, $text){
		if($this->film_table->load((int)$tv_id)){
			$query = $this->db->getQuery(true);
			$fields = array(
				$this->db->quoteName('description') . ' = ' . $this->db->quote($text),
				$this->db->quoteName('published') . ' = 1'
			);
			$conditions = array(
				$this->db->quoteName('film') . ' = ' . $this->db->quote($tv_id),
				$this->db->quoteName('season_number') . ' = ' . $this->db->quote((int)$season_number),
			);
			$query->update($this->db->quoteName('#__tvshows_season'))->set($fields)->where($conditions);
			$this->db->setQuery($query);
			return $this->db->execute();
		} else {
			return 'Film not exist';
		}
	}
	
	function replaceLinksInfo($tv_id, $season_number, $text){
		
		$query = $this->db->getQuery(true);
		$query
			->select(array('s.id'))
			->from($this->db->quoteName('#__tvshows_season', 's'))
			->join('INNER', $this->db->quoteName('#__tvshows_film', 'f') . ' ON (' . $this->db->quoteName('f.id') . ' = ' . $this->db->quoteName('s.film') . ')')
			->where($this->db->quoteName('f.id') . ' = ' . $this->db->quote($tv_id))
			->where($this->db->quoteName('s.season_number') . ' = ' . $this->db->quote($season_number));
		$this->db->setQuery($query);
		$season_id = $this->db->loadResult();
		
		//var_dump($tv_id, $season_number,$season_id);
		/*if($season_number == 7){
			var_dump($tv_id, $season_number, $season_id, $text);
			return true;
		}*/
		
		if($this->season_table->load((int)$season_id)){
			$query = $this->db->getQuery(true);
			$fields = array(
				$this->db->quoteName('links') . ' = ' . $this->db->quote($text),
				$this->db->quoteName('modified') . ' = ' . $this->db->quote(JFactory::getDate()->toSql())
			);
			$conditions = array($this->db->quoteName('id') . ' = ' . $this->db->quote($season_id));
			$query->update($this->db->quoteName('#__tvshows_season'))->set($fields)->where($conditions);
			$this->db->setQuery($query);
			return $this->db->execute();
		} else {
			return 'Film not exist';
		}
	}
	
	function replaceTvInfo($tv_id, $text){
		if($this->film_table->load((int)$tv_id)){
			$query = $this->db->getQuery(true);
			$fields = array(
				$this->db->quoteName('description') . ' = ' . $this->db->quote($text),
				$this->db->quoteName('published') . ' = 1'
			);
			$conditions = array($this->db->quoteName('id') . ' = ' . $this->db->quote($tv_id));
			$query->update($this->db->quoteName('#__tvshows_film'))->set($fields)->where($conditions);
			$this->db->setQuery($query);
			return $this->db->execute();
		} else {
			return 'Film not exist';
		}
	}
	
	public function replaceMovieInfo($tv_id, $text){
		if($this->movie_table->load((int)$tv_id)){
			$query = $this->db->getQuery(true);
			$fields = array(
				$this->db->quoteName('description') . ' = ' . $this->db->quote($text),
				$this->db->quoteName('published') . ' = 1'
			);
			$conditions = array($this->db->quoteName('id') . ' = ' . $this->db->quote($tv_id));
			$query->update($this->db->quoteName('#__tvshows_movies'))->set($fields)->where($conditions);
			$this->db->setQuery($query);
			return $this->db->execute();
		} else {
			return 'Movie not exist';
		}
	}
	
	function  onAjaxTvparse(){	
		JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_tvshows/tables');
		$this->film_table = JTable::getInstance('Film', 'TvshowsTable');
		$this->season_table = JTable::getInstance('Season', 'TvshowsTable');
		$this->movie_table = JTable::getInstance('Movie', 'TvshowsTable');
		$this->db = JFactory::getDbo();
	
		$app = JFactory::getApplication();
		$jinput = $app->input;
		$type= $jinput->get('type', null, 'STRING');
		//var_dump($type);exit;
		
		$return = false;
		
		switch ($type) {
			case 'exporttvshows':
				
				$query = $this->db->getQuery(true);
				
				$query
					->select(array('id', 'title', 'description', 'published'))
					->from($this->db->quoteName('#__tvshows_film'))
					->order($this->db->quoteName('title') . ' ASC');
				$this->db->setQuery($query);
				$results = $this->db->loadObjectList();
				
				$dataToCsv = array();
				foreach($results as $p){
					$dataToCsv[] = array($p->id, $p->title, $p->description, $p->published);
				}
				$head = array('id', 'title', 'description', 'published');
				echo self::arrayToCsvDownload($head, $dataToCsv);
				$app->close();
				
				break;
			case 'exportlinksextended':
				
				$query = $this->db->getQuery(true);
				
				$query
					->select(array('f.title', 'f.id', 's.season_number', 's.links', 's.description', 's.published'))
					->from($this->db->quoteName('#__tvshows_season', 's'))
					->join('INNER', $this->db->quoteName('#__tvshows_film', 'f') . ' ON (' . $this->db->quoteName('s.film') . ' = ' . $this->db->quoteName('f.id') . ')')
					->order($this->db->quoteName('f.title') . ' ASC')
					->order($this->db->quoteName('s.season_number') . ' ASC');
				$this->db->setQuery($query);
				$results = $this->db->loadObjectList();
				
				$dataToCsv = array();
				foreach($results as $p){
					$dataToCsv[] = array($p->title, $p->id, $p->season_number, $p->links, $p->description, $p->published);
				}
				$head = array('title', 'id', 'season_number', 'links', 'description', 'published');
				echo self::arrayToCsvDownload($head, $dataToCsv);
				$app->close();
				
				break;
			case 'exportlinks':
				
				$query = $this->db->getQuery(true);
				
				$query
					->select(array('f.title', 'f.id', 's.season_number', 's.links'))
					->from($this->db->quoteName('#__tvshows_season', 's'))
					->join('INNER', $this->db->quoteName('#__tvshows_film', 'f') . ' ON (' . $this->db->quoteName('s.film') . ' = ' . $this->db->quoteName('f.id') . ')')
					->order($this->db->quoteName('f.title') . ' ASC')
					->order($this->db->quoteName('s.season_number') . ' ASC');
				$this->db->setQuery($query);
				$results = $this->db->loadObjectList();
				
				$dataToCsv = array();
				foreach($results as $p){
					$dataToCsv[] = array($p->title, $p->id, $p->season_number, $p->links);
				}
				$head = array('title', 'id', 'season_number', 'links');
				echo self::arrayToCsvDownload($head, $dataToCsv);
				$app->close();
				
				break;
			case 'exportmovies':
				
				$query = $this->db->getQuery(true);
				
				$query
					->select(array('title', 'id', 'description'))
					->from($this->db->quoteName('#__tvshows_movies'))
					->order($this->db->quoteName('title') . ' ASC');
				$this->db->setQuery($query);
				$results = $this->db->loadObjectList();
				
				$dataToCsv = array();
				foreach($results as $p){
					$dataToCsv[] = array($p->id, $p->title, $p->description);
				}
				$head = array('id', 'title', 'description');
				echo self::arrayToCsvDownload($head, $dataToCsv);
				$app->close();
				
				break;
			case 'getGenres':
				$data = $jinput->get('data', null, 'STRING');
				if(isset($data) && !empty($data)){
					$data = json_decode($data);
				}
				
				$return = $this->getGenres($data);
				
				break;
				
			case 'parseAllPagesCount':
			
				$data = $jinput->get('data', null, 'STRING');
				if(isset($data) && !empty($data)){
					$data = json_decode($data);
				}
				
				$return = $this->parseAllPagesCount($data);
				
				break;
			
			case 'parseTvPage':
				$data = $jinput->get('data', null, 'STRING');
				if(isset($data) && !empty($data)){
					$data = json_decode($data);
				}
				
				$return = $this->parseTvPage($data->page);
				//$return = 'qqq';
				
				break;
				
			case 'saveTvInfo':
				$data = $jinput->get('data', null, 'STRING');
				if(isset($data) && !empty($data)){
					$data = json_decode($data);
				}
				
				$return = $this->saveTvInfo($data->tv_id, $data->lang);
				
				break;
			
			case 'saveTvExternal':
				$data = $jinput->get('data', null, 'STRING');
				if(isset($data) && !empty($data)){
					$data = json_decode($data);
				}
				
				$return = $this->saveTvExternal($data->tv_id);
				
				break;	

			case 'saveTvImages':
				$data = $jinput->get('data', null, 'STRING');
				if(isset($data) && !empty($data)){
					$data = json_decode($data);
				}
				
				if(isset($data->tv_id) && !empty($data->tv_id) && isset($data->tv_name) && !empty($data->tv_name)){
					$return = $this->saveTvImages($data->tv_id, urldecode($data->tv_name), $data->film_screeencaps_quality, $data->screencaps_count);
				} else {
					$return = false;
				}
				
				break;	
				
			case 'saveTvMoreInfo':
				$data = $jinput->get('data', null, 'STRING');
				if(isset($data) && !empty($data)){
					$data = json_decode($data);
				}
				
				$return = $this->saveTvMoreInfo($data->tv_id);
				
				break;	
				
			case 'getTvSeasons':
				$data = $jinput->get('data', null, 'STRING');
				if(isset($data) && !empty($data)){
					$data = json_decode($data);
				}
				
				$return = $this->getTvSeasons($data->tv_id, $data->saveTvImages);
				
				break;	
			
			case 'saveTvSeasons':
				$data = $jinput->get('data', null, 'STRING');
				if(isset($data) && !empty($data)){
					$data = json_decode($data);
				}
				
				if(isset($data->lang) && !empty($data->lang)){
					$this->lang_rec = $data->lang;
				}
				//var_dump($data);exit;
				
				if(isset($data->tv_id) && !empty($data->tv_id)){
					$return = $this->saveTvSeasons($data->tv_id, $data->screencaps_count, $data->season_poster_quality, $data->season_screeencaps_quality);
				} else {
					$return = false;
				}
				
				break;			

			case 'saveTvNextEpisode':
				$data = $jinput->get('data', null, 'STRING');
				if(isset($data) && !empty($data)){
					$data = json_decode($data);
				}
				
				if(isset($data->tv_id) && !empty($data->tv_id)){
					$return = $this->saveTvNextEpisode($data->tv_id);
				} else {
					$return = false;
				}
				
				break;			
			
			case 'getTest':
				$data = $jinput->get('data', null, 'STRING');
				if(isset($data) && !empty($data)){
					$data = json_decode($data);
				}
				
				$return = $this->getTest($data);
				
				break;
				
			case 'getTvs':
				
				$return = $this->getTvs();
				//$return = 'getTvs';
				
				break;
				
			case 'getTvNextSeriesInfo':
				$data = $jinput->get('data', null, 'STRING');
				if(isset($data) && !empty($data)){
					$data = json_decode($data);
				}
				
				if(isset($data->tv_id) && !empty($data->tv_id)){
					$return = $this->getTvNextSeriesInfo($data->tv_id);
				} else {
					$return = false;
				}
				
			break;	
			
			case 'replaceSeasonInfo':
				$tv_id = $jinput->get('tv_id', null, 'STRING');
				$season_number = $jinput->get('season_number', null, 'STRING');
				$text = $jinput->get('text', null, 'STRING');
				
				if(isset($tv_id) && !empty($tv_id) && isset($season_number) && !empty($season_number)){
					$return = $this->replaceSeasonInfo($tv_id, $season_number, urldecode($text));
				} else {
					$return = false;
				}
				
				break;
				
			case 'replaceLinksInfo':
				$tv_id = $jinput->get('tv_id', null, 'STRING');
				$season_number = $jinput->get('season_number', null, 'STRING');
				$links = $jinput->get('links', null, 'STRING');
				
				if(isset($tv_id) && !empty($tv_id) && isset($season_number) && !empty($season_number)){
					$return = $this->replaceLinksInfo($tv_id, $season_number, $links);
					//$return = array($tv_id, $season_number, $links);
				} else {
					//echo '<pre>';var_dump($jinput);
					$return = false;
				}
				
				break;
				
			case 'replaceTvInfo':
				$tv_id = $jinput->get('tv_id', null, 'STRING');
				$text = $jinput->get('text', null, 'STRING');
				
				if(isset($tv_id) && !empty($tv_id)){
					$return = $this->replaceTvInfo($tv_id, urldecode($text));
				} else {
					$return = false;
				}
				
				break;
				
			case 'getTvInfo':
				$data = $jinput->get('data', null, 'STRING');
				if(isset($data) && !empty($data)){
					$data = json_decode($data);
				}
				
				if(isset($data->tv_id) && !empty($data->tv_id)){
					$return = $this->getTvInfo($data->tv_id, $data->lang);
				} else {
					$return = false;
				}
				
				break;
				
			case 'getAllTvList':

				$return = $this->getAllTvList();
				
				break;
			
			case 'saveTvSeasonEpisode':
				$data = $jinput->get('data', null, 'STRING');
				if(isset($data) && !empty($data)){
					$data = json_decode($data);
				}
				
				$tv_id = $data->tv_id;
				$season_number = $data->season_number;
				if(isset($data->language) && !empty($data->language)){
					$this->lang_rec = $data->language;
				} else {
					$this->lang_rec = 'en-US';
				}
				//var_dump($tv_id, $season_number, $this->lang_rec);exit;
				if(
					isset($tv_id) && !empty($tv_id) &&
					isset($season_number) && !empty($season_number) &&
					isset($this->lang_rec) && !empty($this->lang_rec)
				){
					$return = $this->saveTvSeasonEpisode($tv_id, $season_number);
				} else {
					$return = false;
				}
				
				break;
				
			case 'getAllSeasons':
				$return = $this->getAllSeasons();
				break;
				
			case 'saveMovieInfo':
				$data = $jinput->get('data', null, 'STRING');
				if(isset($data) && !empty($data)){
					$data = json_decode($data);
				}
				if(isset($data->language) && !empty($data->language)){
					$this->lang_rec = $data->language;
				} else {
					$this->lang_rec = 'en-US';
				}
				
				$movie_id = $data->movie_id;
				if(isset($movie_id) && !empty($movie_id)){
					$return = $this->saveMovieInfo($movie_id, $data->poster_quality, $data->screeencaps_quality, $data->language);
				} else {
					$return = false;
				}
				break;
				
			case 'replaceMovieInfo':
				$tv_id = $jinput->get('tv_id', null, 'STRING');
				$text = $jinput->get('text', null, 'STRING');
				
				if(isset($tv_id) && !empty($tv_id)){
					$return = $this->replaceMovieInfo($tv_id, urldecode($text));
				} else {
					$return = false;
				}
				
				break;
		}
		
		echo json_encode( array('data' => $return) );
		$app->close();
	}
}