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
        $left = Annex::render('annex/modules/status');
        $list = Model_Annex_Content::overview();
        $right = Theme::factory('views/content/model-list')->bind('data', $list);

        $this->template->main->content = Theme::factory('views/container/2col')
            ->set('class', 'three-to-one')
            ->set('left', $left)
            ->set('right', $right);
    }

    /**
     * Register Action
     *
     * @todo use a form generator built from the user model instead of hard coding fields
     */
    public function action_become_user()
    {
        $user = Brass::factory('brass_user', ['_id' => $this->request->param('id')])->load();

        Authenticate::instance()->complete_login($user);
    }
}