<?php namespace Cms\Exporters;


class IndividualJobsToPdfExport extends Pdf implements ExportingInterface{


	private $congressId = 229;
	/**
	 * run the exportation process
	 * @return mixed
	 */
	public function make($offset = 0)
	{
		$jobs = $this->getJobsAtIndex($offset);
		if(empty($jobs))
		{
			return false;
		}

		$this->setSavePath($this->ci->config->item('upl_trabalhos').'/'.$this->congressId);
		$this->setOutputMode('F'); // I
		$this->setFont('Times');

		foreach($jobs as $job)
		{

//			dd($job);
//			echo $this->ci->load->view('cms/trabalhos/export_page_pdf', $job, true);
//			exit;
			$this->setContentHtml($this->ci->load->view('cms/trabalhos/export_page_pdf', $job, true));
			// show on screen

			$this->render($job['id']);
		}

		 return true;
	}

	public function getJobsAtIndex($offset = 0)
	{
//		$this->ci->load->model('cms/trabalhos_model', 'trabalho');
		$this->ci->load->library('cms_metadados');

//		$this->ci->db->where_in('id', array(375));
		$this->ci->db->where('modulo_id', 66);
		$this->ci->db->where('grupo', 230);// aprovado
		$this->ci->db->where('tipo', 'conteudo');
		$this->ci->db->order_by('id asc');

		$this->ci->db->limit(1, $offset);

		$this->ci->db->where('lang', get_lang());
		$this->ci->db->select('SQL_CALC_FOUND_ROWS *', false);
		$sql = $this->ci->db->get('cms_conteudo');

		$jobs = array();

		foreach($sql->result_array() as $job)
		{
			$metas = $this->ci->cms_metadados->getAllByContent($job['id']);

			$aAuthors = unserialize(get_meta($metas, 'authors', null, true));

			if (!is_array($aAuthors))
			{
				$job['authors'] = array();
			}

			$job['authors'] = $aAuthors;


			$subtit = get_meta($metas, 'subtitulo', null, true);
			$titulo = str_replace(array('<p>','</p>'), '', $job['titulo'] . ' ' . $subtit);

			$job['titulo'] = mb_strtoupper(html_entity_decode($titulo, ENT_NOQUOTES));
//			$job['titulo'] = mb_strtoupper(utf8_encode($titulo));



			$jobs[] = $job;
		}

		return $jobs;

		// -- pega o Total de registros -- //

//		$query    = $this->ci->db->query('SELECT FOUND_ROWS() AS `Count`');
//		$ttl_rows = $query->row()->Count;
	}

}