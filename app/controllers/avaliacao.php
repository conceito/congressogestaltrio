<?php

/**
 * Class Avaliacao
 *
 * controller para páginas de avaliação de trabalhos
 *
 */
class Avaliacao extends Frontend_Controller
{

	/**
	 * @var
	 */
	public $msg_type;
	/**
	 * @var
	 */
	public $msg;


	public function __construct()
	{
		parent::__construct();
		$this->load->model('cms/avaliador_model', 'avaliador');
		$this->load->model('cms/avaliacao_model', 'avaliacao');
		$this->load->model('cms/trabalhos_model', 'trabalho');

		Console::log($this->avaliador->gerLoggedUser());
	}

	/**
	 * list of jobs of this appraiser
	 */
	public function index()
	{
		$user = $this->checkAuthentication();
		$this->message();
		$this->setNewEstyle(array('avaliacao-frontend'));

		$v['evaluations'] = $this->avaliacao->allByUser($user['id'], array('job' => true));

//		dd($v['evaluations']);

		$this->title = 'Avaliações';
		$this->corpo = $this->load->view('avaliacao_trabalho/avaliacoes_lista', $v, true);

		$this->templateRender('avaliacao_trabalho/template');
	}

	/**
	 * show job with form to evaluation
	 * ? side list of jobs
	 * @param $id
	 */
	public function trabalho($id)
	{
		$this->checkAuthentication();
		$this->message();
		$this->setNewPlugin(array('angular'));
		$this->setNewEstyle(array('avaliacao-frontend'));
		$this->setNewScript(array('jquery.sticky', 'page-avaliacao'));
		$this->json_vars('avaliacao', array('id' => $id));

		$v['evaluation'] = $this->avaliacao->find($id, array('job' => true));

		if($v['evaluation']['status'] != 2)
		{
			$this->phpsess->save('msg_type', 'error');
			$this->phpsess->save('msg', 'Este trabalho já foi avaliado.');
			redirect('avaliacao/index');
		}
//		dd($v['evaluation']);

		$this->title = 'Avaliando trabalho';
		$this->corpo = $this->load->view('avaliacao_trabalho/avaliando', $v, true);

		$this->templateRender('avaliacao_trabalho/template');
	}

	public function login()
	{
		$this->message();

		$this->setNewScript(array('jquery.validate', 'page.login'));

		$v['evaluations'] = '';

		$this->title = 'Login de avaliador';
		$this->corpo = $this->load->view('avaliacao_trabalho/login', $v, true);

		$this->templateRender();
	}

	public function logout()
	{
		$this->avaliador->doLogout();
		redirect('avaliacao/login');
	}


	/**
	 * receive POST data to validate user
	 */
	public function post_login()
	{
		$this->load->library(array('form_validation'));

		$this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email');
		$this->form_validation->set_rules('senha', 'Senha', 'trim|required|min_length[6]');

		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');

		/*
		 * Não validou
		 */
		if ($this->form_validation->run() == false)
		{

			// salva erro na session
			$this->phpsess->save('msg_type', 'error');
			$this->phpsess->save('msg', 'Campos incorretos.');
			$this->login();
		} /*
         * OK, validou
         */
		else
		{
			try
			{
				$ret = $this->avaliador->doLogin($this->input->post('email'), $this->input->post('senha'));
				redirect("avaliacao/index");
			} catch (Exception $e)
			{
				$this->phpsess->save('msg_type', 'error');
				$this->phpsess->save('msg', $e->getMessage());
				redirect("avaliacao/login");
			}

		}
	}

	public function checkAuthentication()
	{
		if ($user = $this->avaliador->gerLoggedUser())
		{
			return $user;
		}

		$this->phpsess->save('msg_type', 'loggedout');
		$this->phpsess->save('msg', 'Você não tem permissão para acessar esta página.');

		redirect('avaliacao/login');
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