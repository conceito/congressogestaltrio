<?php

class Institution
{

    protected $table = array(
        'contato' => array(
            'name'    => 'Contato | Núcleo de Estudos e Aplicação da Gestalt-Terapia',
            'phone'   => '(21) 2137-7709 / (21) 7617-0221',
            'address' => 'Avenida das Américas, 3333 / 607 | Barra da Tijuca',
            'email'   => '-',
            'bank'    => array(
                'name'    => 'Banco Santander',
                'agency'  => '4679',
                'account' => '13003011-9 ',
                'doc'   => 'CNPJ: 10.842.244/0001-75',
                'owner'   => 'Contato - Núcleo de Estudos e Aplicação da GT'
            )
        ),
        'dialogo' => array(
            'name'    => 'Dialógico | Núcleo de Gestalt-terapia',
            'phone'   => '(21) 25215644',
            'address' => 'Avenida Nossa Senhora de Copacabana, 1183 cob. 01 | Copacabana ',
            'email'   => '-',
            'bank'    => array(
                'name'    => 'Banco Itaú',
                'agency'  => '3820',
                'account' => '02570-2',
                'doc'   => 'CPF: 943915567-15',
                'owner' => 'Luciana de Medeiros Aguiar'
            )
        ),
        'gestalt' => array(
            'name'    => 'Instituto de Psicologia Gestalt em Figura',
            'phone'   => '(21) 2205-9021',
            'address' => '',
            'email'   => '-',
            'bank'    => array(
                'name'    => 'Banco Itaú',
                'agency'  => '0416 ',
                'account' => '46464-2',
                'doc'   => 'CNPJ: 05.967.197/0001-56',
                'owner' => 'Instituto de Psicologia Gestalt em Figura Ltda.'
            )
        ),
        'igt'     => array(
            'name'    => 'IGT | Instituto de Gestalt-Terapia e Atendimento Familiar',
            'phone'   => '(21) 2567-1038 / (21) 2569-2650',
            'address' => 'Rua Haddock Lobo, 369, sl. 709 | Tijuca',
            'email'   => '-',
            'bank'    => array(
                array(
                    'name'    => 'Banco do Brasil',
                    'agency'  => '3096-1',
                    'account' => '13175-x',
                    'doc'   => 'CNPJ: 1.346.094/0001-18',
                    'owner' => 'Ps Assessoria em Psicologia SC Ltda.'
                ),
                array(
                    'name'    => 'Banco Itaú',
                    'agency'  => '0598',
                    'account' => '56442-5',
                    'doc'   => 'CNPJ: 1.346.094/0001-18',
                    'owner' => 'Ps Assessoria em Psicologia SC Ltda.'
                ),

            )
        ),
    );

    protected $institutionId;

    function __construct($institutionId = null)
    {
        if ($institutionId) {
            $this->institutionId = $institutionId;
        }
    }

    /**
     * @param mixed $institutionId
     */
    public function setInstitutionId($institutionId)
    {
        $this->institutionId = $institutionId;
    }

    /**
     * @return mixed
     */
    public function getInstitutionId()
    {
        return $this->institutionId;
    }

    public function getInstance($institutionId = null){
        if ($institutionId) {
            $this->setInstitutionId($institutionId);
        }

        return $this;
    }

    public function get($institutionId = null)
    {
        if ($institutionId) {
            $this->setInstitutionId($institutionId);
        }

        return $this->table[$this->getInstitutionId()];

    }

    public function getName()
    {
        $tb = $this->get();
        return $tb['name'];
    }
    public function getPhone()
    {
        $tb = $this->get();
        return $tb['phone'];
    }
    public function getAddress()
    {
        $tb = $this->get();
        return $tb['address'];
    }
    public function getEmail()
    {
        $tb = $this->get();
        return $tb['email'];
    }
    public function getBank()
    {
        $tb = $this->get();
        return $tb['bank'];
    }

}