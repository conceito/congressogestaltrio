<?php
namespace Gestalt\Trabalho;

use Gestalt\Congresso5;
use Gestalt\Notifications\EmailNotification;

/**
 * Class Trabalho
 *
 * modelo para trabalhar com trabalhos do congresso
 * traduz o modelo dos dados para o CMS
 *
 * @package Gestalt\Trabalho
 */
class Trabalho extends \CI_Model
{


    /**
     * ID of debug task
     * @var int
     */
    protected $debugTaskId = 233;
    /**
     * @var \Gestalt\Congresso5
     */
    protected $congresso;

    /**
     * @var \Gestalt\Notifications\EmailNotification
     */
    protected $notification;

    function __construct()
    {
        $this->load->library('e_mail');
        $this->congresso = new Congresso5();
        $this->notification = new EmailNotification($this->e_mail);
    }

    public function paginated($filters = array())
    {

    }

    public function all($filters = array())
    {

    }

    /**
     * return the task from cms model
     *
     * @param $id
     * @return mixed
     */
    public function find($id)
    {

        $this->load->model('cms/trabalhos_model');

        return $this->trabalhos_model->find(array('id' => $id));

    }

    /**
     * process data from POST
     *
     * @param array $data
     */
    public function save($data = array())
    {

    }


    /**
     * save data sent from form
     * return the job ID
     *
     * @param array $post
     * @return bool|int
     */
    public function saveFromForm($post = array())
    {
        $fields = $this->mapFormFieldsToDb($post);
        $metas  = $this->mapFormToMetadatas($post);


        if($this->isDebugMode($post))
        {
            return $this->debugTaskId;
        }

//        d($fields);
//        dd($metas);

        $this->db->insert('cms_conteudo', $fields);
        $jobId = $this->db->insert_id();

//        dd($jobId);
        if (!$jobId)
        {
            return false;
        }

        $this->cms_metadados->saveByContent($jobId, $metas);
        return $jobId;
    }


    /**
     * parse POST data and return only the fields of cms_conteudo table
     * @param $form
     * @return array
     */
    private function mapFormFieldsToDb($form)
    {
        $fields = array();

        $loggedUser = $this->usuario->get_session();

        $fields['titulo']    = (isset($form['titulo'])) ? clean_html_to_db($form['titulo']) : 'sem título';
        $fields['resumo']    = (isset($form['resumo1'])) ? clean_html_to_db($form['resumo1']) : 'sem resumo';
        $fields['dt_ini']    = date("Y-m-d");
        $fields['dt_fim']    = date("Y-m-d");
        $fields['hr_ini']    = date("H:i:s");
        $fields['hr_fim']    = date("H:i:s");
        $fields['grupo']     = 232; // aprovação
        $fields['modulo_id'] = $this->congresso->getModuloId();
        $fields['tags']      = (isset($form['palavras_chave'])) ? $form['palavras_chave'] : '';
        $fields['status']    = 1;
//        $fields['txt']       = (isset($form['proposta'])) ? campo_texto_utf8($form['proposta']) : '';
        $fields['txt']       = (isset($form['proposta'])) ? clean_html_to_db($form['proposta']) : '';
        //        $dados['rel']        = prep_rel_to_sql($rel);
        $fields['atualizado'] = date("Y-m-d H:i:s");
        $fields['nick']       = url_title($fields['titulo'], '-', true);
        $fields['autor']      = ($loggedUser) ? $loggedUser['id'] : 0;
        $fields['lang']       = 'pt';
        $fields['tipo']       = 'conteudo';

        $fields['full_uri'] = 'trabalhos/' . $fields['nick'];

        return $fields;
    }

    /**
     * parse POST data and return the meta data as array
     * @param array $form
     * @return array
     */
    private function mapFormToMetadatas($form = array())
    {

        $metas = array();

        if (isset($form['subtitulo']))
        {
            $metas[] = array(
                'meta_key'   => 'subtitulo',
                'meta_type'  => '',
                'meta_value' => clean_html_to_db($form['subtitulo'])
            );
        }
        if (isset($form['eixo_tematico']))
        {
            $metas[] = array(
                'meta_key'   => 'eixo_tematico',
                'meta_type'  => '',
                'meta_value' => $form['eixo_tematico']
            );
        }
        if (isset($form['modalidade']))
        {
            $metas[] = array(
                'meta_key'   => 'modalidade',
                'meta_type'  => '',
                'meta_value' => $form['modalidade']
            );
        }
        if (isset($form['autor_nome_1']))
        {
            $serialized = $this->serializeAuthorsDataFromFromPost($form);
            $metas[]    = array(
                'meta_key'   => 'authors',
                'meta_type'  => '',
                'meta_value' => $serialized
            );
        }

        return $metas;
    }

    /**
     * extract authors data from POST
     * map to database structure
     * serialize to save as metadata
     *
     * @param $form
     * @return string
     */
    private function serializeAuthorsDataFromFromPost($form)
    {
        //  "id":1399912144462,"user_id":0,"ordem":0,"nome":"Bruno","curriculo":"çlk çlk çkl ~çkl ~ç","status":1

        $users = array();

        if (isset($form['autor_nome_1']))
        {
            $users[] = array(
                "id"        => time() . '1',
                "user_id"   => 0,
                "ordem"     => 0,
                "nome"      => $form['autor_nome_1'],
                "curriculo" => $form['curriculo_1'],
                "status"    => 1
            );
        }

        if (isset($form['autor_nome_2']) && strlen($form['autor_nome_2']) > 1)
        {
            $users[] = array(
                "id"        => time() . '2',
                "user_id"   => 0,
                "ordem"     => 0,
                "nome"      => $form['autor_nome_2'],
                "curriculo" => $form['curriculo_2'],
                "status"    => 1
            );
        }

        if (isset($form['autor_nome_3']) && strlen($form['autor_nome_3']) > 1)
        {
            $users[] = array(
                "id"        => time() . '3',
                "user_id"   => 0,
                "ordem"     => 0,
                "nome"      => $form['autor_nome_3'],
                "curriculo" => $form['curriculo_3'],
                "status"    => 1
            );
        }

        return serialize($users);
    }

    /**
     * @param $post
     * @return bool
     */
    public function isDebugMode($post)
    {
        if (isset($post['debug_mode']) && (bool)$post['debug_mode'])
        {
            return true;
        }

        return false;
    }


    /**
     * send email notifications for author and admin
     *
     * @param $taskId
     * @param bool $isDebugging
     * @return bool
     */
    public function notifyNewTrabalho($taskId, $isDebugging = false)
    {
        $this->notification->debug($isDebugging);
        $this->notification->setTask($this->find($taskId));
        $this->notification->setUser($this->usuario->get_session());

        try{
            $this->notification->sendTaskReceived();
            return true;
        } catch(\Exception $e)
        {
            return false;
        }

    }

}