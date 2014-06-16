<?php
namespace Gestalt;

abstract class Congresso
{

    /**
     * @var array
     */
    protected $temas = array();

    /**
     * @var array
     */
    protected $modalidades = array();

    /**
     * ID do módulo no CMS
     * @var int
     */
    protected $modulo_id = 0;

    /**
     * @var int
     */
    protected $moduloInscriptionId = 0;

    /**
     * @var int
     */
    protected $conteudo_id = 0;

    /**
     *
     */
    function __construct()
    {
    }

    /**
     * @return int
     */
    public function getModuloId()
    {
        return $this->modulo_id;
    }

    /**
     * ID of the content module
     * @return int
     */
    public function getModuloInscriptionId()
    {
        return $this->moduloInscriptionId;
    }

    /**
     * ID of the event 'cms_conteudo'
     * @return int
     */
    public function getConteudoId()
    {
        return $this->conteudo_id;
    }

    /**
     * @return array
     */
    public function allTemas()
    {
        return $this->temas;
    }

    /**
     * @param $id
     * @return array
     */
    public function getTema($id)
    {

        $selected = array();
        foreach ($this->temas as $t)
        {
            if ($t['id'] == $id)
            {
                $selected = $t;
            }
        }

        return $selected;
    }

    /**
     * @return array
     */
    public function allModalidades()
    {
        return $this->modalidades;
    }

    /**
     * @param $id
     * @return array
     */
    public function getModalidade($id)
    {

        $selected = array();
        foreach ($this->modalidades as $m)
        {
            if ($m['id'] == $id)
            {
                $selected = $m;
            }
        }

        return $selected;
    }

}