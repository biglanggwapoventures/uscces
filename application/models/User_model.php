<?php

class User_model extends CI_Model
{

    protected $table = 'users';

    public $id;
    public $_id;
    
    public function authenticate($username, $password){
        $this->db->select('*')->from($this->table);
        $this->db->where(['username' => $username, 'password' => md5($password)]);
        return $this->db->get()->row_array();
    }

    public function has_joined($user_id, $activity_id)
    {
        $this->db->select('user_id')->from('activity_participants');
        $this->db->where(['user_id' => $user_id, 'activity_id' => $activity_id, 'status !=' => 'w']);
        return $this->db->get()->num_rows() === 1;
    }

    public function exists($id = FALSE)
    {
        $user_id = $id !== FALSE ? $id : $this->id;
        return $this->db->select('id')->from($this->table)->where('id', $user_id)->get()->num_rows() === 1;
    }

    public function get($id = FALSE)
    {
        $user_id = $id !== FALSE ? $id : $this->id;
        return $this->db->get_where($this->table, ['id' => $user_id])->row_array();
    }

    public function get_attended_activities($id = FALSE)
    {
        $user_id = $id !== FALSE ? $id : $this->id;   
    }

    public function mark_cleared($student_id)
    {
        if(is_array($student_id)){
            $this->db->where_in('id', $student_id);
        }else{
            $this->db->where('id', $student_id);
        }
        return $this->db->update($this->table, ['status' => 1]);
    }

    public function update($id, $data)
    {
        if($this->db->update($this->table, $data, ['id' => $id])){
            $this->session->set_userdata($data);
            return TRUE;
        }
        return FALSE;
    }

}
