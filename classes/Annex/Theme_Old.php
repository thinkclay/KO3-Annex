<?php defined('SYSPATH') or die('No direct script access.');

class Annex_Theme
{
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