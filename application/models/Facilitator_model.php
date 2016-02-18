<?php

class Facilitator_model extends CI_Model
{
	protected $table = 'users';
    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {
        $data['type'] = 'f';
        $data['password'] = md5($data['username']);
        return $this->db->insert($this->table, $data) ? $this->db->insert_id() : FALSE;
    }

    public function all($fields = [])
    {
        if(count($fields)){
            $this->db->select(implode(', ', $fields));
        }
        $this->db->where('type', 'f');
    	return $this->db->get($this->table)->result_array();
    }

    public function get($id, $fields = [])
    {
        if(count($fields)){
            $this->db->select(implode(', ', $fields));
        }
        $this->db->where('type', 'f');
        return $this->db->get_where($this->table, ['id' => $id])->row_array();
    }

    public function update($id, $data)
    {
        unset($data['type']); // ensure type is not ever updated
    	return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function exists($id)
    {
        return $this->db->select('id')->from($this->table)->where('id', $id)->get()->num_rows() === 1;
    }

     public function has_unique_username($name, $id = FALSE)
    {
        if($id)
        {
            $this->db->where('id !=', $id);
        }
        return $this->db->select('id')->from($this->table)->where('username', $name)->get()->num_rows() === 0;
    }

    public function reset_password($id)
    {
        $this->db->set('password', 'MD5(username)', FALSE);
        $this->db->where('id', $id);
        return $this->db->update($this->table);
    }
}