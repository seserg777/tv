<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

use Joomla\CMS\Table\Table;

/**
 * Film table class.
 *
 * @package     Tvshows
 * @subpackage  Tables
 */
class TvshowsTableFilm extends JTable
{
	/**
	 * Constructor
	 *
	 * @param   JDatabaseDriver  &$db  A database connector object
	 */
	public function __construct(&$db)
	{
		parent::__construct('#__tvshows_film', 'id', $db);
		JTableObserverTags::createObserver($this, array('typeAlias' => 'com_tvshows.film'));
	}

	/**
     * Overloaded check function
     */
    public function check()
	{
        //If there is an ordering column and this is a new row then get the next ordering value
        if (property_exists($this, 'ordering') && $this->id == 0) {
            $this->ordering = self::getNextOrder();
        }

		// Check alias
		if (empty($this->alias))
		{
			$this->alias = $this->title;
		}
		$this->alias = JApplication::stringURLSafe($this->alias);
		
		// check for valid category
		if (trim($this->catid) == '')
		{
			$this->setError(JText::_('JGLOBAL_CHOOSE_CATEGORY_LABEL'));

			return false;
		}
		
		// Clean up keywords -- eliminate extra spaces between phrases
		// and cr (\r) and lf (\n) characters from string
		if (!empty($this->metakey))
		{
			// Only process if not empty
			$bad_characters = array("\n", "\r", "\"", "<", ">"); // array of characters to remove
			$after_clean = JString::str_ireplace($bad_characters, "", $this->metakey); // remove bad characters
			$keys = explode(',', $after_clean); // create array using commas as delimiter
			$clean_keys = array();

			foreach($keys as $key)
			{
				if (trim($key)) {  // ignore blank keywords
					$clean_keys[] = trim($key);
				}
			}
			$this->metakey = implode(", ", $clean_keys); // put array back together delimited by ", "
		}

		// Clean up description -- eliminate quotes and <> brackets
		if (!empty($this->metadesc))
		{
			// Only process if not empty
			$bad_characters = array("\"", "<", ">");
			$this->metadesc = JString::str_ireplace($bad_characters, "", $this->metadesc);
		}

        return parent::check();
    }

	/**
	 * Method to bind an associative array or object to the JTable instance.
	 *
	 * @see JTable
	 */
	public function bind($array, $ignore = '')
	{
		//die('qqq');
		if (isset($array['metadata']) && is_array($array['metadata']))
		{
			$registry = new JRegistry();
			$registry->loadArray($array['metadata']);
			$array['metadata'] = (string) $registry;
		}

		return parent::bind($array, $ignore);
	}
	
	/**
	 * Overriden JTable::store to set modified data.
	 *
	 * @param   boolean	True to update fields even if they are null.
	 * @return  boolean  True on success.
	 * @since   1.6
	 */
	public function store($updateNulls = false)
	{
		//die('zzz');
		//echo '<pre>';var_dump($this);exit;
		$date	= JFactory::getDate();
		$user	= JFactory::getUser();
		
		if ($this->id)
		{
			// Existing item
			$this->modified		= $date->toSql();
			
		}
		else
		{
			// New item. An item created and created_by field can be set by the user,
			// so we don't touch either of these if they are set.			
			if (!(int) $this->created)
			{
				$this->created = $date->toSql();
			}
		}
		
		// Verify that the alias is unique
		$table = JTable::getInstance('Film', 'TvshowsTable');
		//var_dump($this->id,$this->alias);
		if ($table->load(array('alias' => $this->alias, 'catid' => $this->catid)) && ($table->id != $this->id || $this->id == 0))
		{
			$this->setError(JText::_('UNIQUE_ALIAS'));
			return false;
		}

		//echo '<pre>';var_dump($this->published);exit;
		if(isset($this->published)){
			$ucmContentTable = Table::getInstance('Corecontent');
		}
		
		//$helper = new JHelperTags;
		//$typeId = $helper->getTypeId('com_tvshows.film');
		//$helper->tagItem($typeId, $this, $this->newTags);
		//echo '<pre>';var_dump(parent::store($updateNulls));exit;
		return parent::store($updateNulls);
	}
}
?>