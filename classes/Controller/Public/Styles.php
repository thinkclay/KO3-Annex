<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - Public Styles Controller
 *
 * Manages stylesheet loading
 *
 * @package     Annex
 * @category    Public
 * @author      Clay McIlrath
 */
class Controller_Public_Styles extends Controller_Public
{
    /**
     * Styles - Renders CSS and LESS stylesheet
     */
    public function action_index()
    {
        $this->auto_render = FALSE;
        $file = $this->request->param('file');
        $path = Theme::get_setting('styles');
        $file = pathinfo($file);

        // Get the server path to the file
        if ( ! $file_loaded = Kohana::find_file('themes/'.Theme::$_theme_name, $path.'/'.$file['filename'], $file['extension']) )
        {
            $file_loaded = Kohana::find_file('themes/default', $path.'/'.$file['filename'], $file['extension']);
        }

        if ( $file_loaded )
        {
            // Check if the browser sent an "if-none-match: <etag>" header, and tell if the file hasn't changed
            Controller::check_cache(sha1($this->request->uri()).filemtime($file_loaded), $this->request);

            // Send the file content as the response
            $this->response->body(file_get_contents($file_loaded));

            // Set the proper headers to allow caching
            $this->response->headers('content-type',  File::mime_by_ext($file['extension']));
            $this->response->headers('last-modified', date('r', filemtime($file_loaded)));
        }
        else
        {
            $this->response->status(404);
            echo Console::output(array('Sorry, but this resource could not be found'), 'view');
        }
    }
}