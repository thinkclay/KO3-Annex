<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Acts as an object wrapper for HTML pages with embedded PHP, called "themes".
 * Variables can be assigned with the theme object and referenced locally within
 * the theme view.
 *
 * @package     Annex
 * @category    Base
 * @author      Clay McIlrath
 */
class Annex_Theme
{
    // Array of global variables
    protected static $_global_data = [];
    public static $_theme_name = 'default';

    /**
     * Returns a new Theme object. If you do not define the "file" parameter,
     * you must call [Theme::set_filename].
     *
     *  $view = Theme::view($file);
     *
     * @param   string  view filename
     * @param   array   array of values
     * @return  Theme
     */
    public static function view($file = NULL, array $data = NULL)
    {
        return new Theme($file, $data);
    }

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
    protected static function capture($annex_view_filename, array $annex_view_data)
    {
        // Import the view variables to local namespace
        extract($annex_view_data, EXTR_SKIP);

        if ( Theme::$_global_data )
        {
            // Import the global view variables to local namespace
            extract(Theme::$_global_data, EXTR_SKIP);
        }

        // Capture the view output
        ob_start();

        try
        {
            // Load the view within the current scope
            include $annex_view_filename;
        }
        catch (Exception $e)
        {
            // Delete the output buffer
            ob_end_clean();

            // Re-throw the exception
            throw $e;
        }

        // Get the captured output and close the buffer
        return ob_get_clean();
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

    // View filename
    protected $_file;

    // Array of local variables
    protected $_data = array();

    /**
     * Sets the initial view filename and local data. Views should almost
     * always only be created using [Theme::view].
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
        if ( $theme = Kohana::$config->load('annex_annex.theme.name') )
            static::$_theme_name = $theme;

        if ( $file !== NULL )
        {
            $this->set_filename($theme.'/'.$file);
        }

        if ( $data !== NULL )
        {
            // Add the values to the current data
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
     *  $view->foo = 'something';
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
     *  isset($view->foo);
     *
     * [!!] `NULL` variables are not considered to be set by [isset](http://php.net/isset).
     *
     * @param   string  variable name
     * @return  boolean
     */
    public function __isset($key)
    {
        return (isset($this->_data[$key]) OR isset(Theme::$_global_data[$key]));
    }

    /**
     * Magic method, unsets a given variable.
     *
     *  unset($view->foo);
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
     * Sets the view filename.
     *
     *  $view->set_filename($file);
     *
     * @param   string  view filename
     * @return  Theme
     * @throws  Theme_Exception
     */
    public function set_filename($file)
    {
        if (($path = Kohana::find_file('themes', $file)) === FALSE)
        {
            throw new Annex_Exception('The requested view :file could not be found', [
                ':file' => $file,
            ]);
        }

        // Store the file path locally
        $this->_file = $path;

        return $this;
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
     * Renders the view object to a string. Global and local data are merged
     * and extracted to create local variables within the view file.
     *
     *  $output = $view->render();
     *
     * [!!] Global variables with the same key name as local variables will be
     * overwritten by the local variable.
     *
     * @param   string  view filename
     * @return  string
     * @throws  Theme_Exception
     * @uses    Theme::capture
     */
    public function render($file = NULL)
    {
        if ( $file !== NULL )
        {
            $this->set_filename($file);
        }

        if ( empty($this->_file) )
        {
            throw new Theme_Exception('You must set the file to use within your view before rendering');
        }

        // Combine local and global data and capture the output
        return Theme::capture($this->_file, $this->_data);
    }
}