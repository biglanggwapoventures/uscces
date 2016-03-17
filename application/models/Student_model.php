<?php

class Student_model extends CI_Model
{

    protected $table = 'users';

    public function __construct()
    {
        parent::__construct();
    }

    public function create($data)
    {
        //set default password
        $data['password'] = md5($data['username']);
        //set student type
        $data['type'] = 's';
        // filter out
        return $this->db->insert($this->table, $data);
        
    }

    public function all($params = NULL, $wildcards = NULL)
    {
        $this->db->select('id, username, firstname, middlename, lastname, year_level, course, status, is_locked');
        $this->db->from($this->table)->where('type', 's');
        if($params){
            $this->db->where($params);        
        }
        if($wildcards){
            $this->db->like($wildcards);        
        }
        return $this->db->order_by('lastname', 'ASC')->get()->result_array();
    }

    public function get($id)
    {
        $this->db->select('id, username, firstname, middlename, lastname, year_level, course, status, dob, gender, is_locked')->from($this->table);
        $this->db->where('id', $id);
        return $this->db->get()->row_array();
    }

    public function update($id, $data)
    {
        foreach($data AS &$field){
            if(!$field){
                $field = NULL;
            }
        }
        return $this->db->update($this->table, $data, ['id' => $id]);
    }

    public function delete($id)
    {
        return $this->db->delete($this->table, ['id' => $id]);
    }

    public function exists($id)
    {
        return $this->db->select('id')->from($this->table)->where('id', $id)->get()->num_rows() === 1;
    }

    public function has_unique_username($name, $id = FALSE)
    {
        if ($id) {
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

    public function is_cleared($id)
    {
        $result = $this->db->select('status')->get_where($this->table, ['id' => $id])->row_array();
        return $result ? (bool)intval($result['status']) : FALSE;
    }

    public function has_active_activity($id)
    {
        $this->db->select('a.name');
        $this->db->from('activities AS a');
        $this->db->join('activity_participants AS ap', 'ap.activity_id = a.id');
        $this->db->where("DATE(a.datetime) >= CURDATE()", FALSE, FALSE);
        $this->db->where(['a.status' => 'a', 'ap.user_id' => $id]);  
        return $this->db->get()->row_array();
    }

}
