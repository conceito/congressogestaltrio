<?php namespace Gestalt\Trabalho;

use CI_Controller;

/**
 * Class Schedule
 * @package Gestalt\Trabalho
 */
class Schedule
{
	/**
	 * @var CI_Controller
	 */
	private $ci;

	private $moduleId = 66;


	private $ids = array('595', '376', '352', '358', '354', '598', '483', '346',
		'277', '601', '366', '368', '360', '337', '279', '359', '363', '375',
		'361', '335', '273', '365', '575', '267', '351', '563', '317', '349', '347', '353',
		'292', '369', '340', '276', '571', '321', '280', '294', '357', '367',
		'320', '281', '338', '333', '319', '336');

	/**
	 * mesas redondas
	 * @var array
	 */
	private $mr = array(

		'595' => array(
			'subjects' => array(
				array('id' => 495, 'titulo' => 'O corpo-tempo, o outro e o contato na situação contemporânea - reflexões interdisciplinares.', 'authors' => 'Mônica Botelho Alvim'),
				array('id' => 500, 'titulo' => 'O cuidar na clínica gestáltica - o fluir terapêutico como escrita poética.', 'authors' => 'Luciana Soares'),
				array('id' => 566, 'titulo' => 'Olhos nos olhos, quero ver o que você diz: no diálogo psicoterapêutico, a linguagem e o olhar da/para a contemporaneidade.', 'authors' => 'Cláudia Távora'),
			)
		),
		'598' => array(
			'subjects' => array(
				array('id' => 612, 'titulo' => 'A infância sob controle: a medicalização a serviço da desconexão com o sentir e da imobilidade para a ação criativa.', 'authors' => 'Luciana Aguiar'),
				array('id'      => 334, 'titulo' => 'O paradigma da medicalização social e a Gestalt-terapia: tecendo novos
				valores.',
				      'authors' => 'Guilherme de Carvalho'),
				array('id' => 605, 'titulo' => 'Fármaco x Remédio x Medicamento - Dispositivos para construção de saúde?- Uma reflexão sobre o cuidar em Saúde Mental.', 'authors' => 'Haroldo Machado')
			)
		),
		'601' => array(
			'subjects' => array(
				array('id' => 615, 'titulo' => '', 'authors' => ''),
				array('id' => 618, 'titulo' => '', 'authors' => ''),
				array('id' => 609, 'titulo' => 'Ultrapassando muros: a sensibilidade como recurso na formação de psicólogos.', 'authors' => 'Laura Cristina de Toledo Quadros e Eleonôra Torres Prestrelo'),

			)
		)
	);


	function __construct()
	{
		$this->ci = &get_instance();
		$this->ci->load->model('cms/trabalhos_model', 'trabalho');
	}


	/**
	 * return all resources to build schedule
	 */
	public function all()
	{
		// fetch data
		//		dd($this->fetchFromDb()[595]);

		return $this->fetchFromDb();
	}

	private function fetchFromDb()
	{
		//		$this->ci

		$this->ci->db->where_in('id', $this->ids);
		$this->ci->db->where('grupo >', 0);
		$this->ci->db->where('tipo', 'conteudo');
		$this->ci->db->select('id, titulo, resumo');
		$queryContent = $this->ci->db->get('cms_conteudo');

		$content = $queryContent->result();

		$this->ci->db->where_in('conteudo_id', $this->ids);
		//		$this->ci->db->where('meta_key', 'authors');
		//		$this->ci->db->where('meta_key', 'subtitulo');
		$this->ci->db->where_in('meta_key', array('authors', 'subtitulo'));
		$this->ci->db->select('conteudo_id, meta_key, meta_value');
		//		$this->ci->db->group_by('conteudo_id');
		$queryMetas = $this->ci->db->get('cms_conteudometas');

		$metas = $queryMetas->result();

		return $this->joinContentMetasArray($content, $metas);

	}


	/**
	 * get a row with metas
	 * @param $id
	 */
	public function find($id)
	{
		$this->ci->db->where('id', $id);
		$this->ci->db->select('id, titulo, resumo');
		$queryContent = $this->ci->db->get('cms_conteudo');

		$content = $queryContent->row();

		$this->ci->db->where('conteudo_id', $id);
		$this->ci->db->where_in('meta_key', array('authors', 'subtitulo'));
		$this->ci->db->select('conteudo_id, meta_key, meta_value');
		$queryMetas = $this->ci->db->get('cms_conteudometas');

		$metas = $queryMetas->result();

		return $this->joinContentMetas($content, $metas);
	}

	/**
	 * @param $content
	 * @param $metas
	 * @return array
	 */
	private function joinContentMetasArray($content, $metas)
	{
		$collection = array();

		foreach ($content as $c)
		{
			$collection[$c->id] = $this->joinContentMetas($c, $metas);
		}

		return $collection;
	}

	/**
	 * @param $id
	 * @param $metas
	 * @return
	 */
	private function joinContentMetas($content, $metas)
	{
		$content->subtitulo = $this->getSubtituloFromId($content->id, $metas);
		$content->authors   = $this->getAuthorsFromId($content->id, $metas);
		$content->subjects  = $this->ifMrGetSubjects($content->id, $metas);

		if ($this->isMesaRedonda($content->id))
		{
			$content->resumo = $content->resumo . '<p>Esta mesa é composta pelos seguintes trabalhos:</p>';
		}

		return $content;
	}


	private function getSubtituloFromId($id, $metas)
	{
		foreach ($metas as $m)
		{
			if ($m->conteudo_id == $id && $m->meta_key == 'subtitulo')
			{
				return strlen($m->meta_value) == 0 ? null : $m->meta_value;
			}
		}

		return null;
	}

	private function getAuthorsFromId($id, $metas)
	{
		foreach ($metas as $m)
		{
			if ($m->conteudo_id == $id && $m->meta_key == 'authors')
			{
				return unserialize($m->meta_value);
			}
		}

		return null;
	}

	private function isMesaRedonda($id)
	{
		return in_array($id, $this->getMrIds());
	}

	/**
	 * @param $id
	 * @param $metas
	 * @return null|string
	 */
	private function ifMrGetSubjects($id, $metas)
	{
		if (!$this->isMesaRedonda($id))
		{
			return null;
		}

		$subjectsCopy = $this->mr[$id]['subjects'];

		$subjects = array();

		foreach ($subjectsCopy as $s)
		{
			if (isset($s['id']))
			{
				$subjects[] = $this->getSubject($s['id']);
			}
			else
			{
				$s['resumo'] = 'Em breve.';
				$subjects[]  = (object)$s;
			}
		}

		return $subjects;
	}

	/**
	 * return MR IDs
	 * @return array
	 */
	public function getMrIds()
	{
		return array_keys($this->mr);
	}

	private function getSubject($id)
	{
		$content = $this->find($id);

		return $content;
	}

}