<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/*
 * Controller principal INDEX
 */

class Inicio extends Frontend_Controller
{

    function __construct()
    {
        parent::__construct();

        //        $this->output->enable_profiler(true);

        /*
         * Ativar função em caso de site multilingue
         * Ver core/Multilang_Controller.
         */
        //        $this->setLang();
    }

    function index()
    {

            // shortcodes devem ser inicializados primeiro
            $this->cms_conteudo->shortcode_reg(array('slide'));

            // breadcrumb
            // $this->load->library('breadcrumb'); // autoload




            // retorna dados da tabela cms_conteudo parseado
            $this->pagina = $this->cms_conteudo->get_page('home');

            if($this->pagina === FALSE){
                redirect();
            }

            // retorna galeria
            $this->pagina['galeria'] = $this->cms_conteudo->get_page_gallery();
            // retorna os arquivos anexos
            $this->pagina['anexos'] = $this->cms_conteudo->get_page_attachments();
            // retorna dados do módulo
            $this->pagina['modulo'] = $this->cms_conteudo->set_get_modulo();
            // retorna as páginas filhas
            $this->pagina['children'] = $this->cms_conteudo->get_children(true, array('html' => true));
            // retorna as páginas, ou grupos a que pertencem para breadcrumb
            $this->pagina['hierarchy'] = $this->cms_conteudo->get_hierarchy();



            // breadcrumb
            $this->breadcrumb->add($this->pagina['hierarchy']);

            $view['post'] = '';

            $this->title = $this->pagina['titulo'];
            $this->corpo = $this->load->view('page', $view, true);

            $this->templateRender();


    }


    // -------------------------------------------------------------------------
    /**
     * Redireciona para endereço do banner e soma 1 click.
     * Caso não exita, redireciona para home.
     * @param int $banner_id
     */
    public function redirect($banner_id)
    {

        $this->load->library('cms_banner');
        if (!$this->cms_banner->redirect($banner_id)) {
            redirect('');
        }

    }


    public function user()
    {

        $this->load->library(array('cms_usuario'));

        // recupera infos da inscrição
        $ins = $this->cms_usuario->get_inscription(2);

        // inscreve usuário no conteúdo
        //        $this->cms_usuario->inscribe(array(
        //            'conteudo_id' => 10,
        //            'comentario'  => 'novo comment',
        //            'user_id'     => 8,
        //            'status'      => 1
        //        ), false, true);

        $view['user'] = $this->cms_usuario->get(9);

        // insere novo usuário
        //        $this->cms_usuario->insert(array(
        //           'nome' => 'meu nome',
        //            'email' => 'email@email.com.br',
        //            'status' => 2
        //        ));

        # insere ou atualiza
        //        $user_id = $this->cms_usuario->insert_update(array(
        //           'nome' => 'Nome do usuário',
        //            'email' => 'bruno@brunobarros.com',
        //            'status' => 1
        //        ), 'email');

        //mybug($ins);

        $this->title = $view['user']['nome'];
        $this->corpo = $this->load->view('site_add/user', $view, true);

        $this->templateRender();

    }

}