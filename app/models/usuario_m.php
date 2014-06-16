<?php

class Usuario_m extends CI_Model
{

    public $usuario = false;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('cms_usuario');
        $this->load->library('cms_metadados');
    }

    public function find($id)
    {

        $user = $this->cms_usuario->get($id);

        // metadata
        $metas = $this->cms_metadados->getAllByUser($id);
        $user['metas'] = $metas;

        return $user;
    }

    /**
     * Retorna dados do usuário da sessão, ou FALSE
     * @return array|boolean
     */
    public function get_session()
    {
        if ($this->cms_usuario->get_session()) {
            return $this->cms_usuario->get_session();
        } else {
            return false;
        }
    }

    // ------------------------------------------------------------------------

    /**
     * Retorna dados do usuário.
     * @return array
     */
    public function user_infos()
    {

        if ($this->usuario) {
            return $this->usuario;
        }

        $sess                    = $this->cms_usuario->get_session();
        $this->usuario           = $this->cms_usuario->get($sess['id'], true);
        $this->usuario['regiao'] = $this->cms_usuario->get_regiao_entrega();

        return $this->usuario;
    }

    // ---------------------------------------------------------------------
    /**
     * Requisitando CEP:
     * http://cep.republicavirtual.com.br/web_cep.php?cep=91010000&formato=json
     * Retorno:
     * Array
     * (
     * [resultado] => 1
     * [resultado_txt] => sucesso - cep completo
     * [uf] => RJ
     * [cidade] => Niteroi
     * [bairro] => Inga
     * [tipo_logradouro] => Rua
     * [logradouro] => Doutor Paulo Alves
     * )
     * @param string|\type $cep
     * @return
     */
    public function buscaCepRepublicaVirtual($cep = '')
    {

        // Simple call to CI URI
        $json = $this->curl->simple_get('http://cep.republicavirtual.com.br/web_cep.php', array(
            'cep'     => $cep,
            'formato' => 'query_string'
        ));

        //        $this->curl->debug()
        // converte para array
        parse_str($json, $output);

        return $output;
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

    // -------------------------------------------------------------------------

    /**
     * salva usuário no BD e coloca na sessão.
     * @param type $is
     * @return int|boolean
     */
    public function cadastro_salva($is)
    {

        // dados recebidos
        //        mybug($this->input->post());
        // faz tatamento
        // salva no BD
        if ($is == 'insert') {
            $_POST['foto']           = '';
            $_POST['grupo']          = 12; // PJ
            $_POST['status']         = 1;
            $_POST['regiao_entrega'] = $_POST['regiao'];
            $user_id                 = $this->cms_usuario->insert_update($_POST, 'email');
        } else {
            $user = $this->get_session();

            if (isset($_POST['regiao'])) {
                $_POST['regiao_entrega'] = $_POST['regiao'];
            }

            $user_id = $this->cms_usuario->update($user['id'], $_POST);
        }

        if ($is == 'insert') {
            // em caso positivo coloca usuário na sessão
            $_POST['id'] = $user_id;
            $this->do_login($_POST);
        }

        return ($user_id === false) ? false : $user_id;
    }

    // -------------------------------------------------------------------------
    /**
     * Seleciona os dados do usuário que ficarão na sessão
     * @param array $user_array
     */
    public function doLogin($user_array)
    {

        $sess['id'] = $user_array['id'];
        //        $sess['nome'] = $user_array['nome'];
        //        $sess['email'] = $user_array['email'];
        //        $sess['grupo'] = $user_array['grupo'];
        //        $sess['fantasia'] = $user_array['fantasia'];

        $this->cms_usuario->do_login($sess);
    }


    /**
     * check credentials
     * if success put on session
     *
     * @param $userData
     * @return array|bool
     * @throws Exception
     */
    public function checkValidLogin($userData)
    {
        if(! isset($userData['email']) || ! isset($userData['senha']))
        {
            throw new Exception('Faltam campos email e/ou senha.');
        }

        $this->load->helper('checkfix');

        return $this->cms_usuario->do_login(array(
            'email' => $userData['email'],
            'senha' => cf_password($userData['senha'], 6, 20)
        ));
    }

    // -------------------------------------------------------------------------
    /**
     * Gera nova senha, salva e envia email.
     * @param type $email
     * @return boolean
     */
    public function gera_nova_senha($email = null)
    {

        if ($email === null) {
            $email = $this->input->post('login_mail');
        }

        // retorna usuário
        $user = $this->cms_usuario->get(array('email' => $email));

        if (!$user) {
            return false;
        }

        // nova senha
        $this->load->helper('string');
        $str = random_string('alnum', 8);

        // atualiza
        $ret = $this->cms_usuario->update($user['id'], array('senha' => $str));

        $this->send_mail_novasenha($user, $str);

        if ($ret) {
            return true;
        } else {
            return false;
        }
    }

    // -------------------------------------------------------------------------
    /**
     * Recebe dados, monta template e envia email.
     * @param array $user
     * @param string $pass
     * @return boolean
     */
    public function send_mail_novasenha($user, $pass)
    {

        $view['nome']  = $user['nome'];
        $view['senha'] = $pass;

        $tmpl['body'] = $this->load->view('pedidos/emailNovaSenha', $view, true);
        $html         = $this->load->view('template/email', $tmpl, true);
        //        dd($html, true);
        /*
         * instancia library
         */
        $this->load->library('e_mail');

        $emailSite = $this->config->item('email1');
        $assunto   = 'Recuperação de senha';
        $nomeSite  = $this->config->item('title');

        $ret = $this->e_mail->envia($user['email'], $user['nome'], $assunto, $html, $html, $emailSite, $nomeSite);

        return $ret;
    }

    // -------------------------------------------------------------------------

    /**
     * Migração dos clientes do banco de dados antigo para CMS v 4.37.
     */
    public function migracao_clientes()
    {

        $offset = ($this->loop) * $this->step;

        $old = $this->db->select('*')
            ->where('id >', $offset)
            ->limit($this->step)
            ->order_by('id ASC')
            ->get('df_clientes');

        $total = $old->num_rows();

        if ($total == 0 || $this->loop == 3) {
            // avisa que vai parar
            return false;
        }

        $users = $old->result_array();
        $test  = array();
        //        var_dump($total);

        $this->load->helper('checkfix');

        // percorre os olds e adapta
        foreach ($users as $user) {

            unset($user['id']);
            unset($user['pedido']);
            $new           = $user;
            $new['dt_ini'] = $user['dt_cad'];
            $new['cep']    = cf_cep($user['cep']);
            $new['cidade'] = $this->find_city_id($user['cidade']);

            $new['logradouro'] = $user['endereco'];
            $new['tel1']       = tel_to_sql($user['tel1']);
            $new['tel2']       = tel_to_sql($user['tel2']);

            if (strlen($user['cnpj']) == 18) {
                $new['cnpj']  = $user['cnpj'];
                $new['grupo'] = 1;
            } else {
                $new['cnpj']  = '';
                $new['cpf']   = $user['cnpj'];
                $new['grupo'] = 13;
            }

            unset($new['endereco']);
            unset($new['site']);
            unset($new['fax']);
            unset($new['dt_pedido']);
            unset($new['total_pedidos']);
            unset($new['dt_cad']);
            unset($new['tipo']);
            unset($new['news']);

            $test[] = $new;

            $user_id = $this->cms_usuario->insert_update($new, 'email');
        }

        //        mybug($test);

        return true;
    }

    // ----------------------------------------------------------------------
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

        if ($ret->num_rows() > 0) {
            $cidade = $ret->row_array();

            return $cidade['id'];
        } else {
            return '';
        }
    }


    /**
     * Gera o ComboBox das cidades dentro da restrição da variável $uf.
     * Caso a variável $id_city exista marca a opção como selected.
     *
     * @param string $uf : sigla do Estado
     * @param string $id_city : ID da cidade
     * @return string
     */
    function comboCidades($uf = '', $id_city = '')
    {
        if ($uf == '') {
            return '';
        }

        $this->db->order_by('nome');
        $this->db->where('uf', strtoupper($uf));
        $sql = $this->db->get('opt_cidades');

        foreach ($sql->result_array() as $row) {
            $i          = $row['id'];
            $lbl        = $row['nome'];
            $a_rows[$i] = $lbl;
        }

        // Popula o array
        $selected = array($id_city);
        $mark     = array();
        foreach ($selected as $id) {
            if ($id != '')
                $mark[] = $id;
        }

        $drop = form_dropdown('cidade', $a_rows, $mark, 'class="input-cidade" id="cidade"');

        return $drop;

    }

}