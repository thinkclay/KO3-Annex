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

        $path = Kohana::$config->load($module.'_annex.theme.scripts');

        // Find the file extension
        $ext = pathinfo($file, PATHINFO_EXTENSION);

        // Remove the extension from the filename
        $file = substr($file, 0, -(strlen($ext) + 1));

        // Get the server path to the file
        $file = Kohana::find_file($path, $file, $ext);

        if ($file)
        {
            // Check if the browser sent an "if-none-match: <etag>" header, and tell if the file hasn't changed
            $this->response->check_cache(sha1($this->request->uri()).filemtime($file), $this->request);

            // Send the file content as the response
            $this->response->body(file_get_contents($file));

            // Set the proper headers to allow caching
            $this->response->headers('content-type',  File::mime_by_ext($ext));
            $this->response->headers('last-modified', date('r', filemtime($file)));
        }
        else
        {
            $this->response->status(404);
            echo Console::output(array('Sorry, but this resource could not be found'), 'view');
        }
    }
}