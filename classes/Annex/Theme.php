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
    public function __construct($file = NULL, array $data = NULL, $find_only = FALSE)
    {
        // Set theme to whatever config file has for theme name.
        // The theme uses directories and loads the namespace accordingly
        $namespace = strtolower(Request::$current->directory());

        if ( $theme = Kohana::$config->load("annex_annex.theme.{$namespace}") )
        {
            static::$_theme_name = $theme;
        }

        if ( $find_only == TRUE )
            return $this->_file = $this->find_file($theme, $file);

        if ( $file !== NULL )
        {
            // Sets file name
            $this->set_filename($theme, $file);
        }

        if ( $data !== NULL )
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
     *     $view = Theme::factory($file);
     *
     * @param   string  view filename
     * @param   array   array of values
     * @return  Theme
     */
    public static function factory($file = NULL, array $data = NULL, $find_only = FALSE)
    {
        $theme = new Theme($file, $data, $find_only);

        if ( $find_only )
            return $theme->_file;
        else
            return $theme;
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
        $theme = is_string($theme) ? $theme : $theme['public'];
        $file = is_string($file) ? $file : $file->_file;

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
            throw new Annex_Exception("Couldn't set file {$file} beacuse file could not be found in the {$theme} or default theme folders");
        }
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
    public function find_file($theme, $file)
    {
        if ( $p = Kohana::find_file('themes', $theme . '/' . $file) )
            return $p;
        else if ( $p = Kohana::find_file('themes', 'default/' . $file) )
            return $p;
        else
            return FALSE;
    }

    public static function get_setting($setting_name)
    {
        $config = Kohana::$config->load('theme_'.static::$_theme_name.'_annex');

        return $config->$setting_name;
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
     * I process the PHP vars in the file first, and then run the view through the mustache rendering engine
     *
     *  $this->render($file);
     *
     * @param   string view filename
     * @return  the output of the view set.
     */
    public function render($file = NULL)
    {
        if ( $file !== NULL )
        {
            $this->set_filename($file);
        }

        if (empty($this->_file))
        {
            echo 'You must set the file to use within your view before rendering';
        }

        $processed_file = Theme::capture($this->_file, $this->_data);
        $cms = isset($this->_data['cms']) ? $this->_data['cms'] : Model_Brass_Page::cms();
        $m = new Mustache_Engine;

        return $m->render($processed_file, $cms);
    }

    public static function style($filename)
    {
        $path = Theme::get_setting('styles');
        $file = pathinfo($filename);

        // Get the server path to the file
        if ( ! $file_loaded = Kohana::find_file('themes/'.Theme::$_theme_name, $path.'/'.$file['filename'], $file['extension']) )
        {
            $file_loaded = Kohana::find_file('themes/default', $path.'/'.$file['filename'], $file['extension']);
        }

        return '/'.Less::factory($file_loaded);
    }

    public static function glob_recursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);

        foreach (glob(dirname($pattern).'/*', GLOB_ONLYDIR|GLOB_NOSORT) as $dir)
        {
            $files = array_merge($files, static::glob_recursive($dir.'/'.basename($pattern), $flags));
        }

        return $files;
    }
}