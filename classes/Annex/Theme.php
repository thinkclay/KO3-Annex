<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Acts as an object wrapper for different kind of pages such as haml or html mixed with php, called "themes".
 * Variables can be assigned with the theme object and referenced locally within
 * the theme view.
 *
 * @package		Annex
 * @category	Base
 * @author		Jiran Dowlati
 */ 

class Annex_Theme
{
	// Local Variables to the View
	protected $_file;
	protected $_data = array();
	protected $_ext; // Extension of file.
	protected $_default_file; // Default file is set if file can't be found in another folder and is found in default. 
	public static $_theme_name = 'default';

	/**
     * Follows factory pattern, returns Theme object in order to be able to chain functions and so on.
     *
     *  $view = Theme::view($file);
     *
     * @param   string  view filename
     * @param   array   array of values
     * @return  Theme object
     */
	public static function factory($file = NULL, $data = NULL)
	{
		return new Theme($file, $data);
	}

	/**
	 * Sets the initial view filename and local data. 
	 *
	 *	$view = new Theme($file);
	 *
	 * @param	string	view filename
	 * @param	array	array of values
	 * @return	void
	 * @uses	Theme::set_filename
	 */
	public function __construct($file = NULL, array $data = NULL)
	{
		// Set theme to whatever config file has for theme name. 
		if ( $theme = Kohana::$config->load('annex_annex.theme.name') )
			static::$_theme_name = $theme;

		if( $file !== NULL)
		{
			// Sets file name
			$this->set_filename($theme, $file);
		}

		if($data !== NULL)
		{
			// Add values to the current data array
			$this->_data = $data + $this->_data;
		}
	}
	

	/**
	 * Gets extension of filename.  
	 *
	 *	$ext = $this->get_extension($file);
	 *
	 * @param	string	view filename
	 * @return	string 	extension 
	 */
	public function get_extension($file)
	{
		return pathinfo($file, PATHINFO_EXTENSION);
	}

	/**
	 * The set_filename function first checks if the file exists, if it does then we set the $ext 
	 * and _file. However, if the file does not exist then we check if we have a default of that file to
	 * use instead and if we do then we set the $ext and file of that default file. If it doesn't exist in either
	 * place then throw an error. 
	 *
	 *	$this->set_filename($file);
	 *
	 * @param	string	view filename
	 * @return	void
	 * @uses 	local $_file and $_ext variable
	 */
	public function set_filename($theme, $file)
	{
		if( $path = Kohana::find_file('themes', $theme . '/' . $file) )
		{
			$this->_ext = $this->get_extension($path);
			$this->_file = $path;
		}
		elseif( $path = Kohana::find_file('themes', 'default/' . $file) )
		{
			$this->_ext = $this->get_extension($path);
			$this->_file = $path;
		}
		else
		{
			echo "Couldn't set file beacuse file could not be found in any folders";
		}
		
	}

	/**
	 * Capture is protected function that essentially extracts all of the data array into variables. It also outputs the buffer
	 * and try to render the files we are asking it to. 
	 *
	 *	Theme::capture($this->_file, $this->_data);
	 *
	 * @param	string view filename, array view data
	 * @return	the output of the file passed.
	 */
	protected static function capture($view_file, array $view_data)
	{
		// Extract all data into variables, EXTR_SKIP mean it won't override existing variables. 
		extract($view_data, EXTR_SKIP);

		// Capture view output
		ob_start();

		try
		{
			// Load the view 
			include $view_file;
		} 
		catch (Exception $e)
		{
			// Delete the buffer
			ob_end_clean();

			// Throw exception
			throw $e;	
		}

		// Get the captured output and close buffer. 
		return ob_get_clean();
	}

	/**
	 * Render is the public function that essentially calls our capture function and renders the view we have set. 
	 * We have the option of rendering a different file if that file exists. 
	 *
	 *	$this->render($file);
	 *
	 * @param	string view filename
	 * @return	the output of the view set. 
	 */
	public function render($file = NULL)
	{
		if($file !== NULL)
		{
			$this->set_filename($file);
		}

		if (empty($this->_file))
		{
			echo 'You must set the file to use within your view before rendering';
		}

		return Theme::capture($this->_file, $this->_data);
	}

	/**
	 * Bind passes all the set variables to the view. This is the fastest way to set variables for the view.
	 *
	 *	$this->bind($key, $value);
	 *
	 * @param	string key, string value 
	 * @return	$this
	 */
	public function bind($key, $value)
	{
		$this->_data[$key] = $value;

		return $this;
	}

	/**
	 * Set is essentially doing what Bind does but a little less efficent. It also has the ability to set an array of variables. 
	 *
	 *	$this->set($key);
	 *
	 * 	Example with arrays:
	 * 	$view->set(array('food' => 'bread', 'beverage' => 'water'));
	 * @param	string key, string value 
	 * @return	$this
	 */
	public function set($key, $value = NULL)
	{
		if( is_array($key) )
		{
			foreach($key as $name => $value)
			{
				$this->_data[$name] = $value;
			}
		}
		else
		{
			$this->_data[$key] = $value;
		}

		return $this;
	}

	/**
	 * MAGIC METHODS - Some usability functions.  
	 *
	 * @package		Annex
	 * @category	Base
	 * @author		Jiran Dowlati
	 */

	public function __set($key, $value)
	{
		$this->set($key, $value);
	} 

	/**
	 * Theme functions for HTML.
	 *
	 * @package		Annex
	 * @category	Base
	 * @author		Jiran Dowlati
	 */
	public function scripts($type = 'raw_js', array $files)
	{
		if ( $type == 'jquery' )
		{
			$jquery = 'http://code.jquery.com/jquery.js';
			$this->bind('jquery', $jquery);
			$this->bind('files', $files);
		}
		else
		{
			$this->bind('files', $files);
		}

	}

	public function styles(array $styles)
	{
		$this->bind('styles', $styles);

	}


}

?>