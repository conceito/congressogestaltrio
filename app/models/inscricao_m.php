<?php
use Gestalt\Congresso5;
use Gestalt\Notifications\EmailNotification;

/**
 * @property mixed e_mail
 */
class Inscricao_m extends CI_Model
{
    protected $notification;

    protected $congresso;

    function __construct()
    {
        $this->notification = new EmailNotification();
        $this->congresso    = new Congresso5();

    }


    /**
     * Gera combobox apenas com estado permitido: RJ
     * @param null $selected
     * @param string $css
     * @return string
     */
    public function comboUf($selected = null, $css = 'form-control')
    {

        if ($selected === null)
        {
            $selected = $this->input->post('uf');
        }

        if (!$selected)
        {
            $selected = 'RJ';
        }

        $this->db->order_by('nome');
        $sql = $this->db->get('opt_estado');

        $options = '<option value=""> - </option>';
        foreach ($sql->result_array() as $row)
        {
            $i   = $row['uf'];
            $lbl = $i;
            $s   = ($i == $selected) ? 'selected="selected"' : '';

            $options .= '<option value="' . $i . '" ' . $s . '>' . $lbl . '</option>';

        }

        $html = '<select name="uf" id="field_uf" class="' . $css . '">';
        $html .= $options;
        $html .= '</select>';

        return $html;
    }


    /**
     * combobox cidade
     * @param string $uf
     * @param null $selected
     * @param string $css
     * @return string
     */
    public function comboCidades($uf = 'RJ', $selected = null, $css = 'form-control')
    {

        if ($selected === null)
        {
            $selected = $this->input->post('cidade');
        }

        if ($this->input->post('uf'))
        {
            $uf = $this->input->post('uf');
        }

        $this->db->order_by('nome');
        $this->db->where('uf', strtoupper($uf));
        $sql = $this->db->get('opt_cidades');

        $options = '<option value="">Escolha... </option>';
        foreach ($sql->result_array() as $row)
        {
            $i   = $row['id'];
            $lbl = $row['nome'];
            $s   = ($i == $selected) ? 'selected="selected"' : '';

            $options .= '<option value="' . $i . '" ' . $s . '>' . $lbl . '</option>';

        }

        $html = '<select name="cidade" id="cidade" class="' . $css . '">';
        $html .= $options;
        $html .= '</select>';

        return $html;
    }

    /**
     * Gera combobox apenas com estado permitido: RJ
     * @param null $selected
     * @param string $css
     * @return string
     */
    public function comboPaises($selected = null, $css = 'form-control')
    {
        if ($selected === null)
        {
            $selected = $this->input->post('pais');
        }

        if (!$selected)
        {
            $selected = 80; // brazil
        }

        $this->db->order_by('nome');
        $this->db->where('status', 1);
        $sql = $this->db->get('opt_paises');

        $options = '<option value=""> Escolha... </option>';
        foreach ($sql->result_array() as $row)
        {
            $i   = $row['id'];
            $lbl = $row['nome'];
            $s   = ($i == $selected) ? 'selected="selected"' : '';
            $options .= '<option value="' . $i . '" ' . $s . '>' . $lbl . '</option>';

        }

        $html = '<select name="pais" id="field_pais" class="' . $css . '">';
        $html .= $options;
        $html .= '</select>';

        return $html;
    }

    /**
     * check the post data for 'debug_mode'
     * @param null $post
     * @return bool
     */
    public function isDebug($post = null)
    {
        if ($this->input->post('debug_mode'))
        {
            return true;
        }
        else if (isset($post['debug_mode']) && $post['debug_mode'])
        {
            return true;
        }

        return false;
    }


    /**
     * save subscription
     * post data:
     *  'nome' => string 'nome' (length=4)
     * 'sobrenome' => string 'sobrenome' (length=9)
     * 'cracha' => string 'cracha' (length=6)
     * 'email' => string 'example@example.com' (length=19)
     * 'cpf' => string '546.546.465-46' (length=14)
     * 'tel1' => string '(12)34567890' (length=12)
     * 'sexo' => string '1' (length=1)
     * 'fantasia' => string 'fantasia' (length=8)
     * 'pais' => string '80' (length=2)
     * 'cep' => string '24.243-543' (length=10)
     * 'uf' => string 'MT' (length=2)
     * 'cidade' => string '4216' (length=4)
     * 'bairro' => string 'bairro' (length=6)
     * 'logradouro' => string 'logradouro' (length=10)
     * 'num' => string 'num' (length=3)
     * 'compl' => string 'compl' (length=5)
     * 'pagamento' => string 'contato' (length=7)
     * 'senha' => string '123456' (length=6)
     * 'confirmacao' => string '123456' (length=6)
     * @param array $data
     * @return array|bool
     */
    public function saveSubscription($data = array())
    {

        $congressId = $this->congresso->getConteudoId();

        $this->load->library('cms_usuario');
        $this->load->library('institution');
        $this->load->library('value_table');
        $this->institution->setInstitutionId($data['pagamento']);
        $valueTable = $this->value_table->getByType($data['tipo_usuario']);

        $parsedData = $this->preparseUserData($data);
        //dd($parsedData);
        $userId = $this->cms_usuario->insert_update($parsedData, 'email');
        $this->saveUserMetadata($userId, $data);

        $inscriptionId = $this->cms_usuario->inscribe(array(
            'conteudo_id' => $congressId,
            'comentario'  => '',
            'user_id'     => $userId,
            'status'      => 2
        ), false, true);

        // get content billing data
        $this->cms_conteudo->set_page($congressId);
        $post = $this->cms_conteudo->get_page();
        //        $preCup = $this->cms_conteudo->get_precos_cupons(221);
        //        $total = $this->cms_conteudo->preco_final($preCup['cupons'], $preCup['precos']);
        //        dd($preCup);
        //        dd($total);

        // generate bill
        $this->load->library('cms_extrato');
        $extratoId = $this->cms_extrato->add(array(
            'modulo_id'      => $post['modulo_id'],
            'usuario_id'     => $userId,
            'inscricao_id'   => $inscriptionId,
            'metodo'         => $valueTable['type'],
            'tipo_pagamento' => $data['tipo_usuario'],
            'parcelas'       => ($data['forma_pagamento'] == 'parcelado') ? $valueTable['portion']['times'] : 1,
            'valor_total'    => $valueTable['value'],
            'data'           => date("Y-m-d"),
            'hora'           => date("H:i:s"),
            'anotacao'       => 'Aguardando pagamento',
            'comprovante'    => '',
            'status'         => 2
        ));

        if (!is_numeric($userId) || !is_numeric($inscriptionId) || !is_numeric($extratoId))
        {
            return false;
        }

        // send mail
        $this->sendSubscriptionMessage(array(
            'user'        => $userId,
            'inscription' => $inscriptionId,
            'bill'        => $extratoId
        ));

        return array(
            'user'        => $userId,
            'inscription' => $inscriptionId,
            'bill'        => $extratoId
        );

        //        d($userId);
        //        dd($data);
    }

