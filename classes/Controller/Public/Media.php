<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - Public Script Controller
 *
 * Manage script loading
 *
 * @package     Annex
 * @category    Public
 * @author      Clay McIlrath
 */
class Controller_Public_Media extends Controller_Public
{
    public $module = NULL;
    public $file = NULL;
    public $file_info = NULL;
    public $type = NULL;
    public $path = NULL;

    public function before()
    {
        parent::before();

        $this->auto_render = FALSE;

        $this->module = $this->request->param('module');
        $this->file = $this->request->param('file');
        $this->file_info = pathinfo($this->file);
        $this->type = $this->request->param('type');
    }

    /**
     * Scripts - Renders Javascript Paths
     */
    public function action_index()
    {
        $file = $path = FALSE;

        if ( ! $path = Kohana::$config->load($this->module.'_annex.theme.'.$this->type) )
        {
            $path = Kohana::$config->load('theme_'.$this->module.'_annex.'.$this->type);
        }

        if ( $path )
        {
            $path = 'themes'.DIRECTORY_SEPARATOR.$this->module.DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR;

            if ( $this->file_info['dirname'] != '.' )
            {
                $path = $path.$this->file_info['dirname'];
            }

            // Get the server path to the file
            $file = Kohana::find_file($path, $this->file_info['filename'], $this->file_info['extension']);
        }

        // If we dont have a path still, then our theme isnt a module,
        // and we need to just do some intelligent searching instead
        if ( ! $path )
        {
            $dir = new RecursiveDirectoryIterator(APPPATH);
            $iterator = new RecursiveIteratorIterator($dir);

            // Make sure the path does not contain "/.Trash*" folders and ends with .js
            $files = new RegexIterator($iterator, '#^(?:[A-Z]:)?(?:/(?!\.Trash)[^/]+)+/[^/]+\.js$#Di');

            foreach ($files as $f)
            {
                $regex = '/(themes\/'.$this->module.')'. // Match the theme path with the module name: themes/default
                    '.*('.
                    preg_replace('/\./', '\.', $this->file_info['filename']).'\.'.$this->file_info['extension']. // match every folder to our file
                    ')/i';

                if ( $match = preg_match($regex, $f) )
                {
                    $file = $f;
                }
            }
        }

        if ( $file )
        {
            // Check if the browser sent an "if-none-match: <etag>" header, and tell if the file hasn't changed
            Controller::check_cache(sha1($this->request->uri()).filemtime($file), $this->request);

            // Send the file content as the response
            $this->response->body(file_get_contents($file));

            // Set the proper headers to allow caching
            $this->response->headers('content-type',  File::mime_by_ext($this->file_info['extension']));
            $this->response->headers('last-modified', date('r', filemtime($file)));
        }
        else
        {
            $this->response->status(404);
            echo Console::output(array('Sorry, but this resource could not be found'), 'view');
        }
    }
}