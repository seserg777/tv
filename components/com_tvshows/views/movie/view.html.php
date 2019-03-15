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
class TvshowsViewMovie extends JViewLegacy
{
	protected $item;
	protected $form;
	protected $state;
	protected $params;
	
	public function display($tpl = null) {
		$this->state 	= $this->get('State');
		$this->item 	= $this->get('Item');
		if(isset($this->item->videos) && !empty($this->item->videos)){
			$this->item->videos = json_decode($this->item->videos);
		}
		//echo '<pre>';var_dump($this->item->videos);
		$this->form 	= $this->get('Form');
		$this->neighbors 	= $this->get('Neighbors');
		$this->component_params = JComponentHelper::getParams('com_tvshows');

		$app = JFactory::getApplication();
		
		$user = JFactory::getUser();
		
		// Check if item is empty
		if (empty($this->item))
		{
			$app->redirect(JRoute::_('index.php?option=com_tvshows&view=movies'), JText::_('JERROR_NO_ITEMS_SELECTED'));
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
		
		$this->comments_type = $this->component_params->get('comments_type');
		$this->recaptcha_public = $this->component_params->get('recaptcha-public');
		$this->fileshare_domains = $this->component_params->get('fileshare_domains');
		$this->fileshare_domains = json_decode($this->fileshare_domains);
		$this->enable_modal_fileshare_domains = $this->component_params->get('enable_movie_modal_fileshare_domains', false, 'INT');
		$this->enable_movie_custom_btn_1 = $this->component_params->get('enable_movie_custom_btn_1', false, 'INT');
		$this->scroll_to_download_btn = $this->component_params->get('movie_scrolltodownload_btn', null, 'INT');
		$this->movie_custom_btn_1_anchor = $this->component_params->get('movie_custom_btn_1_anchor', null);
		$this->movie_custom_btn_1_link = $this->component_params->get('movie_custom_btn_1_link', null);
		$this->h1_pattern = $this->component_params->get('movie_h1_pattern', null);
		$this->h2_pattern = $this->component_params->get('movie_h2_pattern', null);
		$this->description_pattern = $this->component_params->get('movie_description_pattern', null);
		$this->description_pattern_2 = $this->component_params->get('movie_description_pattern_2', null);
		$this->movie_imdb_link = $this->component_params->get('movie_imdb_link', null);
		$this->movie_imdb_link_nofollow = $this->component_params->get('movie_imdb_link_nofollow', null);
		
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
		
		$movie_meta_title_pattern = $this->component_params->get('movie_meta_title_pattern', null);
		$movie_meta_description_pattern = $this->component_params->get('movie_meta_description_pattern', null);
		
		$this->item->implodetags = array();
		if(count($this->item->tags->itemTags)){
			foreach($this->item->tags->itemTags as $tag){
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
		
		if(isset($movie_meta_title_pattern) && !empty($movie_meta_title_pattern)){
			$custom_title = TvshowsHelperMovie::replacer($this->item, $movie_meta_title_pattern);
			$this->document->setTitle($custom_title);
		} else {
			$this->document->setTitle($title);
		}
		
		if(isset($movie_meta_description_pattern) && !empty($movie_meta_description_pattern)){
			$custom_description = TvshowsHelperMovie::replacer($this->item, $movie_meta_description_pattern);
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

		/*
		/var_dump($this->item->metadata);
		$mdata = (array)$this->item->metadata;
		//echo '<pre>';var_dump($mdata);
		
		foreach ($mdata as $k => $v)
		{
			if ($v)
			{
				$this->document->setMetadata($k, $v);
			}
		}
		*/

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