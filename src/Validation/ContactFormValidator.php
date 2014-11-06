<?php namespace Src\Validation;

class ContactFormValidator extends Validator
{

	protected $rules = array(
		array(
			'field' => 'nome',
			'label' => 'Nome',
			'rules' => 'trim|required'
		),
		array(
			'field' => 'email',
			'label' => 'E-mail',
			'rules' => 'trim|required|valid_email'
		),
		array(
			'field' => 'mensagem',
			'label' => 'Mensagem',
			'rules' => 'trim|required|min_length[10]'
		),
		array(
			'field' => 'tel',
			'label' => 'Telefone',
			'rules' => 'callback_external_callbacks[Src\Validation\ContactFormValidator,telephone]'
		)
	);

	/**
	 * Call parent constructor to get CI instance in this context
	 */
	function __construct()
	{
		parent::__construct();

		// here we could apply conditional rules
	}


	/**
	 * validate the attached file input
	 *
	 * NEEDED: (xx) xxxxx?-xxxx
	 *
	 * @param $value
	 * @return bool
	 */
	public function telephone($value)
	{
		$numbers = preg_replace('/[^\d]/', '', $value);

		if (strlen($numbers) < 10 || strlen($numbers) > 11)
		{
			$this->ci->form_validation->set_message('external_callbacks', 'Telefone não está no formato correto.');

			return false;

		}

		return true;

	}

	/**
	 * validate the attached file input
	 * @param $value
	 * @return bool
	 */
	public function attached($value)
	{
		if (isset($_FILES['anexo']) && strlen($_FILES['anexo']['name']) > 0)
		{

			$this->ci->load->library('cms_arquivo');
			$this->ci->cms_arquivo->max_size     = 2097152; // 1048576 = 1Mb
			$this->ci->cms_arquivo->permited_ext = array('doc', 'docx', 'jpg', 'pdf');

			$val = $this->ci->cms_arquivo->valida('anexo');

			if ($val !== true)
			{
				$this->ci->form_validation->set_message('external_callbacks', $val);

				return false;
			}

		}

		return true;

	}


} 