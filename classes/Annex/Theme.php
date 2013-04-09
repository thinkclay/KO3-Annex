<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Acts as an object wrapper for different kind of pages such as haml, html, or embedded php
 * Variables can be assigned with the theme object and referenced locally within the theme view.
 *
 * @package     Annex
 * @category    Base
 * @author      Jiran Dowlati
 */
class Annex_Theme
{
    // View file name
    protected $_file;

    // Data to be passed to the view
    protected $_data = [];

    // Array of global variables
    protected static $_global_data = [];

    // Extension of file.
    protected $_ext;

    // Default file is set if file can't be found in another folder and is found in default.
    protected $_default_file;

    // The theme name to use when looking for files
    public static $_theme_name = 'default';


    /**
     * Sets the initial view filename and local data. Views should almost
     * always only be created using [Theme::factory].
     *
     *  $view = new Theme($file);
     *
     * @param   string  view filename
     * @param   array   array of values
     * @return  void
     * @uses    Theme::set_filename
     */
    public function __construct($file = NULL, array $data = NULL)
    {
        // Set theme to whatever config file has for theme name.
        if ( $theme = Kohana::$config->load('annex_annex.theme.name') )
        {
            static::$_theme_name = $theme;
        }

        if ( $file !== NULL )
        {
            // Sets file name
            $this->set_filename($theme, $file);
        }

        if ($data !== NULL )
        {
            // Add values to the current data array
            $this->_data = $data + $this->_data;
        }
    }

    /**
     * Magic method, searches for the given variable and returns its value.
     * Local variables will be returned before global variables.
     *
     *     $value = $view->foo;
     *
     * [!!] If the variable has not yet been set, an exception will be thrown.
     *
     * @param   string  variable name
     * @return  mixed
     * @throws  Kohana_Exception
     */
    public function & __get($key)
    {
        if ( array_key_exists($key, $this->_data) )
        {
            return $this->_data[$key];
        }
        elseif ( array_key_exists($key, Theme::$_global_data) )
        {
            return Theme::$_global_data[$key];
        }
        else
        {
            throw new Annex_Exception('View variable is not set: :var', array(':var' => $key));
        }
    }

    /**
     * Magic method, calls [Theme::set] with the same parameters.
     *
     *     $view->foo = 'something';
     *
     * @param   string  variable name
     * @param   mixed   value
     * @return  void
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /**
     * Magic method, determines if a variable is set.
     *
     *     isset($view->foo);
     *
     * [!!] `NULL` variables are not considered to be set by [isset](http://php.net/isset).
     *
     * @param   string  variable name
     * @return  boolean
     */
    public function __isset($key)
    {
        return ( isset($this->_data[$key]) OR isset(Theme::$_global_data[$key]) );
    }

    /**
     * Magic method, unsets a given variable.
     *
     *     unset($view->foo);
     *
     * @param   string  variable name
     * @return  void
     */
    public function __unset($key)
    {
        unset($this->_data[$key], Theme::$_global_data[$key]);
    }

    /**
     * Magic method, returns the output of [Theme::render].
     *
     * @return  string
     * @uses    Theme::render
     */
    public function __toString()
    {
        try
        {
            return $this->render();
        }
        catch (Exception $e)
        {
            // Display the exception message
            Annex_Exception::handler($e);

            return '';
        }
    }

    /**
     * Follows factory pattern, returns Theme object in order to be able to chain functions and so on.
     *
     * Returns a new Theme object. If you do not define the "file" parameter,
     * you must call [Theme::set_filename].
     *
     *     $view = Theme::factor($file);
     *
     * @param   string  view filename
     * @param   array   array of values
     * @return  Theme
     */
    public static function factory($file = NULL, array $data = NULL)
    {
        return new Theme($file, $data);
    }

