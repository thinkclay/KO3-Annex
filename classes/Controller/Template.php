<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Private - wrapper for the private pseudo namespace
 *
 * @package     Annex
 * @category    Private
 * @author      Clay McIlrath
 */
abstract class Controller_Template extends Controller
{
    public $template = 'template';

    public static $user = FALSE;

    public $navigation = [];

    public $seo;

    /**
     * @var  boolean  auto render template
     **/
    public $auto_render = TRUE;

    /**
     * Loads the template [View] object.
     */
    public function before()
    {
        parent::before();

        // Page SEO initialization
        $this->seo = new stdClass();
        $this->seo->title = "";
        $this->seo->keywords = "";
        $this->seo->description = "";

        $this->authorize = Authorize::instance();
        static::$user = Authorize::instance()->get_user();

        if ( $this->auto_render === TRUE )
        {
            // Load the template
            $this->template = Theme::factory($this->template);

            $role = (static::$user) ? static::$user->role : 'guest';
            $this->navigation = Kohana::$config->load("navigation.$role");
        }
    }

    /**
     * Assigns the template [View] as the request response.
     */
    public function after()
    {
        if ($this->auto_render === TRUE)
        {
            $this->template->bind_global('seo', $this->seo);
            $this->response->body($this->template->render());
        }

        parent::after();
    }

}