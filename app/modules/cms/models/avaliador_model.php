<?php

class Avaliador_model extends MY_Model
{

    /**
     * store the last user got by find()
     * @var null
     */
    protected $userFound = null;

    /**
     * @var int
     */
    protected $avalCategoryId = 13;

    public function __construct()
    {
        $this->load->library(array('cms_usuarios'));
    }

    public function doLogin()
    {

    }

    public function doLogout()
    {

    }

    public function find($id)
    {
        if ($this->userFound !== null)
        {
            return $this->decorateUser($this->userFound);
        }

        $qUser = $this->db->where('id', $id)->get('cms_usuarios');

        if ($qUser->num_rows() == 0)
        {
            return $this->userFound = null;
        }

        $this->userFound = $qUser->row_array();

        return $this->decorateUser($this->userFound);

    }

    public function decorateCollection($users = array())
    {
        if (!is_array($users))
        {
            return null;
        }
        $collection = array();

        foreach ($users as $u)
        {
            $collection[] = $this->decorateUser($u);
        }

        return $collection;
    }

    public function decorateUser($user)
    {
        return $user;
    }

    public function all()
    {
        $qUsers = $this->db->where('grupo', $this->avalCategoryId)
            ->where('status', 1)
            ->get('cms_usuarios');

        if ($qUsers->num_rows() == 0)
        {
            return null;
        }

        return $this->decorateCollection($qUsers->result_array());
    }

    public function update($id, $data = array())
    {

    }

    public function getOwnJobs()
    {

    }
}