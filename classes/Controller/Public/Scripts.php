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
class Controller_Public_Scripts extends Controller_Public
{
    /**
     * Scripts - Renders Javascript Paths
     */
    public function action_index()
    {
        $this->auto_render = FALSE;

        $module = $this->request->param('module');
        $file = $this->request->param('file');
        $file_info = pathinfo($file);

        if ( ! $path = Kohana::$config->load($module.'_annex.theme.scripts') )
        {
            $path = Kohana::$config->load('theme_'.$module.'_annex.scripts');
        }

        // If we dont have a path still, then our theme isnt a module,
        // and we need to just do some intelligent searching instead
        if ( $path )
        {
            // Get the server path to the file
            $file = Kohana::find_file($path, $file_info['filename'], $file_info['extension']);
        }
        else
        {
            $dir = new RecursiveDirectoryIterator(APPPATH);
            $iterator = new RecursiveIteratorIterator($dir);

            // Make sure the path does not contain "/.Trash*" folders and ends with .js
            $files = new RegexIterator($iterator, '#^(?:[A-Z]:)?(?:/(?!\.Trash)[^/]+)+/[^/]+\.js$#Di');

            foreach ($files as $f)
            {
                $regex = '/(themes\/'.$module.')'. // Match the theme path with the module name: themes/default
                    '.*('.
                    $file_info['filename'].'\.'.$file_info['extension']. // match every folder to our file
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
            $this->response->headers('content-type',  File::mime_by_ext($file_info['extension']));
            $this->response->headers('last-modified', date('r', filemtime($file)));
        }
        else
        {
            $this->response->status(404);
            echo Console::output(array('Sorry, but this resource could not be found'), 'view');
        }
    }
}