<?php

class Api_avaliacoes extends Api_Controller {


    public function __construct()
    {
    }

    public function all(){

        echo $this->responseOk(array(), 'Mensagem');
    }
}