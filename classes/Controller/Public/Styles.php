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
     *
     * @usage   /styles/annex/global.less which is /styles/<module>/<file>
     */
    public function action_index()
    {
        $this->auto_render = FALSE;

        $module = $this->request->param('module');
        $file = $this->request->param('file');

        if ( ! $path = Kohana::$config->load($module.'_annex.theme.styles'))
        {
            $path = Kohana::$config->load('theme_'.$module.'_annex.theme.styles');
        }

        // Find the file extension
        $ext = pathinfo($file, PATHINFO_EXTENSION);
        $file = pathinfo($file);

        // Get the server path to the file
        $file = Kohana::find_file($path, $file['filename'], $ext);

        if ($file)
        {
            if ($ext == 'less')
            {
                $ext = 'css';
                $less_file = Less::compile($file);
            }

            // Check if the browser sent an "if-none-match: <etag>" header, and tell if the file hasn't changed
            Controller::check_cache(sha1($this->request->uri()).filemtime($file), $this->request);

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