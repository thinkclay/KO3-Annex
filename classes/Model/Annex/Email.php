<?php defined('SYSPATH') or die('No direct script access.');

class Model_Annex_Email extends Model
{
    public $config = FALSE;
    public $key_as_array = [];

    public function __construct($config)
    {
        $this->config = Kohana::$config->load($config);
    }

    public static function factory($config = 'emails')
    {
        return new Model_Annex_Email($config);
    }

    /**
     * Send Mail
     *
     * This function will send mail by first looking to see if there is a email template
     * with the key matching the key set here. If it is found, it will render the mail template
     * as a mustache template and send it to the array of users it should go to
     */
    public function send($key = NULL, $to = 'system', $data = NULL)
    {
        // Swap the "to" field with config value if there if key exists
        // Then make sure it's a mail friendly string
        $to = $this->config->get($to) ? $this->config->get($to) : $to;
        $to = is_array($to) ? implode(', ', $to) : $to;

        $file = preg_replace('/\./', DIRECTORY_SEPARATOR, $key);
        $conf = $this->config->get('templates')[$key];

        // First try and find an admin generated email template, as it is most current/relevant
        if ( $doc = BrassDB::instance()->find_one('emails', ['key' => $key]) )
        {
            $m = new Mustache_Engine;
            $subject = $doc['subject'];
            $body = $m->render($doc['body'], $data);
        }

        // If we don't have an admin created email template, lets see if we can find a file
        else if ( $view = Theme::factory($file, ['cms' => $data]) )
        {
            $subject = $conf['subject'];
            $body = $view;
        }

        // Otherwise, let's send an email to the admin to get them to come in and create a template
        else
        {
            throw new Annex_Exception('tell the admin to get a template here!');
        }

        $template = Theme::factory($this->config->get('wrapper'))
            ->bind('subject', $conf['subject'])
            ->bind('body', $body);

        return $this->_send_mail($to, $subject, $template);
    }

    private function _send_mail($to, $subject, $message)
    {
        $headers = 'MIME-Version: 1.0'."\r\n".
                'Content-type: text/html; charset=iso-8859-1'."\r\n".
                'From: '.$this->config['system']."\r\n".
                'Reply-To: '.$this->config['system']."\r\n";

        if ( mail($to, $subject, $message, $headers) )
            return TRUE;
        else
            return FALSE;
    }

    public function error_report($error, $data)
    {
        $headers = 'MIME-Version: 1.0'."\r\n".
                'Content-type: text/html; charset=iso-8859-1'."\r\n".
                'From: '.$this->config['system']."\r\n".
                'Reply-To: '.$this->config['system']."\r\n";

        if ( mail($this->config['system'], 'Error: '.$error, '<pre>'.var_export($data, TRUE).'</pre>', $headers) )
            return TRUE;
        else
            return FALSE;
    }
}