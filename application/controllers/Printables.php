<?php 

class Printables extends CES_Controller
{

	public function __construct()
	{
		parent::__construct();

	}

	public function form($activity_id = FALSE)
	{
		if(user_type(USER_TYPE_STUDENT)){
			show_404();
		}
		$this->load->model('Activity_model', 'activity');
		if($this->activity->exists($activity_id)){
			$activity = $this->activity->view_activity($activity_id, FALSE);
			$participants = $this->activity->get_participants($activity_id);
			$this->load->view('printables/form', compact(['activity', 'participants']));
		}else{
			show_404();
		}
		
	}

	public function certificate($activity_id = FALSE, $user_id = FALSE)
	{
		if(!$activity_id || !$user_id){
			show_404();
		}
		$this->load->model('Activity_model', 'activity');
		if($this->activity->exists($activity_id)){
			if($this->activity->is_finished($activity_id) && $this->activity->has_participant_with_id($activity_id, $user_id)){
				$this->load->model('User_model', 'user');
				$user = $this->user->get($user_id);
				$activity = $this->activity->get($activity_id);
				$this->load->view('printables/certificate', compact(['user', 'activity']));
			}else{
				show_404();
			}
		}else{
			show_404();
		}
	}

	public function test(){
		$this->load->model('Activity_model', 'activity');
		$this->activity->sample();
	}
}