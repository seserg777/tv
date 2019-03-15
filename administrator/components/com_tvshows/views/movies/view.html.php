<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

require_once JPATH_COMPONENT.'/helpers/tvshows.php';

/**
 * Films list view class.
 *
 * @package     Tvshows
 * @subpackage  Views
 */
class TvshowsViewMovies extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	
	public function display($tpl = null)
	{
		$this->items		 = $this->getModel()->getItems();
		$this->state		 = $this->getModel()->getState();
		$this->pagination	 = $this->getModel()->getPagination();
		$this->filterForm    = $this->getModel()->getFilterForm();
		$this->activeFilters = $this->getModel()->getActiveFilters();

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			throw new Exception(implode("\n", $errors));
			return false;
		}
		
		TvshowsHelper::addSubmenu('movies');
		
		// We don't need toolbar in the modal window.
		if ($this->getLayout() !== 'modal')
		{
			$this->addToolbar();
			$this->sidebar = JHtmlSidebar::render();
		}
		
		parent::display($tpl);
	}
	
	/**
	 *	Method to add a toolbar
	 */
	protected function addToolbar()
	{
		$state	= $this->get('State');
		$canDo	= TvshowsHelper::getActions();
		$user	= JFactory::getUser();

		// Get the toolbar object instance
		$bar = JToolBar::getInstance('toolbar');
		
		JToolBarHelper::title(JText::_('COM_TVSHOWS_MOVIE_VIEW_FILMS_TITLE'));
		
		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_tvshows', 'core.create'))) > 0 )
		{
			JToolBarHelper::addNew('movie.add','JTOOLBAR_NEW');
		}

		if (($canDo->get('core.edit')) && isset($this->items[0]))
		{
			JToolBarHelper::editList('movie.edit','JTOOLBAR_EDIT');
		}
		
		if ($canDo->get('core.edit.state'))
		{
            if (isset($this->items[0]->published))
			{
			    JToolBarHelper::divider();
				JToolbarHelper::publish('movies.publish', 'JTOOLBAR_PUBLISH', true);
				JToolbarHelper::unpublish('movies.unpublish', 'JTOOLBAR_UNPUBLISH', true);
            } 
			else if (isset($this->items[0]))
			{
                // Show a direct delete button
                JToolBarHelper::deleteList('', 'movies.delete','JTOOLBAR_DELETE');
            }

            if (isset($this->items[0]->published))
			{
			    JToolBarHelper::divider();
			    JToolBarHelper::archiveList('movies.archive','JTOOLBAR_ARCHIVE');
            }
            
			if (isset($this->items[0]->checked_out))
			{
				JToolbarHelper::checkin('movies.checkin');
            }
		}
		
		// Show trash and delete for components that uses the state field
        if (isset($this->items[0]->published))
		{
		    if ($state->get('filter.published') == -2 && $canDo->get('core.delete'))
			{
			    JToolBarHelper::deleteList('', 'movies.delete','JTOOLBAR_EMPTY_TRASH');
			    JToolBarHelper::divider();
		    }
			else if ($state->get('movies.published') != -2 && $canDo->get('core.edit.state'))
			{
			    JToolBarHelper::trash('movies.trash','JTOOLBAR_TRASH');
			    JToolBarHelper::divider();
		    }
        }
		
		// Add a batch button
		if (isset($this->items[0]) && $user->authorise('core.create', 'com_contacts') && $user->authorise('core.edit', 'com_contacts') && $user->authorise('core.edit.state', 'com_contacts'))
		{
			JHtml::_('bootstrap.modal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');

			// Instantiate a new JLayoutFile instance and render the batch button
			$layout = new JLayoutFile('joomla.toolbar.batch');

			$dhtml = $layout->render(array('title' => $title));
			$bar->appendButton('Custom', $dhtml, 'batch');
		}
		
		if ($canDo->get('core.admin'))
		{
			JToolBarHelper::preferences('com_tvshows');
		}
	}
}
?>