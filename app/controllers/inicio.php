<?php
if (!defined('BASEPATH'))
{
	exit('No direct script access allowed');
}
use Src\Core\Commanding\CommandBus;

/*
 * Controller principal INDEX
 */

class Inicio extends Frontend_Controller
{


	/**
	 * @var
	 */
	public $msg_type;
	/**
	 * @var
	 */
	public $msg;

	/**
	 * @var CommandBus
	 */
	private $bus;

	function __construct()
	{
		parent::__construct();

		$this->bus = CommandBus::make();

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

		if ($this->pagina === false)
		{
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


	public function programacao()
	{
		$schedule      = new \Gestalt\Trabalho\Schedule();
		$v['schedule'] = $schedule->all();

		//		dd($v['schedule'][0]);

		$this->setNewScript(array('velocity.min', 'page.programacao'));
		$this->setNewEstyle(array('event-detail'));
		$this->title = "Programação";
		$this->corpo = $this->load->view('programacao', $v, true);

		$this->templateRender('template/full');
	}

	public function almoco()
	{
		$this->message();

		$this->json_vars('form', array('returnType' => $this->msg_type));

		$this->setNewScript(array('jquery.validate'));

		$this->title = "Almoço";
		$this->corpo = $this->load->view('almoco', '', true);

		$this->templateRender();
	}


	public function post_almoco()
	{
		// -- carrega classes -- //
		$this->load->library(array('form_validation'));

		/*
		 * Validação
		 */
		$this->form_validation->set_rules('nome', 'Nome', 'trim|required');
		$this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email');
		$this->form_validation->set_rules('telefone', 'Telefone', 'trim|required|min_length[12]');
		$this->form_validation->set_rules('pagamento', 'Forma de pagamento', 'trim|required');

		//        $this->form_validation->set_rules('anexo', 'Anexo', 'callback_validate_attached');

		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');

		/*
		 * Não validou
		 */
		if ($this->form_validation->run() == false)
		{

			// salva erro na session
			$this->phpsess->save('msg_type', 'error');
			$this->phpsess->save('msg', 'Campos incorretos.');
			$this->almoco();
		}
		/*
		 * OK, validou
		 */
		else
		{

			$ret = $this->bus->execute(new \Gestalt\Commands\LunchSubscriptionCommand(
				$this->input->post('nome'),
				$this->input->post('email'),
				$this->input->post('telefone'),
				$this->input->post('pagamento')
			));

			if ($ret)
			{
				$this->phpsess->save('msg_type', 'success');
				$this->phpsess->save('msg', 'Inscrição enviada com sucesso.');
			}
			else
			{
				$this->phpsess->save('msg_type', 'error');
				$this->phpsess->save('msg', 'Erro ao enviar inscrição.');
			}

			redirect('informacoes/almoco');
		}
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
		if (!$this->cms_banner->redirect($banner_id))
		{
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


	/**
	 * set up sessions vars
	 */
	private function message()
	{
		$this->msg_type = $this->phpsess->get('msg_type');
		$this->msg      = $this->phpsess->get('msg');
		$this->phpsess->save('msg_type', null);
		$this->phpsess->save('msg', null);

	}

}