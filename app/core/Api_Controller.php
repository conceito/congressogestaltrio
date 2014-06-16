<?php

class Api_Controller extends MY_Controller
{


    public function __construct()
    {

    }


    public function responseOk($data, $msg = '')
    {
        return $this->response(false, $msg, $data);
    }

    public function responseError($msg = '')
    {
        return $this->response(true, $msg, null);
    }

    public function response($error = false, $msg = '', $data = array())
    {
        return json_encode(array(
            'error' => $error,
            'msg'   => $msg,
            'data'  => $data
        ));
    }
}