    private function preparseUserData($data)
    {
        $data['nome']   = $data['nome'] . ' ' . $data['sobrenome'];
        $data['status'] = 1;

        return $data;
    }

    private function saveUserMetadata($userId, $data)
    {
        $this->load->library('cms_metadados');

        $this->cms_metadados->saveByUser($userId, array(
            'meta_key'   => 'nome_cracha',
            'meta_type'  => '',
            'meta_value' => $data['cracha']
        ));
        $this->cms_metadados->saveByUser($userId, array(
            'meta_key'   => 'pais',
            'meta_type'  => '',
            'meta_value' => $data['pais']
        ));
        $this->cms_metadados->saveByUser($userId, array(
            'meta_key'   => 'pagamento',
            'meta_type'  => '',
            'meta_value' => $data['pagamento']
        ));
        $this->cms_metadados->saveByUser($userId, array(
            'meta_key'   => 'tipo_usuario',
            'meta_type'  => '',
            'meta_value' => $data['tipo_usuario']
        ));
        $this->cms_metadados->saveByUser($userId, array(
            'meta_key'   => 'forma_pagamento',
            'meta_type'  => '',
            'meta_value' => $data['forma_pagamento']
        ));
    }

    /**
     *
     * array(
     * 'user'        => $userId,
     * 'inscription' => $inscriptionId,
     * 'bill'        => $extratoId
     * )
     *
     * @param $array
     */
    public function sendSubscriptionMessage($array)
    {
        $this->load->library('value_table');
        $this->load->library('institution');
        $this->load->model('contato_m', 'contato');
        $user = $this->usuario->find($array['user']);

        $v['user']            = $user;
        $v['table']           = $this->value_table->getByType(get_meta($user['metas'], 'tipo_usuario', null, true));
        $v['institution']     = $this->institution->getInstance(get_meta($user['metas'], 'pagamento', null, true));
        $v['forma_pagamento'] = get_meta($user['metas'], 'forma_pagamento', null, true);
        //        dd($v['institution']);

        $body = $this->load->view('inscricao_obrigado', $v, true);

        $nome    = $user['nome'];
        $email   = $user['email'];
        $assunto = 'Inscrição ' . $this->config->item('title');

        $html = $this->contato->emailTemplate($body);
        //echo $html; exit;
        /*
         * instancia library
         */
        $this->load->library('e_mail');

        if (ENVIRONMENT == 'development')
        {
            $emailDes = $this->config->item('email_debug');
            $assunto  = '[debug] ' . $assunto;
        }
        else
        {
            $emailDes = $this->config->item('email1');
        }

        $nomeDes  = $this->config->item('title');
        $menHTML  = $html;
        $menTXT   = strip_tags($html);
        $emailRem = $email;
        $nomeRem  = $nome;

        $ret = $this->e_mail->envia($emailRem, $nomeRem, $assunto, $menHTML, $menTXT, $emailDes, $nomeDes);

        /**
         * copy admin
         */
        $this->e_mail->envia($emailDes, $nomeDes, '[cópia] ' . $assunto, $menHTML, $menTXT, $emailRem, $nomeRem);

        return $ret;
    }

    public function buscaCepAviso($cep = '')
    {

        /*
         * http://avisobrasil.com.br/api-de-consulta-de-cep/
         * API: http://cep.correiocontrol.com.br/$CEP.json
         */

        // Simple call to CI URI
        $json = $this->curl->simple_get('http://cep.correiocontrol.com.br/' . $cep . '.json');

        //        $this->curl->debug();
        // converte para array
        //         parse_str($json, $output);
        return $json;
    }

    /**
     * Busca ID da cidade pelo nome
     * @param string $str Nome da cidade
     * @return string
     */
    public function find_city_id($str = '')
    {

        $ret = $this->db->like('nome', $str)
            ->limit(1)
            ->get('opt_cidades');

        if ($ret->num_rows() > 0)
        {
            $cidade = $ret->row_array();

            return $cidade['id'];
        }
        else
        {
            return '';
        }
    }


    public function renewPasswordForUserEmail($email = '')
    {
        // generate new hash
        $newPass = rand(111111, 999999);

        // update table
        $user = $this->cms_usuario->get(array('email' => $email));
        if (!$user)
        {
            return 'Usuário não encontrado.';
        }

        /** @var $user array */
        $this->cms_usuario->update($user['id'], array('senha' => $newPass));

        // send notification
        $this->notification->passwordRenewed($user, $newPass);

        return true;
    }
}