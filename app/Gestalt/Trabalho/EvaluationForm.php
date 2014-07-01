<?php namespace Gestalt\Trabalho;

class EvaluationForm
{
	private $ci;
	/**
	 * answers as array
	 * @var array
	 */
	private $data = array();


	private $form = array(
		array(
			'key'      => 'q1',
			'question' => 'O trabalho apresenta adequação do título ao trabalho	proposto',
			'answer'   => '',
			'obs'      => '',
			'type'     => 'bool'
		),
		array(
			'key'      => 'q2',
			'question' => 'O trabalho apresenta adequação do trabalho ao eixo temático',
			'answer'   => '',
			'obs'      => '',
			'type'     => 'bool'
		),
		array(
			'key'      => 'q3',
			'question' => 'O trabalho apresenta adequação do trabalho à modalidade proposta',
			'answer'   => '',
			'obs'      => '',
			'type'     => 'bool'
		),
		array(
			'key'      => 'q4',
			'question' => 'O trabalho apresenta coerência e articulação de ideias no texto',
			'answer'   => '',
			'obs'      => '',
			'type'     => 'bool'
		),
		array(
			'key'      => 'q5',
			'question' => 'O trabalho apresenta contribuição para as reflexões contemporâneas da abordagem',
			'answer'   => '',
			'obs'      => '',
			'type'     => 'bool'
		),
		array(
			'key'      => 'q6',
			'question' => 'O trabalho apresenta adequação do método aos objetivos propostos',
			'answer'   => '',
			'obs'      => '',
			'type'     => 'bool'
		),
		array(
			'key'      => 'q7',
			'question' => 'O trabalho apresenta clareza na apresentação dos resultados e sua discussão (se for o
			caso)',
			'answer'   => '',
			'obs'      => '',
			'type'     => 'bool'
		),
		array(
			'key'      => 'q8',
			'question' => 'O trabalho apresenta referências bibliográficas relevantes e compatíveis com o assunto
			desenvolvido',
			'answer'   => '',
			'obs'      => '',
			'type'     => 'bool'
		),
		array(
			'key'      => 'q9',
			'question' => 'MR e TL: introdução, relevância, conceitos principais, objetivos, síntese do estudo, aspectos em discussão e bibliografia',
			'answer'   => '',
			'obs'      => '',
			'type'     => 'bool'
		),
		array(
			'key'      => 'q10',
			'question' => 'MC: introdução, relevância, conceitos principais, objetivos, síntese do estudo, metodologia (teórico ou teórico-vivencial), aspectos em discussão e bibliografia',
			'answer'   => '',
			'obs'      => '',
			'type'     => 'bool'
		),
		array(
			'key'      => 'q11',
			'question' => 'WS: introdução, objetivos, metodologia, recursos e bibliografia',
			'answer'   => '',
			'obs'      => '',
			'type'     => 'bool'
		),
		array(
			'key'      => 'q12',
			'question' => 'EG: contexto, justificativa, público-alvo, objetivos, metodologia, resultados, conclusões, sugestões',
			'answer'   => '',
			'obs'      => '',
			'type'     => 'bool'
		),
		array(
			'key'      => 'q13',
			'question' => 'PO: apresentação do tema, objetivos, metodologia e resultados (quando houver), bibliografia',
			'answer'   => '',
			'obs'      => '',
			'type'     => 'bool'
		),
		array(
			'key'      => 'q14',
			'question' => 'Comentários do avaliador',
			'answer'   => '',
			'obs'      => '',
			'type'     => 'text'
		),
		array(
			'key'      => 'q15',
			'question' => 'O trabalho está:',
			'answer'   => '',
			'obs'      => '',
			'type'     => 'id'
		),
	);

	function __construct()
	{
		$this->ci = & get_instance();
	}

	public function setData($data)
	{
		$this->data = (! is_array($data)) ? null : $data;
	}

	public function getData()
	{
		return $this->data;
	}

	public function getAnswers($format = 'html')
	{
		$arrayStructure = $this->parseData($this->getData());

		if ($format == 'html' && $arrayStructure)
		{
			return $this->generateHtml($arrayStructure);
		}

		return 'conteúdo não encontrado';
	}

	private function parseData($getData)
	{
		if(! is_array($getData))
		{
			return false;
		}

		$structure = array();

		foreach ($getData as $key => $a)
		{
			$line        = $this->getLineByKey($key);
			$structure[] = array(
				'key'      => $key,
				'question' => $line['question'],
				'answer'   => $this->getAnswerByType($key, $line['type'], $a),
				'obs'      => $line['obs'],
				'type'     => $line['type']
			);
		}

		return $structure;
	}

	private function generateHtml($arrayStructure)
	{
		$html = '<table class="table">';
		foreach ($arrayStructure as $line)
		{
			if ($line['type'] == 'bool' || $line['key'] == 'q15')
			{
				$html .= "<tr><td><b>{$line['question']}</b> </td>";
				$html .= "<td>{$line['answer']}</td></tr>";
			}
			else
			{
				$html .= "<tr><td><b>{$line['question']}</b> <br>";
				$html .= "{$line['answer']}</td><td></td></tr>";
			}
		}

		$html .= '</table>';

		return $html;
	}

	private function getAnswerByType($key, $type, $a)
	{
		if ($type == 'bool')
		{
			return ($a) ? 'Sim' : 'Não';
		}
		if ($type == 'id' && $key == 'q15')
		{
			$this->ci->load->model('cms/avaliacao_model', 'avaliacao');

			return $this->ci->avaliacao->getNotaLabel($a, 1);
		}
		else
		{
			return $a;
		}
	}

	private function getLineByKey($key)
	{
		foreach ($this->form as $line)
		{
			if ($line['key'] == $key)
			{
				return $line;
				break;
			}
		}
	}

}