<?php
/**
 * @author		Peak-systems
 * @copyright	
 * @license		GNU General Public License version 2 or later
 */

defined("_JEXEC") or die("Restricted access");

/**
 * Tvshows Component Category Tree
 *
 * @static
 * @package     Tvshows
 * @subpackage  Helpers
 */
class TvshowsCategories extends JCategories
{
	/**
	 * Constructor
	 */
	public function __construct($options = array())
	{
		$options["extension"] = "com_tvshows";
		$options["table"] = "#__tvshows_film";
		$options["field"] = "catid";
		$options["key"] = "id";
		$options["statefield"] = "published";		
		$options['access'] = 0;

		parent::__construct($options);
	}
}