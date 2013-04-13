<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - Public Images Controller
 *
 * Manages image loading
 *
 * @package     Annex
 * @category    Public
 * @author      Clay McIlrath
 */
class Controller_Public_Images extends Controller_Public
{
    public function action_index()
    {
        $this->auto_render = FALSE;

        $module = $this->request->param('module');
        $file = $this->request->param('file');

        if ( ! $path = Kohana::$config->load($module.'_annex.theme.images') )
        {
            $path = Kohana::$config->load('theme_'.$module.'_annex.images');
        }

        // Find the file extension
        $file = pathinfo($file);
        $path .= '/'.$file['dirname'];

        // Get the server path to the file
        $loaded_file = Kohana::find_file($path, $file['filename'], $file['extension']);

        if ( $file )
        {
            // Check if the browser sent an "if-none-match: <etag>" header, and tell if the file hasn't changed
            Controller::check_cache(sha1($this->request->uri()).filemtime($loaded_file), $this->request);

            // Send the file content as the response
            $this->response->body(file_get_contents($loaded_file));

            // Set the proper headers to allow caching
            $this->response->headers('content-type',  File::mime_by_ext($file['extension']));
            $this->response->headers('last-modified', date('r', filemtime($loaded_file)));
        }
        else
        {
            $this->response->status(404);
            echo Console::output(array('Sorry, but this resource could not be found'), 'view');
        }
    }
}