<?php

namespace Gestalt\Notifications;

class EmailNotification
{

    protected $ci;
    protected $mailer;
    protected $user = null;
    protected $task = null;

    protected $isDebugging = false;

    function __construct()
    {
        $this->ci = & get_instance();

        $this->ci->load->library('e_mail');
        $this->mailer = $this->ci->e_mail;
    }

    /**
     * set the debug status
     *
     * @param bool $is
     */
    public function debug($is = true)
    {
        $this->isDebugging = $is;
    }

    /**
     * @param mixed $task
     */
    public function setTask($task)
    {
        $this->task = $task;
    }

    /**
     * @return mixed
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @param mixed $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getUser()
    {
        return $this->user;
    }

    public function instance()
    {
        return $this->mailer;
    }


    /**
     * send message with new password
     *
     * @param array $user
     * @param string $newPassword
     * @return mixed
     * @throws \Exception
     */
    public function passwordRenewed($user, $newPassword = '')
    {
        if (!is_array($user) || strlen($newPassword) == 0)
        {
            throw new \Exception('Usuário ou senha não foram encontrados.');
        }

        $html = $this->composeMessageWithBody($this->composeEmailRenewerBody($user, $newPassword));

        $subject   = "Redefinição de senha";
        $menHTML   = $html;
        $menTXT    = strip_tags($html);
        $sendEmail = $this->ci->config->item('email1');
        $sendName  = $this->ci->config->item('title');

        return $this->mailer->envia($user['email'], $user['nome'], $subject, $menHTML, $menTXT, $sendEmail, $sendName);
    }

    /**
     * send emails about new task
     * @see getUser()
     * @see getTask()
     * @throws \Exception
     * @return bool
     */
    public function sendTaskReceived()
    {
        if (!$this->user || !$this->task)
        {
            throw new \Exception('Usuário ou trabalho não foram encontrados');
        }
        // compose message
        $html = $this->composeMessageWithBody($this->composeNewTaskBody());

        $user      = $this->getUser();
        $subject   = "Trabalho enviado por: {$user['nome']}";
        $menHTML   = $html;
        $menTXT    = strip_tags($html);
        $sendEmail = $this->ci->config->item('email1');
        $sendName  = $this->ci->config->item('title');

        /**
         * if debugging overwrite
         */
        if ($this->isDebugging)
        {
            $subject   = '[debug] ' . $subject;
            $user      = array(
                'nome'  => 'degugger',
                'email' => $this->ci->config->item('email_debug')
            );
            $sendEmail = $this->ci->config->item('email_debug');
        }

        //
        //        echo $html;
        //        exit;

        $ret = $this->mailer->envia($user['email'], $user['nome'], $subject, $menHTML, $menTXT, $sendEmail, $sendName);

        /*
         * admin copy
         */
        $this->mailer->envia($sendEmail, $sendName, $subject, $menHTML, $menTXT, $user['email'], $user['nome']);

        return $ret;

    }



    /**
     * send emails about job updated by users
     *
     * @see getUser()
     * @see getTask()
     * @throws \Exception
     * @return bool
     */
    public function sendJobUpdated()
    {
        if (!$this->user || !$this->task)
        {
            throw new \Exception('Usuário ou trabalho não foram encontrados');
        }
        // compose message
        $html = $this->composeMessageWithBody($this->composeNewTaskBody());

        $user      = $this->getUser();
        $subject   = "Trabalho enviado por: {$user['nome']}";
        $menHTML   = $html;
        $menTXT    = strip_tags($html);
        $sendEmail = $this->ci->config->item('email1');
        $sendName  = $this->ci->config->item('title');

        /**
         * if debugging overwrite
         */
        if ($this->isDebugging)
        {
            $subject   = '[debug] ' . $subject;
            $user      = array(
                'nome'  => 'degugger',
                'email' => $this->ci->config->item('email_debug')
            );
            $sendEmail = $this->ci->config->item('email_debug');
        }

        //
        //        echo $html;
        //        exit;

        $ret = $this->mailer->envia($user['email'], $user['nome'], $subject, $menHTML, $menTXT, $sendEmail, $sendName);

        /*
         * admin copy
         */
        $this->mailer->envia($sendEmail, $sendName, $subject, $menHTML, $menTXT, $user['email'], $user['nome']);

        return $ret;

    }

    /**
     * compose the body of new task message
     */
    public function composeEmailRenewerBody($user, $newPassword)
    {
        $v['user']        = $user;
        $v['newPassword'] = $newPassword;
        $v['emailRespond'] = $this->ci->config->item('email1');

        return $this->ci->load->view('email/password_renew', $v, true);
    }

    /**
     * compose the body of new task message
     */
    public function composeNewTaskBody()
    {
        $v['emailRespond'] = $this->ci->config->item('email1');
        $v['user']         = $this->getUser();
        $v['task']         = $this->getTask();

        return $this->ci->load->view('email/new_task', $v, true);
    }

    /**
     * compose base e-mail HTML
     */
    public function composeMessageWithBody($body = '')
    {
        $v['body'] = $body;

        return $this->ci->load->view('template/email', $v, true);
    }
}