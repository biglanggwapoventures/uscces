<?php

class Activities extends CES_Controller
{

	protected $active_nav;

	public function __construct()
	{
		parent::__construct();
		$this->load->model('Activity_model', 'activity');
		$this->load->model('User_model', 'user');
	}

	public function index()
	{
		$this->active_nav = NAV_USR_VIEW_ACTIVITIES;
		$this->generate_page('students-corner/activity-list', [
			'items' => $this->activity->get_approved(FALSE, TRUE)
		]);
	}

	public function view($id = FALSE)
    {
    	
        if(!$id || !$this->activity->is_approved($id)){
            show_404();
        }
        $this->active_nav = NAV_USR_VIEW_ACTIVITIES;
        $this->import_page_script('join-activity.js');
        $activity = $this->activity->get($id);
        $this->generate_page('students-corner/activity-view', [
            'form_title' => 'View activity: '.$activity['name'],
            'program_natures' => $this->activity->program_natures(),
            'program_areas' => $this->activity->program_areas(),
            'activity' => $activity,
            'request_status' => $this->activity->get_request_status($id, $this->session->userdata('id'))
        ]);
    }

	public function propose()
	{
		$this->active_nav = NAV_USR_PROPOSE_ACTIVITIES;
		if($this->input->method(TRUE) === 'POST'){
			$this->_perform_validation();
			if($this->form_validation->run()){
				$activity = $this->_format_input();
				if($this->activity->create($activity)){
					redirect('students-corner/activities');
				}
			}
		}
		$this->generate_page('students-corner/activity-propose', [
			'form_title' => 'Propose new activity',
			'program_natures' => $this->activity->program_natures(),
			'program_areas' => $this->activity->program_areas(),
			'activity' => $this->input->post()
		]);
	}


	public function ajax_request_join_activity()
	{
		$activity_id = $this->input->post('id');
		$this->output->set_content_type('json');
		if(!$activity_id || !$this->activity->is_approved($activity_id)){
			 $this->output->set_output(json_encode(['result' => FALSE, 'message' => 'Please provide an approved activity id!']));
			 return;
		}
		if($this->user->has_joined($this->session->userdata('id'), $activity_id)){
			$this->output->set_output(json_encode([
				'result' => FALSE, 
				'message' => 'You have already joined or requested to join this activity!'
			]));
			return;
		}
		if($this->activity->is_open($activity_id)){
			$this->activity->join($activity_id, $this->session->userdata('id'));
			$this->output->set_output(json_encode([
				'result' => TRUE, 
				'message' => 'Your request has been received and is waiting for approval!'
			]));
		}else{
			$this->output->set_output(json_encode(['result' => FALSE, 'message' => 'The activity is already full!']));
		}
	}

	public function ajax_withdraw_activity()
	{
		$activity_id = $this->input->post('id');
		$this->activity->withdraw($activity_id, $this->session->userdata('id'));
		$this->output->set_content_type('json')->set_output(json_encode(['result' => TRUE, 'message' => 'You have successfully withdrawn from the activity!']));
	}

	public function _perform_validation()
	{
		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('description', 'Description', 'required');
		$this->form_validation->set_rules('location', 'Location', 'required');
		$this->form_validation->set_rules('datetime', 'Date and time', 'callback__validate_datetime');
		$this->form_validation->set_rules('population', 'Population', 'required|integer|greater_than[0]');
		$this->form_validation->set_rules('nature_id', 'Nature of the activity', 'callback__validate_nature_id');
		$this->form_validation->set_rules('area_id', 'Activity area', 'callback__validate_area_id');
	}

	public function _format_input($mode = 'c')
	{
		$activity = elements(['name', 'description', 'location', 'population', 'nature_id', 'area_id'], $this->input->post());
		// set formatted date for timestamp data type
		$activity['datetime'] = date('Y-m-d H:i:s', strtotime($this->input->post('datetime')));
		$activity['type'] = 'p';
		if($mode === 'c'){
			$activity['created_by'] = $this->session->userdata('id');
		}
		return $activity;
	}

	public function _validate_datetime($datetime)
	{
		$this->load->helper('cesdate');
		$this->form_validation->set_message('_validate_datetime', 'Please input a valid datetime with format: Y-m-d hh:mm am/pm');
		return $datetime && is_valid_date($datetime, 'Y-m-d h:i a');
	}

	public function _validate_nature_id($id)
	{
		$this->form_validation->set_message('_validate_nature_id', 'Please select a valid %s');
		return $this->activity->has_valid_program_nature($id);
	}

	public function _validate_area_id($id)
	{
		$this->form_validation->set_message('_validate_area_id', 'Please select a valid %s');
		return $this->activity->has_valid_program_area($id);
	}

}