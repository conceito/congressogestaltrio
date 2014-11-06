<?php
use Gestalt\Congresso5;

class Usuario extends Frontend_Controller
{

	/**
	 * @var
	 */
	public $msg_type;
	/**
	 * @var
	 */
	public $msg;


	public $congresso;


	function __construct()
	{
		parent::__construct();

		$this->load->model('usuario_m', 'usuario');

		$this->congresso = new Congresso5();

		Console::log($this->phpsess->get(null, 'user'));

	}

	/**
	 * user home page, list of options
	 */
	public function index()
	{
		$this->checkAuthentication('usuario/index');

		$v['user'] = $this->usuario->get_session();

		$this->title = 'Página do usuário';
		$this->corpo = $this->load->view('usuario/index', $v, true);

		$this->templateRender();
	}


	/**
	 * login form
	 */
	public function login()
	{
		$this->message();

		$this->setNewScript(array('jquery.validate', 'page.login'));

		$v['redirect'] = $this->usuario->getRedirectUrl();

		$this->title = 'Login de usuário';
		$this->corpo = $this->load->view('login_form', $v, true);

		$this->templateRender();
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

			$ret = $this->usuario->checkValidLogin($this->input->post());

			//            dd($ret);

			if (!$ret)
			{
				$this->phpsess->save('msg_type', 'error');
				$this->phpsess->save('msg', 'E-mail ou senha não conferem.');
				redirect("usuario/login");
			}
			else
			{
				if ($this->input->post('redirect') && strlen($this->input->post('redirect')))
				{
					redirect($this->input->post('redirect'));
				}
				else
				{
					redirect("usuario/index");
				}

			}

		}
	}

	public function passwordrenew()
	{
		$this->load->model('inscricao_m', 'inscricao');
		$email = $this->input->post('email');
		echo $this->inscricao->renewPasswordForUserEmail($email);

	}

	public function logout()
	{
		$this->cms_usuario->do_logout();
		redirect('usuario/login');
	}


	/**
	 * list of jobs
	 */
	public function trabalhos()
	{
		$this->checkAuthentication('usuario/trabalhos');

		$this->load->model('cms/avaliador_model', 'avaliador');
		$this->load->model('cms/avaliacao_model', 'avaliacao');
		$this->load->model('cms/trabalhos_model', 'trabalho');

		//        $t = new \Gestalt\Trabalho\Trabalho();
		//        dd($t->notifyNewTrabalho(232, false));

		$this->message();
		$this->setNewEstyle(array('avaliacao-frontend'));

		$v['user']        = $this->usuario->get_session();
		$v['jobs'] = $this->trabalho->allByAuthor($v['user']['id'], array('job' => true));

//		        dd($v['jobs']);



		$this->title = 'Meus trabalhos';
		$this->corpo = $this->load->view('usuario/trabalhos', $v, true);

		$this->templateRender();
	}

	/**
	 * form to edit a job
	 */
	public function trabalho($id)
	{
		$this->checkAuthentication('usuario/trabalho/' . $id);

		$this->load->model('cms/avaliador_model', 'avaliador');
		$this->load->model('cms/avaliacao_model', 'avaliacao');
		$this->load->model('cms/trabalhos_model', 'trabalho');

		$this->message();

		// libs\tiny_mce356
		//        $this->setNewJquery(array('tiny_mce356' => 'jquery.tinymce'));
		//        $this->setNewScript(array('jquery.tinymce'), 'libs');
		$this->setNewScript(array('jquery.validate', 'page.trabalho'));

		$v['job'] = $this->trabalho->find($id);
		$v['eixo_tematico_id'] = get_meta($v['job']['metas'], 'eixo_tematico', null, true);
		$v['modalidade_id'] = get_meta($v['job']['metas'], 'modalidade', null, true);

		$v['job']['autores'] = unserialize(get_meta($v['job']['metas'], 'authors', null, true));
//		         dd($v['job']);
		$v['user']        = $this->usuario->get_session();
		$v['temas']       = $this->congresso->allTemas();
		$v['modalidades'] = $this->congresso->allModalidades();
		$v['canSubmit']   = $this->usuario->hasSpecialCredentials();

		$this->title = 'Inscrição de trabalho';
		$this->corpo = $this->load->view('usuario/trabalho_form', $v, true);

		$this->templateRender();


	}

	/**
	 * validate and update a job
	 */
	public function post_trabalho($id = null)
	{
		$this->load->model('cms/trabalhos_model', 'trabalho');
		$this->load->library(array('form_validation'));

		$this->form_validation->set_rules('eixo_tematico', 'Eixo temático', 'trim|required');
		$this->form_validation->set_rules('modalidade', 'Modalidade do trabalho', 'trim|required');
		$this->form_validation->set_rules('titulo', 'Título', 'trim|required|callback_html_max_length[130]');
		$this->form_validation->set_rules('subtitulo', 'Subtítulo', 'trim|required|callback_html_max_length[130]');
		$this->form_validation->set_rules('resumo1', 'Resumo', 'trim|required|callback_html_max_length[700]');
		$this->form_validation->set_rules('palavras_chave', 'Palavras-chave', 'trim|required|max_length[75]');
		$this->form_validation->set_rules('autor_nome_1', 'Nome do autor (1)', 'trim|required');
		$this->form_validation->set_rules('curriculo_1', 'Minicurrículo (1)',
			'trim|required|callback_html_max_length[510]');
		$this->form_validation->set_rules('autor_nome_2', 'Nome do autor (2)', 'trim');
		$this->form_validation->set_rules('curriculo_2', 'Minicurrículo (2)', 'trim|callback_html_max_length[510]');
		$this->form_validation->set_rules('autor_nome_3', 'Nome do autor (3)', 'trim');
		$this->form_validation->set_rules('curriculo_3', 'Minicurrículo (3)', 'trim|callback_html_max_length[510]');
		$this->form_validation->set_rules('proposta', 'Proposta', 'trim|required|callback_html_max_length[20000]');

		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');



		/*
		 * Não validou
		 */
		if ($this->form_validation->run() == false)
		{

			// salva erro na session
			$this->phpsess->save('msg_type', 'error');
			$this->phpsess->save('msg', 'Existem campos incorretos.');
			$this->trabalho($id);
		} /*
         * OK, validou
         */
		else
		{

			$trabalho = new \Gestalt\Trabalho\Trabalho();
			$jobId    = $trabalho->updateFromForm($id, $this->input->post());

			if (!$jobId)
			{
				$this->phpsess->save('msg_type', 'error');
				$this->phpsess->save('msg', 'Houve um erro ao salvar trabalho. Tente mais tarde.');
				redirect("usuario/trabalho/{$id}");
			}
			else
			{
				// notification
				$note = new \Gestalt\Notifications\JobWasUpdatedByUser();
				$note->jobTitle(strip_tags($this->input->post('titulo')));
				$user = $this->usuario->get_session();
				$note->setUser($user['email'], $user['nome']);
				$note->send();

				$this->phpsess->save('msg_type', 'success');
				$this->phpsess->save('msg', 'Trabalho atualizado com sucesso.');
				redirect("usuario/trabalhos");
			}

		}
	}


	public function html_max_length($value = '', $limit = 0)
	{

		$this->load->helper('cmshelper');
		//        $chars = strip_tags($value);
		$chars = strip_tags(campo_texto_utf8($value));
		//        d($chars2);
		//        d(strlen($chars));
		//        dd(mb_strlen($chars2));

		if (mb_strlen($chars) > $limit)
		{
			$this->form_validation->set_message('html_max_length', 'O campo %s ultrapassou o limite de ' . $limit . '
            caracteres.');

			return false;
		}
		else
		{
			return true;
		}
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

	/**
	 * check authentication and pass redirect url
	 * @param string $redirect
	 */
	private function checkAuthentication($redirect = '')
	{
		if (!$this->usuario->get_session())
		{
			$this->usuario->setRedirectUrl($redirect);
			redirect('usuario/login');
		}
	}
}