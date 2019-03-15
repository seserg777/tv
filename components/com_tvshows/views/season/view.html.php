<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");
 
/**
 * Season item view class.
 *
 * @package     Tvshows
 * @subpackage  Views
 */
class TvshowsViewSeason extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;
	protected $params;
	
	public function display($tpl = null) {
		$this->state 	= $this->get('State');
		$this->item 	= $this->get('Item');
		$this->form 	= $this->get('Form');
		$this->neighbors 	= $this->get('Neighbors');
		$this->component_params = JComponentHelper::getParams('com_tvshows');
		
		if(
			!isset($this->item->links) || empty($this->item->links) ||
			!count($this->item->links) || !$this->item->links[$this->item->season_number]
		){	
			$this->item->episodes = $this->get('Episodes');
		}
		
		if(isset($this->item->film->id) && !empty($this->item->film->id)){
			$this->item->film->tags = new JHelperTags;
			$this->item->film->tags->getItemTags('com_tvshows.film' , $this->item->film->id);
			
			$this->item->count_seasons = array();
			for($i = 1; $i<=(count($this->item->film->seasons)+1);$i++){
				$this->item->count_seasons[] = $i;
			}
			$this->item->count_seasons = implode(',', $this->item->count_seasons);
		}

		$app = JFactory::getApplication();
		
		$user = JFactory::getUser();
		
		// Check if item is empty
		if (empty($this->item))
		{
			$app->redirect(JRoute::_('index.php?option=com_tvshows&view=seasons'), JText::_('JERROR_NO_ITEMS_SELECTED'));
		}
			
		// Is the user allowed to create an item?
		if (!$this->item->id && !$user->authorise("core.create", "com_tvshows"))
		{
			throw new Exception(JText::_('JERROR_ALERTNOAUTHOR'));
		}
		
		if(!$this->item->published){
			throw new Exception(JText::_('JNOTPUBLISHEDYET'));
		}

		// Get menu params
		$menu = $app->getMenu();
		$active = $menu->getActive();
		
		if (is_object($active))
		{
			$this->params = $active->params;
		}

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}		

		// Increment hits
		$model = $this->getModel();
		$model->hit($this->item->id);
		
		$this->_prepareDocument();

		parent::display($tpl);
	}

	/**
	 * Prepares the document.
	 *
	 * @return  void
	 */
	protected function _prepareDocument()
	{
		$app     = JFactory::getApplication();
		$doc = $app->getDocument();
		$menus   = $app->getMenu();
		$pathway = $app->getPathway();
		$title   = null;
		
		$season_meta_title_pattern = $this->component_params->get('season_meta_title_pattern', null);
		$season_meta_description_pattern = $this->component_params->get('season_meta_description_pattern', null);
		
		$this->item->implodetags = array();
		if(count($this->item->film->tags->itemTags)){
			foreach($this->item->film->tags->itemTags as $tag){
				$this->item->implodetags[] = $tag->title;
			}
			$this->item->implodetags = implode(',', $this->item->implodetags);
		} 
		
		/**
		 * Because the application sets a default page title,
		 * we need to get it from the menu item itself
		 */
		$menu = $menus->getActive();

		if ($menu)
		{
			$this->params->def('page_heading', $this->params->get('page_title', $menu->title));
		}
		else
		{
			$this->params->def('page_heading', JText::_('JGLOBAL_ARTICLES'));
		}

		$title = $this->params->get('page_title', '');

		$id = (int) @$menu->query['id'];

		$pathway = $app->getPathway();
		if(isset($this->item->film) && isset($this->item->film->title) && !empty($this->item->film->title)){
			$pathway->addItem(ucfirst($this->item->film->title), TvshowsHelperRoute::getFilmRoute(null, $this->item->film->alias));
			//$pathway->addItem(ucfirst($this->item->alias));
		} else {
			//$pathway->addItem(ucfirst($this->item->alias));
		}
		
		// If the menu item does not concern this article
		if ($menu && ($menu->query['option'] !== 'com_content' || $menu->query['view'] !== 'article' || $id != $this->item->id))
		{
			// If a browser page title is defined, use that, then fall back to the article title if set, then fall back to the page_title option
			$title = $this->params->get('article_page_title', $this->item->title ?: $title);

			$path     = array(array('title' => $this->item->title, 'link' => ''));

			$path = array_reverse($path);

			foreach ($path as $item)
			{
				$pathway->addItem($item['title'], $item['link']);
			}
		}

		// Check for empty title and add site name if param is set
		if (empty($title))
		{
			$title = $app->get('sitename');
		}
		elseif ($app->get('sitename_pagetitles', 0) == 1)
		{
			$title = JText::sprintf('JPAGETITLE', $app->get('sitename'), $title);
		}
		elseif ($app->get('sitename_pagetitles', 0) == 2)
		{
			$title = JText::sprintf('JPAGETITLE', $title, $app->get('sitename'));
		}

		if (empty($title))
		{
			$title = $this->item->title;
		}
		
		if(isset($season_meta_title_pattern) && !empty($season_meta_title_pattern)){
			$custom_title = str_replace('{season_number}', $this->item->season_number, str_replace('{film_name}', $this->item->film->title, $season_meta_title_pattern));
			
			if(count($this->item->implodetags)){
				$custom_title = str_replace('{film_genres}', $this->item->implodetags, $custom_title);
			}
			
			if(isset($this->item->film->creators) && !empty($this->item->film->creators)){
				$custom_title = str_replace('{film_creators}', $this->item->film->creators, $custom_title);
			}
			
			if(isset($this->item->film->channel) && !empty($this->item->film->channel)){
				$custom_title = str_replace('{film_channel}', $this->item->film->channel, $custom_title);
			}
			
			if(isset($this->item->count_seasons) && !empty($this->item->count_seasons)){
				$custom_title = str_replace('{seasons_enumeration}', $this->item->count_seasons, $custom_title);
			}
			
			$this->document->setTitle($custom_title);
		} else {
			$this->document->setTitle($title);
		}
		
		if(isset($season_meta_description_pattern) && !empty($season_meta_description_pattern)){
			$custom_description = str_replace('{season_number}', $this->item->season_number, str_replace('{film_name}', $this->item->film->title, $season_meta_description_pattern));
			
			if(count($this->item->implodetags)){
				$custom_description = str_replace('{film_genres}', $this->item->implodetags, $custom_description);
			}
			
			if(isset($this->item->film->creators) && !empty($this->item->film->creators)){
				$custom_description = str_replace('{film_creators}', $this->item->film->creators, $custom_description);
			}
			
			if(isset($this->item->film->channel) && !empty($this->item->film->channel)){
				$custom_description = str_replace('{film_channel}', $this->item->film->channel, $custom_description);
			}
			
			if(isset($this->item->count_seasons) && !empty($this->item->count_seasons)){
				$custom_description = str_replace('{seasons_enumeration}', $this->item->count_seasons, $custom_description);
			}
			
			$this->document->setDescription($custom_description);
		} else {
			if ($this->item->metadesc)
			{
				$this->document->setDescription($this->item->metadesc);
			}
			elseif ($this->params->get('menu-meta_description'))
			{
				$this->document->setDescription($this->params->get('menu-meta_description'));
			}
		}

		if ($this->item->metakey)
		{
			$this->document->setMetadata('keywords', $this->item->metakey);
		}
		elseif ($this->params->get('menu-meta_keywords'))
		{
			$this->document->setMetadata('keywords', $this->params->get('menu-meta_keywords'));
		}

		if ($this->params->get('robots'))
		{
			$this->document->setMetadata('robots', $this->params->get('robots'));
		}

		//var_dump($this->item->metadata);
		$mdata = (array)$this->item->metadata;

		foreach ($mdata as $k => $v)
		{
			if ($v)
			{
				$this->document->setMetadata($k, $v);
			}
		}

		// If there is a pagebreak heading or title, add it to the page title
		if (!empty($this->item->page_title))
		{
			$this->item->title = $this->item->title . ' - ' . $this->item->page_title;
			$this->document->setTitle(
				$this->item->page_title . ' - ' . JText::sprintf('PLG_CONTENT_PAGEBREAK_PAGE_NUM', $this->state->get('list.offset') + 1)
			);
		}
		
		$doc->addHeadLink(htmlspecialchars(JUri::getInstance()), 'canonical');
	}
}
?>