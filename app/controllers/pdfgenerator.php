<?php

/**
 * Classe que implementa um loop para executar um tétodo de migração qualquer.
 *
 * O método $this->start() executa um script enquanto seu retorno == TRUE
 * Quando retornar == FALSE o looping é interrompido.
 */
class Pdfgenerator extends Frontend_Controller
{

	public $method = 'start';

	/**
	 * Quantidade de iterações.
	 * @var integer
	 */
	public $loop = 0;

	/**
	 * Quantos registros afetará antes de retornar.
	 * @var integer
	 */
	public $step = 10;

	/**
	 * Retorno do método de atualização/migração.
	 * Em caso 'false' interrompe looping.
	 * @var boolean
	 */
	public $continue = true;

	/**
	 * Variáveis da URI.
	 * @var array
	 */
	public $u = array();

	/**
	 * Quantos segundos de espera entre cada iteração.
	 * @var integer
	 */
	public $sleep = 1;

	// -------------------------------------------------------------------------

	public function __construct()
	{
		parent::__construct();

		$this->u = $this->uri->to_array(array('loop'));
		// retorno do loop
		$this->loop = ($this->u['loop'] == '') ? $this->loop : $this->u['loop'];
	}

	// -------------------------------------------------------------------------

	/**
	 * Exibe mensagem final.
	 */
	public function index()
	{
//		$this->load->helper('file');
//		$pdfName = '267';
//
//
//		$f = read_file($pdfName . '.pdf');
//
//		chmod($pdfName . '.pdf', 0777);
//		d(octal_permissions(fileperms($pdfName . '.pdf')));
//
//		if ( ! write_file($this->config->item('upl_trabalhos') .'/', $f, 'r+'))
//		{
//			echo 'Unable to write the file';
//		}
//		else
//		{
//			echo 'File written!';
//		}
//
//		$saved = fisic_path().$this->config->item('upl_trabalhos') .'/';
//
//		d(getcwd());
//		d(fisic_path().$pdfName . '.pdf');
//		d($saved . $pdfName . '.pdf');
//
//		d(move_uploaded_file(fisic_path().$pdfName . '.pdf', $saved . $pdfName . '.pdf'));

		$loops = $this->phpsess->flashget('loop', 'migration');
		echo 'Migração finalizada. <br>';
		echo 'Total loops: ' . (int)$loops;

	}

	// -------------------------------------------------------------------------

	/**
	 * Método que será executado a cada interação.
	 */
	public function start()
	{

		/*
		 * processa método de migração do usuário
		 */
		//        $this->load->model('meu_model', 'model');
		if ($this->generate_pdf() === false)
		{
			$this->stop();
		}
		else
		{
			$this->loop++;
		}

		// executado no final do método
		$this->run();
	}

	public function generate_pdf()
	{

		echo '$this->loop = ' . $this->loop;

		try{
			$pdf = new \Cms\Exporters\IndividualJobsToPdfExport();
			$files = $pdf->make($this->loop);

			return $files;
		}catch (Exception $e)
		{
			dd($e->getMessage());
		}

//		if ($this->loop >= 2)
//		{
//			return false;
//		}
//		else
//		{
//			return true;
//		}

	}

	// -------------------------------------------------------------------------
	// -------------------------------------------------------------------------
	// -------------------------------------------------------------------------

	/**
	 * Método execuado no final do método de atualização do usuário.
	 * Dependendo do valor de #this->continue reinicia iteração, ou
	 * redireciona para $this->index() para finalizar.
	 */
	private function run()
	{

		sleep($this->sleep);

		if ($this->continue)
		{
			// recebe o $this->loop e reinicializa processo
			redirect('pdfgenerator/' . $this->method . '/loop:' . $this->loop, 'refresh');
		}
		else
		{

			$this->phpsess->flashsave('loop', $this->loop, 'migration');
			// finaliza lopping
			redirect('pdfgenerator/index', 'refresh');
		}
	}

	// -------------------------------------------------------------------------

	/**
	 * Faz o fechamento das iterações para interrupção.
	 */
	private function stop()
	{
		$this->continue = false;
	}

}