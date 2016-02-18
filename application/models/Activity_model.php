<?php

class Activity_model extends CI_Model
{

	protected $table = 'activities';

	public function program_areas()
	{
		return $this->db->select('id,name')->from('activity_program_areas')->get()->result_array();
	}

	public function program_natures()
	{
		return $this->db->select('id,name')->from('activity_program_natures')->get()->result_array();
	}

	public function has_valid_program_area($id)
	{
		return $this->db->select('id')->from('activity_program_areas')->where('id', $id)->get()->num_rows() > 0;
	}

	public function exists($id)
	{
		return $this->db->select('id')->from($this->table)->where('id', $id)->get()->num_rows() > 0;
	}

	public function is_approved($id)
	{
		return $this->db->select('id')->from($this->table)->where(['id' => $id, 'status' => 'a'])->get()->num_rows() > 0;
	}

	public function has_valid_program_nature($id)
	{
		return $this->db->select('id')->from('activity_program_natures')->where('id', $id)->get()->num_rows() > 0;
	}

	public function is_open($id)
	{
		$population = $this->db->select('population')->from($this->table)->where('id', $id)->get()->row_array()['population'];
		$this->db->select('COUNT(user_id) AS participants', FALSE)->from('activity_participants');
		$this->db->where([
			'status' => 'a',
			'activity_id' => $id
		]);
		$participants = $this->db->get()->row_array()['participants'];
		return $population > $participants;
	}

	public function get_request_status($id, $user_id)
	{
		return $this->db->get_where('activity_participants', [
			'activity_id' => $id,
			'user_id' => $user_id
		])->row_array();
	}

	public function all()
	{
		$this->db->select('a.*, CONCAT(u.lastname, ", ", u.firstname, " ", u.middlename) AS created_by, '.
			' COUNT(CASE WHEN pu.type = "s" THEN ap.user_id ELSE NULL END) AS student_count', FALSE);
		$this->db->from($this->table.' AS  a');
		$this->db->join('activity_participants AS ap', 'ap.activity_id = a.id', 'left');
		$this->db->join('users AS pu', 'pu.id = ap.user_id', 'left');
		$this->db->join('users AS u', 'u.id = a.created_by');
		$this->db->group_by('a.id');
		$this->db->order_by('a.datetime', 'DESC');
		return $this->db->get()->result_array();
	}

	public function create($data, $facilitate = FALSE)
	{
		$this->db->trans_start();

		$this->db->insert($this->table, $data);

		if($facilitate && $facilitate['action'] === 'join'){
			$id = $this->db->insert_id();
			$this->db->insert('activity_participants', ['user_id' => $facilitate['id'], 'activity_id' => $id]);
		}

		$this->db->trans_complete();

		return $this->db->trans_status();
		 
	}

	public function update($id, $data, $facilitate = FALSE)
	{

		$this->db->trans_start();

		$this->db->update($this->table, $data, ['id' => $id]);

		if($facilitate && $facilitate['action'] === 'join'){
			$this->join($id, $facilitate['id']);
		}else if($facilitate && $facilitate['action'] === 'leave'){
			$this->leave($id, $facilitate['id']);
		}

		$this->db->trans_complete();
		return $this->db->trans_status();
	}

	public function get($id)
	{
		$data = $this->db->get_where($this->table, ['id' => $id])->row_array();
		return $data;
	}

	public function get_participants($id)
	{
		$this->db->select('u.id, u.username, CONCAT(u.lastname, ", ", u.firstname, " ", u.middlename) AS fullname, u.status, ap.contact_number', FALSE);
		$this->db->from('activity_participants AS ap');
		$this->db->join('users AS u', 'u.id = ap.user_id', 'left', FALSE);
		$this->db->where('u.type', 's');
		$this->db->order_by('lastname');
		return $this->db->where(['activity_id' => $id])->get()->result_array();
	}

	public function remove_students($id, $student_id)
	{
		if(is_array($student_id)){
			$this->db->where_in('user_id', $student_id);
		}else{
			$this->db->where('user_id', $student_id);
		}
		$this->db->where('activity_id', $id);
		return $this->db->delete('activity_participants');
	}

	/* FUNCTIONS FOR USE OF STUDENTS */

	// master list of proposed activities by user id
	public function get_proposed($student_id)
	{
		$this->db->select('a.id, a.name, a.created_at, a.status');
		$this->db->from($this->table. ' AS a');
		$this->db->where(['a.type' => 'p', 'created_by' => $student_id]);	
		$this->db->order_by('a.created_at', 'DESC');
		return $this->db->get()->result_array();
	}

	public function view_proposed($student_id, $id)
	{
		return $this->db->get_where($this->table, ['id' => $id, 'created_by' => $student_id])->row_array();
	}

