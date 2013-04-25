<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - Private Default Controller
 *
 * @package     Annex
 * @category    Private
 * @author      Clay McIlrath
 */
class Controller_Admin_Admin extends Controller_Admin
{
    public function action_index()
    {
        $status = Annex::render('annex/modules/status');

        $this->template->main->content = Theme::factory('views/container/2col')
            ->set('left', $status)
            ->set('right', 'right content');
    }
}