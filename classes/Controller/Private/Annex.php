<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - Private Default Controller
 *
 * @package     Annex
 * @category    Private
 * @author      Clay McIlrath
 */
class Controller_Private_Annex extends Controller_Private
{
    public function action_index()
    {
        $status = Annex::render('annex/modules/status');

        $this->template->main->content = View::factory('annex/index')
            ->bind('content', $status);
    }
}