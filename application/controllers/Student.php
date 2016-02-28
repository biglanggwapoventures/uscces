<?php

class Student extends CES_Controller
{
	protected $user_id;
	protected $tab_title = 'Activities';
	protected $active_nav;

	public function __construct()
	{
		parent::__construct();
        if($this->session->userdata('type') !== 's'){
            show_404();
        }
		$this->load->model('Activity_model', 'activity');
		$this->user_id = $this->session->userdata('id');
	}

	public function view_activities()
	{
		$this->active_nav = NAV_USR_VIEW_ACTIVITIES;
		$this->generate_page('student/list-activities', [
			'items' => $this->activity->get_approved()
		]);
	}

	public function view_activity($id = FALSE)
	{
		if(!$id || !$activity = $this->activity->view_activity($id)){
			show_404();
		}
        $this->active_nav = NAV_USR_VIEW_ACTIVITIES;
		$this->import_page_script('student-view-activity.js');
		$this->generate_page('student/view-activity', [
			'data' => $activity,
			'is_participant' => $this->activity->has_participant_with_id($id, $this->user_id),
            'facilitators' => $this->activity->get_facilitators($id)
		]);
	}

	public function join_activity()
	{
        $this->form_validation->set_rules('id', 'activity', 'required|callback__validate_activity');
        $this->form_validation->set_rules('mobile', 'mobile', 'required|numeric|exact_length[11]');
        if(!$this->form_validation->run()){
            $this->json_response(['result' => FALSE, 'messages' => array_values($this->form_validation->error_array())]);
            return;
        }
		if($this->activity->join($this->input->post('id'), $this->user_id, $this->input->post('mobile'))){
			$this->json_response(['result' => TRUE]);
			return;
		}
		$this->json_response(['result' => FALSE, 'messages' => ['Cannot perform action due to an unknown error. Please try again later.']]);
	}

    public function _validate_activity($id)
    {
        if(!$this->activity->is_available($id)){
            $this->form_validation->set_message('_validate_activity', 'Failed to join activity.');
            return FALSE;
        }  
        $this->load->model('Student_model', 'student');
        if($result = $this->student->has_active_activity($this->user_id)){
            $this->form_validation->set_message('_validate_activity', "You are already registered in another activity: \"{$result['name']}\"");
            return FALSE;
        }   
        return TRUE;
    }

    public function leave_activity()
    {
        $id = $this->input->post('id');
        if(!$this->activity->has_participant_with_id($id, $this->user_id)){
            $this->json_response(['result' => FALSE, 'messages' => ['You are not in the activity.']]);
            return;
        }

        $result = $this->activity->get_date($id);
        $today = date_create(date('Y-m-d'));
        $activity_date = date_create(date('Y-m-d', strtotime($result['datetime'])));
        $diff = date_diff($today, $activity_date)->format('%a');
        if((int)$diff <= 3){
            $this->json_response(['result' => FALSE, 'messages' => ['Failed to leave: Activity starts at '.$diff.' days from now']]);
            return;
        }

        if($this->activity->leave($id, $this->user_id)){
            $this->json_response(['result' => TRUE]);
            return;
        }
        $this->json_response(['result' => FALSE, 'messages' => ['Cannot perform action due to an unknown error. Please try again later.']]);
    }

	public function track_proposals()
	{
		$this->active_nav = NAV_USR_TRACK_PROPOSALS;
		$this->generate_page('student/proposals-list', [
			'items' => $this->activity->get_proposed($this->user_id)
		]);
	}

	public function propose_activity()
	{
		$this->active_nav = NAV_USR_PROPOSE_ACTIVITIES;
		$this->import_plugin_script([
            'bootstrap-wysiwyg/bootstrap3-wysihtml5.all.min.js', 
            'moment.min.js', 
            'bootstrap-datetimepicker/bs-datetimepicker.min.js'
        ]);
        $this->import_page_script('manage-activity.js');
        $this->generate_page('student/propose-activity', [
            'form_title' => 'Propose new activity',
            'action' => ACTION_CREATE,
            'program_natures' => $this->activity->program_natures(),
            'program_areas' => $this->activity->program_areas(),
            'data' => []
        ]);
	}

