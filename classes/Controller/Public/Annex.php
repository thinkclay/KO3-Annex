<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Annex - Public Default Controller
 *
 * @package		Annex
 * @category	Public
 * @author		Clay McIlrath
 */
class Controller_Public_Annex extends Controller_Public
{
	public function before()
	{
		parent::before();

		$this->template->styles = array(
			// "http://twitter.github.com/bootstrap/assets/css/bootstrap.css" => "all",
			// "http://twitter.github.com/bootstrap/assets/css/bootstrap-responsive.css" => "screen"
		);
		$this->template->scripts = array(
			// "http://code.jquery.com/jquery-1.6.4.min.js",
			// "http://code.jquery.com/mobile/1.1.0/jquery.mobile-1.1.0.min.js"
		);

		$this->template->head = '<!--[if lt IE 9]><script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script><![endif]-->';
	}

	/**
	 * Styles - Renders CSS and LESS stylesheet
	 *
	 * @usage	/styles/annex/global.less which is /styles/<module>/<file>
	 */
	public function action_styles()
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

	/**
	 * Scripts - Renders Javascript Paths
	 */
	public function action_scripts()
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

	public function action_register()
	{
		$role = Request::$current->param('id');

        if ($_POST)
        {
            $check = Model_User::create($_POST, 'user');
            if (is_array($check))
            {
                $check['success'] = 0;
                echo json_encode($check);
            }
            else if ($check == false)
            {
                echo json_encode(array('success' => 0, 'message'=>'User creation failed'));
            }
            else if ($check == true)
            {
                echo json_encode(array('success' => 1, 'message'=>'An email has been sent to '.$_POST['email'].'<br /> Follow instructions to complete registration.'));
            }

		}

		$this->template->main->content = Theme::view('default/views/forms/register');
	}

	/**
	 * Login Action
	 *
	 * @todo Make the login checks more robust (aka: user doesnt exist)
	 */
	public function action_login()
	{
		if ($_POST)
		{
			$post = Validation::factory($_POST)
				->rule('username', 'not_empty')
				->rule('password', 'not_empty');


			if ($post->check())
			{
				// Attempt to login user
				$remember = array_key_exists('remember', $this->request->post()) ? (bool) $this->request->post('remember') : FALSE;

				echo $this->request->post('username').'<br />';
				echo $this->request->post('password').'<br />';
				var_dump($remember);

				$user = A1::instance()->login($this->request->post('username'), $this->request->post('password'), $remember);

				if ($user AND $user->role != 'pending')
				{
					// Redirect to account/index if login passed
					print_r('success');
				}
				else
				{
					$message = 'Invalid username or password';
					print_r($message);
				}
			}
			else
			{
				$message = 'Please enter a username and password';
				print_r($message);
			}
		}

		$this->template->main->content = Theme::view('default/views/forms/login');
	}

	/**
     * Function for user logout
     *
     * @return  void
     */
    public function action_logout()
    {
        A1::instance()->logout(true);
        $this->redirect();
    }
}