<?php
namespace Gestalt;


class Congresso5 extends Congresso {

    /**
     * @var array
     */
    protected $temas = array(
        array(
            'id' => 1,
            'title' => 'Articulação dos conceitos gestálticos com o existir humano em diferentes contextos'
        ),
        array(
            'id' => 2,
            'title' => 'A visão de homem na Gestalt-terapia como possibilidade para o desenvolvimento de projetos de atenção básica na saúde mental'
        ),
        array(
            'id' => 3,
            'title' => 'A Gestalt-terapia no mundo contemporâneo: atualização teórica e prática'
        ),
    );


    /**
     * @var array
     */
    protected $modalidades = array(
        array(
            'id' => 1,
            'title' => 'Mesa-redonda (MR)'
        ),
        array(
            'id' => 2,
            'title' => 'Tema-livre (TL)'
        ),
        array(
            'id' => 3,
            'title' => 'Minicurso (MC)'
        ),
        array(
            'id' => 4,
            'title' => 'Workshop (WS)'
        ),
        array(
            'id' => 5,
            'title' => 'Experiências Gestálticas (EG)'
        ),
        array(
            'id' => 6,
            'title' => 'Pôster (PO)'
        ),
    );


    /**
     * ID do módulo no CMS
     * @var int
     */
    protected $modulo_id = 66;

    protected $moduloInscriptionId = 21;
    protected $conteudo_id = 229;

    /**
     *
     */
    function __construct()
    {
        parent::__construct();
    }


}