	// get all activities that are available for students to join
	public function get_approved()
	{
		$this->db->select('a.id, a.name, a.datetime, a.location, a.population, '.
			' COUNT(CASE WHEN u.type = "s" THEN ap.user_id ELSE NULL END) AS student_count', FALSE);
		$this->db->from($this->table. ' AS a');
		$this->db->join('activity_participants AS ap', 'ap.activity_id = a.id', 'left');
		$this->db->join('users AS u', 'u.id = ap.user_id', 'left');
		$this->db->where("DATE(a.datetime) >= CURDATE()", FALSE, FALSE);
		$this->db->where('a.status', 'a');	
		$this->db->order_by('a.datetime', 'DESC');
		$this->db->group_by('a.id');
		return $this->db->get()->result_array();
	}

	// view activity
	public function view_activity($id)
	{
		$this->db->select('a.*, apn.name AS nature, apa.name AS area, '.
			'COUNT(CASE WHEN u.type = "s" THEN ap.user_id ELSE NULL END) AS student_count', FALSE);
		$this->db->from($this->table. ' AS a, activity_program_areas AS apa, activity_program_natures AS apn');
		$this->db->join('activity_participants AS ap', 'ap.activity_id = a.id', 'left');
		$this->db->join('users AS u', 'u.id = ap.user_id', 'left');
		$this->db->where('a.nature_id = apn.id AND a.area_id = apa.id', FALSE, FALSE);
		$this->db->where("DATE(a.datetime) >= CURDATE()", FALSE, FALSE);
		$this->db->where(['a.status' => 'a', 'a.id' => $id]);	
		return $this->db->get()->row_array();
	}

	public function is_available($id)
	{
		// first check if activity is approved and is still ongoing
		$this->db->select('a.id, a.population')->from($this->table.' AS a');
		$this->db->where("DATE(a.datetime) >= CURDATE()", FALSE, FALSE);
		$this->db->where(['a.id' => $id, 'a.status' => 'a']);
		$activity = $this->db->get()->row_array();

		if(!$activity){
			return FALSE;
		}

		// now check for current population
		$this->db->select('COUNT(ap.user_id) AS student_count', FALSE)->from('activity_participants AS ap, users AS u');
		$this->db->where('u.id = ap.user_id', FALSE, FALSE)->where(['activity_id' => $id, 'u.type' => 's']);
		$participants = $this->db->get()->row_array();

		// check if still available
		return $activity['population'] > $participants['student_count'];
	}

	public function join($id, $user_id, $mobile = FALSE)
	{
		return $this->db->replace('activity_participants', [
			'activity_id' => $id,
			'user_id' => $user_id,
			'contact_number' => $mobile ? $mobile : NULL
		]);
	}

	public function leave($id, $user_id)
	{
		return $this->db->delete('activity_participants', [
			'activity_id' => $id,
			'user_id' => $user_id
		]);
	}

	public function has_participant_with_id($id, $student_id)
	{
		return $this->db->select('user_id')->from('activity_participants')->where(['user_id' => $student_id, 'activity_id' => $id])->get()->num_rows() > 0;
	}

	public function check_if_valid_applicants($id, $student_id){
		$this->db->select('COUNT(user_id) AS num', FALSE)->from('activity_participants');
		$this->db->where('activity_id', $id);
		if(is_array($student_id)){
			$this->db->where_in('user_id', $student_id);
		}else{
			$this->db->where('user_id', $student_id);
		}
		$result = $this->db->get()->row_array();
		$count = intval($result['num']);
		return is_array($student_id) ? $count === count($student_id) : $count > 0;
	}

	public function get_date($id)
	{
		return $this->db->select('datetime')->get_where($this->table, ['id' => $id])->row_array();
	}

	public function get_facilitators($id)
	{
		$this->db->select('CONCAT(u.firstname, " ", u.middlename, " ", u.lastname) AS fullname, email, mobile', FALSE);
		$this->db->from('activity_participants AS ap');
		$this->db->join('users AS u', 'u.id = ap.user_id');
		$this->db->where(['ap.activity_id' => $id, 'u.type !=' => 's']);
		return $this->db->get()->result_array();
	}

	public function get_proposed_count($student_id)
	{
		$result = $this->db->select('COUNT(id) AS `count`', FALSE)->from($this->table)->where(['type' => 'p', 'created_by' => $student_id])->get()->row_array();
		return $result['count'];
	}

	public function get_attended($student_id)
	{
		$result = $this->db->select('activity_id')->from('activity_participants')->where('user_id', $student_id)->get()->result_array();

		if(!$result){
			return [];
		}

		$this->db->select('name, datetime, location', FALSE)->from($this->table);
		$this->db->where('status', 'a')->where('DATE(`datetime`) < CURDATE()', FALSE, FALSE);
		$this->db->where_in('id', array_column($result, 'activity_id'));
		$activities = $this->db->get()->result_array();

		return $activities;
	}
}