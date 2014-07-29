<?php

use Gestalt\Congresso5;

class Inscricao extends Frontend_Controller
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

	public function __construct()
	{
		parent::__construct();
		$this->load->model('contato_m', 'contato');
		$this->load->model('inscricao_m', 'inscricao');
		$this->load->model('usuario_m', 'usuario');

		$this->congresso = new Congresso5();

		Console::log($this->phpsess->get(null, 'user'));

		//        $this->load->library('cms_conteudo'); # carregadas no Front_Controller
	}

	public function index()
	{

		$this->message();
		$this->setNewScript(array('jquery.validate', 'page.inscricao'));

		$this->load->library('value_table');
		$vt            = $this->value_table->getByType('profissional');
		$v['parcelas'] = ($vt['portion']['times'] > 1) ? $vt['portion']['times'] : false;
		//        dd($v['parcelas']);
		$v['combo_uf']      = $this->inscricao->comboUf();
		$v['combo_cidades'] = $this->inscricao->comboCidades();
		$v['combo_paises']  = $this->inscricao->comboPaises();

		$this->title = 'Inscrição';
		$this->corpo = $this->load->view('inscricao_form', $v, true);

		$this->templateRender();

	}

	public function post_inscricao()
	{
		$this->load->library(array('form_validation'));

		$this->form_validation->set_rules('nome', 'Primeiro nome', 'trim|required');
		$this->form_validation->set_rules('sobrenome', 'Último nome', 'trim|required');
		$this->form_validation->set_rules('cracha', 'Nome no crachá', 'trim|required');
		$this->form_validation->set_rules('email', 'E-mail', 'trim|required|valid_email');
		$this->form_validation->set_rules('cpf', 'CPF', 'trim|required|min_length[14]');
		$this->form_validation->set_rules('tel1', 'Telefone', 'trim|required|min_length[12]');
		$this->form_validation->set_rules('sexo', 'Sexo', 'trim|required');
		$this->form_validation->set_rules('fantasia', 'Instituição a qual pertence', 'trim');
		$this->form_validation->set_rules('pais', 'País', 'trim|required');
		$this->form_validation->set_rules('cep', 'CEP', 'trim|required|min_length[9]');
		$this->form_validation->set_rules('uf', 'UF', 'trim|required|min_length[2]');
		$this->form_validation->set_rules('cidade', 'Cidade', 'trim|required');
		$this->form_validation->set_rules('bairro', 'Bairro', 'trim|required');
		$this->form_validation->set_rules('logradouro', 'Logradouro', 'trim|required');
		$this->form_validation->set_rules('num', 'Número', 'trim|required');
		$this->form_validation->set_rules('compl', 'Complemento', 'trim');
		$this->form_validation->set_rules('pagamento', 'Instituição por onde fará o pagamento', 'trim|required');
		$this->form_validation->set_rules('tipo_usuario', 'Você é', 'trim|required');
		$this->form_validation->set_rules('forma_pagamento', 'Forma de pagamento', 'trim|required');
		$this->form_validation->set_rules('senha', 'Senha', 'trim|required|matches[confirmacao]');
		$this->form_validation->set_rules('confirmacao', 'Confirmar senha', 'trim|required');

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
			$this->index();
		} /*
         * OK, validou
         */
		else
		{

			$ret = $this->inscricao->saveSubscription($this->input->post());

			if ($ret)
			{
				$this->phpsess->save('msg_type', 'success');
				$this->phpsess->save('msg', 'Inscrição realizada com sucesso.');
			}
			else
			{
				$this->phpsess->save('msg_type', 'error');
				$this->phpsess->save('msg', 'Erro ao salvar inscrição.');
			}

			redirect("inscricao/obrigado/{$ret['user']}/{$ret['inscription']}/{$ret['bill']}");
		}
	}

	/**
	 * @param null $userId
	 * @param null $inscriptionId
	 * @param null $billId
	 */
	public function obrigado($userId = null, $inscriptionId = null, $billId = null)
	{
		$this->message();

		$this->load->library('value_table');
		$this->load->library('institution');

		$user = $this->usuario->find($userId);

		$v['user']            = $user;
		$v['table']           = $this->value_table->getByType(get_meta($user['metas'], 'tipo_usuario', null, true));
		$v['institution']     = $this->institution->getInstance(get_meta($user['metas'], 'pagamento', null, true));
		$v['forma_pagamento'] = get_meta($user['metas'], 'forma_pagamento', null, true);
		//        dd($v['institution']);
		$this->title = 'Obrigado';
		$this->corpo = $this->load->view('inscricao_obrigado', $v, true);

		$this->templateRender();
	}


	/**
	 * login form
	 */
	public function login()
	{
		$this->message();

		$this->setNewScript(array('jquery.validate', 'page.login'));

		$this->title = 'Login';
		$this->corpo = $this->load->view('login_form', '', true);

		$this->templateRender();
	}

	public function passwordrenew()
	{

		$email = $this->input->post('email');
		echo $this->inscricao->renewPasswordForUserEmail($email);

	}

	public function logout()
	{
		$this->cms_usuario->do_logout();
		redirect('inscricao/login');
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
				redirect("inscricao/login");
			}
			else
			{
				redirect("inscricao/trabalho");
			}

		}
	}


	/**
	 * form job inscription
	 */
	public function trabalho($permission = '')
	{

		if ($permission == 'comissao')
		{
			$this->usuario->setSpecialCredentials();
		}

//		d($this->usuario->hasSpecialCredentials());
//		dd($this->usuario->get_session());

		if (! $this->usuario->hasSpecialCredentials())
		{

			$this->cms_usuario->do_logout();
//			$this->usuario->removeSpecialCredentials();
			$this->phpsess->save('msg_type', 'error');
			$this->phpsess->save('msg', 'A inscrição de trabalhos está encerrada.');
		}

		if (!$this->usuario->get_session())
		{
			$this->usuario->setRedirectUrl('inscricao/login');
			redirect('usuario/login');
		}

		//        $t = new \Gestalt\Trabalho\Trabalho();
		//        dd($t->notifyNewTrabalho(232, false));

		$this->message();

		// libs\tiny_mce356
		//        $this->setNewJquery(array('tiny_mce356' => 'jquery.tinymce'));
		//        $this->setNewScript(array('jquery.tinymce'), 'libs');
		$this->setNewScript(array('jquery.validate', 'page.trabalho'));

		//        dd($v['parcelas']);
		$v['user']        = $this->usuario->get_session();
		$v['temas']       = $this->congresso->allTemas();
		$v['modalidades'] = $this->congresso->allModalidades();
		$v['canSubmit']   = $this->usuario->hasSpecialCredentials();

		$this->title = 'Inscrição de trabalho';
		$this->corpo = $this->load->view('inscricao_trabalho_form', $v, true);

		$this->templateRender();

	}


	public function trabalho_enviado()
	{
		if (!$this->usuario->get_session())
		{
			$this->usuario->setRedirectUrl('inscricao/login');
			redirect('usuario/login');
		}

		$this->message();

		$v['user'] = $this->usuario->get_session();

		$this->title = 'Inscrição de trabalho';
		$this->corpo = $this->load->view('inscricao_trabalho_enviado', $v, true);

		$this->templateRender();
	}


	/**
	 * receive job POST
	 */
	public function post_trabalho()
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
		$this->form_validation->set_rules('proposta', 'Proposta', 'trim|required|callback_html_max_length[12000]');

		$this->form_validation->set_error_delimiters('<label class="error">', '</label>');

		/*
		 * Não validou
		 */
		if ($this->form_validation->run() == false)
		{

			// salva erro na session
			$this->phpsess->save('msg_type', 'error');
			$this->phpsess->save('msg', 'Existem campos incorretos.');
			$this->trabalho();
		} /*
         * OK, validou
         */
		else
		{

			$trabalho = new \Gestalt\Trabalho\Trabalho();
			$jobId    = $trabalho->saveFromForm($this->input->post());


			$this->usuario->removeSpecialCredentials();

			if (!$jobId)
			{
				$this->phpsess->save('msg_type', 'error');
				$this->phpsess->save('msg', 'Houve um erro ao salvar trabalho. Tente mais tarde.');
				redirect("inscricao/trabalho");
			}
			else
			{
				$trabalho->notifyNewTrabalho($jobId, $this->input->post('debug_mode'));
				$this->phpsess->save('msg_type', 'success');
				$this->phpsess->save('msg', 'Trabalho enviado com sucesso.');
				redirect("inscricao/trabalho_enviado");
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
	 * Recebe o cep e busca endereço completo na API republicavirtual.
	 *
	 * @param int $cep
	 * @return array
	 */
	public function cep($cep = '')
	{

		// curl
		$this->load->spark('curl/1.2.1');
		$this->load->helper('text');

		try
		{
			//        $return = $this->usuarios->buscaCepRepublicaVirtual($cep);
			$return = $this->inscricao->buscaCepAviso($cep);

			//        mybug($array);
		} catch (Exception $e)
		{
			echo false;
			exit;
		}

		$array              = json_decode($return, true);
		$cityId             = $this->inscricao->find_city_id($array['localidade']);
		$array['cidade_id'] = (is_numeric($cityId) && $cityId > 1) ? $cityId : false;

		if (IS_AJAX)
		{
			echo json_encode($array);
		}
		else
		{
			return $return;
		}
	}

}