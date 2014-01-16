<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - Public Default Controller
 *
 * @package     Annex
 * @category    Public
 * @author      Clay McIlrath
 */
class Controller_Admin_Users extends Controller_Admin
{

    /**
     * Index Action
     */
    public function action_index()
    {
        $pagination = Model_Annex_Content::pagination('user');
        $per_page = isset($_GET['per_page']) ? (int) $_GET['per_page'] : 10;
        $offset = isset($_GET['page']) ? (int) $_GET['page'] * $per_page : 0;
        $left = Model_Annex_Content::show_list('user', 'views/content/model-sort-user');

        if ($pagination['pages'] > 1)
        {
            $left .= Theme::factory('views/blocks/ui/pagination')
                ->set('page', isset($_GET['page']) ? $_GET['page'] : 1)
                ->set('format', "/admin/users/?page=")
                ->set('data', $pagination);
        }

        $this->template->main->content = $left;
    }

    /**
     * Register Action
     *
     * @todo use a form generator built from the user model instead of hard coding fields
     */
    public function action_edit()
    {
        $user = Model_Annex_Account::find_user($this->request->param('id'));

        $username = $this->request->post('username');
        $password = $this->request->post('password');
        $password_confirm = $this->request->post('password_confirm');
        $email = $this->request->post('email');

        if ( $post = $this->request->post() )
        {
            $this->auto_render = FALSE;

            if ( Model_Annex_Account::update($post, $user->_id) )
            {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Account updated'
                ]);
            }
            else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'We could not update your account with that information'
                ]);
            }

            return;
        }

        $this->template->main->content = Theme::factory('views/forms/account/manage')
            ->set('method', 'POST')
            ->bind('user', $user);
    }
}