	public function edit_proposed_activity($id = FALSE)
	{
		if(!$id || !$activity = $this->activity->view_proposed($this->user_id, $id)){
            show_404();
        }
        $this->active_nav = NAV_USR_TRACK_PROPOSALS;
        $this->import_plugin_script([
            'bootstrap-wysiwyg/bootstrap3-wysihtml5.all.min.js', 
            'moment.min.js', 
            'bootstrap-datetimepicker/bs-datetimepicker.min.js'
        ]);
        $this->import_page_script('manage-activity.js');
        $this->generate_page('student/propose-activity', [
            'form_title' => "Update proposed activity: {$activity['name']}",
            'action' => ACTION_UPDATE,
            'program_natures' => $this->activity->program_natures(),
            'program_areas' => $this->activity->program_areas(),
            'data' => $activity
        ]);

	}

	public function store_proposed_activity()
    {
        $this->_perform_validation();
        if(!$this->form_validation->run()){
            $this->json_response(['result' => FALSE, 'messages' => array_values($this->form_validation->error_array())]);
            return;
        }
        $input = $this->_format_data(ACTION_CREATE);
        $created = $this->activity->create($input);
        if($created){
            $this->json_response(['result' => TRUE]);
            return;
        }
        $this->json_response(['result' => FALSE, 'messages' => ['Cannot perform action due to an unknown. Please try again later.']]);
    }

    public function update_proposed_activity($id = FALSE)
    {
        if(!$id || !$activity = $this->activity->view_proposed($this->user_id, $id)){
            $this->json_response(['result' => FALSE, 'messages' => ['Please choose an activity to update.']]);
            return;
        }
        if($activity['status'] === 'a' || $activity['status'] === 'd'){
    	 	$this->json_response(['result' => FALSE, 'messages' => ['Approved and denied proposals cannot be updated.']]);
            return;
        }
        $this->_perform_validation();
        if(!$this->form_validation->run()){
            $this->json_response(['result' => FALSE, 'messages' => array_values($this->form_validation->error_array())]);
            return;
        }
        $input = $this->_format_data(ACTION_UPDATE);
        $updated = $this->activity->update($id, $input);
        if($updated){
            $this->json_response(['result' => TRUE]);
            return;
        }
        $this->json_response(['result' => FALSE, 'messages' => ['Cannot perform action due to an unknown. Please try again later.']]);
    }

    public function _perform_validation()
    {
        $this->form_validation->set_rules('name', 'name', 'required');
        $this->form_validation->set_rules('description', 'description', 'required');
        $this->form_validation->set_rules('location', 'location', 'required');
        $this->form_validation->set_rules('datetime', 'date and time', 'callback__validate_datetime');
        $this->form_validation->set_rules('population', 'population', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('nature_id', 'nature of the activity', 'callback__validate_nature_id');
        $this->form_validation->set_rules('area_id', 'area of the activity', 'callback__validate_area_id');
    }

    public function _format_data($action = ACTION_CREATE)
    {
        $data = elements(['name', 'description', 'location', 'population', 'nature_id', 'area_id'], $this->input->post());
        // set formatted date for timestamp data type
        $data['datetime'] = date('Y-m-d H:i:s', strtotime($this->input->post('datetime')));
        if($action === ACTION_CREATE){
            $data['type'] = 'p';
            $data['status'] = 'p';
            $data['created_by'] = $this->session->userdata('id');
        }
        return $data;
    }

    public function _validate_datetime($datetime)
    {
        $this->load->helper('cesdate');
        $this->form_validation->set_message('_validate_datetime', 'Please input a valid datetime with format: m/d/Y hh:mm AM/PM');
        return $datetime && is_valid_date($datetime, 'm/d/Y h:i A');
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