    /**
     * Assigns a variable by name. Assigned values will be available as a
     * variable within the view file:
     *
     *  // This value can be accessed as $foo within the view
     *  $view->set('foo', 'my value');
     *
     * You can also use an array to set several values at once:
     *
     *  // Create the values $food and $beverage in the view
     *  $view->set(array('food' => 'bread', 'beverage' => 'water'));
     *
     * @param   string  variable name or an array of variables
     * @param   mixed   value
     * @return  $this
     */
    public function set($key, $value = NULL)
    {
        if ( is_array($key) )
        {
            foreach ( $key as $name => $value )
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
     * Sets a global variable, similar to [Theme::set], except that the
     * variable will be accessible to all views.
     *
     *  Theme::set_global($name, $value);
     *
     * @param   string  variable name or an array of variables
     * @param   mixed   value
     * @return  void
     */
    public static function set_global($key, $value = NULL)
    {
        if ( is_array($key) )
        {
            foreach ($key as $key2 => $value)
            {
                Theme::$_global_data[$key2] = $value;
            }
        }
        else
        {
            Theme::$_global_data[$key] = $value;
        }
    }

    /**
     * Assigns a value by reference. The benefit of binding is that values can
     * be altered without re-setting them. It is also possible to bind variables
     * before they have values. Assigned values will be available as a
     * variable within the view file:
     *
     *  // This reference can be accessed as $ref within the view
     *  $view->bind('ref', $bar);
     *
     * @param   string  variable name
     * @param   mixed   referenced variable
     * @return  $this
     */
    public function bind($key, & $value)
    {
        $this->_data[$key] =& $value;

        return $this;
    }

    /**
     * Assigns a global variable by reference, similar to [Theme::bind], except
     * that the variable will be accessible to all views.
     *
     *  Theme::bind_global($key, $value);
     *
     * @param   string  variable name
     * @param   mixed   referenced variable
     * @return  void
     */
    public static function bind_global($key, & $value)
    {
        Theme::$_global_data[$key] =& $value;
    }

    /**
     * Gets extension of filename.
     *
     *  $ext = $this->get_extension($file);
     *
     * @param   string  view filename
     * @return  string  extension
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
     *  $this->set_filename($file);
     *
     * @param   string  view filename
     * @return  void
     * @uses    local $_file and $_ext variable
     */
    public function set_filename($theme, $file)
    {
        if ( $path = Kohana::find_file('themes', $theme . '/' . $file) )
        {
            $this->_ext = $this->get_extension($path);
            $this->_file = $path;

            return $path;
        }
        elseif ( $path = Kohana::find_file('themes', 'default/' . $file) )
        {
            $this->_ext = $this->get_extension($path);
            $this->_file = $path;

            return $path;
        }
        else
        {
            echo "Couldn't set file beacuse file could not be found in any folders";
        }
    }

    /**
     * If we find the wrapper file for the user's theme we will use return that file path for the Public or Private wrappers
     *
     * @return  string  the relative file path to the theme wrapper
     *
     * @todo    this currently only works within annex folder and we want it to work for the app path as well
     */
    public function get_theme_wrapper()
    {

        if ( Kohana::find_file('themes', static::$_theme_name.'/views/wrapper') )
            return '../themes/'.static::$_theme_name.'/views/wrapper';
        else
            return '../themes/default/views/wrapper';
    }

    /**
     * Capture is protected function that essentially extracts all of the data array into variables. It also outputs the buffer
     * and try to render the files we are asking it to.
     *
     *  Theme::capture($this->_file, $this->_data);
     *
     * @param   string  view filename, array view data
     * @return  the output of the file passed.
     */
    /**
     * Captures the output that is generated when a view is included.
     * The view data will be extracted to make local variables. This method
     * is static to prevent object scope resolution.
     *
     *  $output = Theme::capture($file, $data);
     *
     * @param   string  filename
     * @param   array   variables
     * @return  string
     */
    protected static function capture($view_file, array $view_data)
    {
        // Extract all data into variables, EXTR_SKIP mean it won't override existing variables.
        extract($view_data, EXTR_SKIP);

        if ( Theme::$_global_data )
        {
            // Import the global view variables to local namespace
            extract(Theme::$_global_data, EXTR_SKIP);
        }

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
     *  $this->render($file);
     *
     * @param   string view filename
     * @return  the output of the view set.
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
     * Theme functions for HTML.
     *
     * @package     Annex
     * @category    Base
     * @author      Jiran Dowlati
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