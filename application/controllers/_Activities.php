<?php

class Activities extends CES_Controller
{

    protected $tab_title = 'Activities';
    protected $active_nav;

    public function __construct()
    {
        parent::__construct();
        if(!in_array($this->session->userdata('type'),  ['su', 'f'])){
            redirect('dashboard');
        }
        $this->load->model('Activity_model', 'activity');
    }

    public function index()
    {
        $this->active_nav = NAV_ADM_APPROVED_ACTIVITIES;
        $this->import_page_script('activity-listing.js');
        $this->generate_page('activities/listing', [
            'items' => $this->activity->get_approved(),
            'type' => 'a'
        ]); 
    }

    public function create()
    {
        $this->active_nav = NAV_ADM_APPROVED_ACTIVITIES;
        if($this->input->method(TRUE) === 'POST'){
            $this->_perform_validation('c');
            if($this->form_validation->run()){
                $activity = $this->_format_input();
                $updated = $this->activity->create($activity);
                if($updated){
                    redirect('activities');
                }
            }
        }
        $activity = $this->input->post();
        $this->generate_page('activities/manage', [
            'form_title' => 'Create new activity',
            'mode' => 'c',
            'program_natures' => $this->activity->program_natures(),
            'program_areas' => $this->activity->program_areas(),
            'activity' => $activity
        ]);
    }

    

    public function view_proposed()
    {
        $this->active_nav = NAV_ADM_PROPOSED_ACTIVITIES;
        $this->generate_page('activities/listing', [
            'items' => $this->activity->get_proposed(),
            'type' => 'p'
        ]); 
    }

    public function update($id = FALSE)
    {
        if(!$id || !$this->activity->exists($id)){
            show_404();
        }
        if($this->input->get('s') == 'a'){
            $this->active_nav = NAV_ADM_APPROVED_ACTIVITIES;
            $url_on_success = 'activities';
        }else{
            $this->active_nav = NAV_ADM_PROPOSED_ACTIVITIES;
            $url_on_success = 'activities/view_proposed';
        }
        if($this->input->method(TRUE) === 'POST'){
            $this->_perform_validation();
            if($this->form_validation->run()){
                $activity = $this->_format_input('u');
                $this->activity->update($id, $activity);
                redirect($url_on_success);
            }
            $activity = $this->input->post();
            $activity['id'] = $id;
        }else{
            $activity = $this->activity->get($id);
        }
        $this->generate_page('activities/manage', [
            'form_title' => 'View / update activity',
            'mode' => 'u',
            'program_natures' => $this->activity->program_natures(),
            'program_areas' => $this->activity->program_areas(),
            'activity' => $activity
        ]);
    }

    public function view_participants($id = FALSE)
    {
        if(!$id || !$this->activity->is_approved($id)){
            show_404();
        }
        $this->active_nav = NAV_ADM_APPROVED_ACTIVITIES;
        $this->import_page_script('manage-requests.js');
        $activity = $this->activity->get($id);
        $this->generate_page('activities/participants', [
            'form_title' => "View all participants for: {$activity['name']}",
            'participants' => $this->activity->get_participants($id),
            'activity_id' => $id
        ]);

    }

    public function manage_requests($id = FALSE)
    {
        if(!$id || !$this->activity->is_approved($id)){
            show_404();
        }
        $this->active_nav = NAV_ADM_APPROVED_ACTIVITIES;
        $this->import_page_script('manage-requests.js');
        $activity = $this->activity->get($id);
        $this->generate_page('activities/join-requests', [
            'form_title' => "View all join requests for: {$activity['name']}",
            'activity_id' => $id,
            'participants' => $this->activity->get_join_requests($id)
        ]);

    }

    public function ajax_approve_students($id = FALSE)
    {
        $this->output->set_content_type('json');
        if(!$id || !$this->activity->is_approved($id)){
            $this->output->set_output(json_encode([
                'result' => FALSE,
                'message' => 'Please provide an approved activity id!'
            ]));
            return;
        }
        $applicants = $this->input->post('applicants');
        if((is_array($applicants) && $applicants === array_filter($applicants, 'is_numeric')) || is_numeric($applicants)){
            $valid = $this->activity->check_if_valid_applicants($id, $applicants);
            if(!$valid){
                $this->output->set_output(json_encode([
                    'result' => FALSE,
                    'message' => 'Only students who requested to join can be accepted!'
                ]));
                return;
            }
            $applicants_count = is_array($applicants) ? count($applicants) : 1;
            $activity = $this->activity->get($id);
            $free_slots = $activity['population'] - $activity['participants_count'];
            if($free_slots < $applicants_count){
                $this->output->set_output(json_encode([
                    'result' => FALSE,
                    'message' => "Cannot approve students: Remaining slots {$free_slots}, attempted to approve {$applicants_count} students."
                ]));
                return;
            }
            $this->activity->approve_join_requests($id, $applicants);
            $this->output->set_output(json_encode([
                'result' => TRUE
            ]));
            
        }
    }

    public function ajax_remove_students($id = FALSE)
    {
        $this->output->set_content_type('json');
        if(!$id || !$this->activity->exists($id)){
            $this->output->set_output(json_encode([
                'result' => FALSE,
                'message' => 'Please provide an activity id!'
            ]));
            return;
        }
        $applicants = $this->input->post('applicants');
        if((is_array($applicants) && $applicants === array_filter($applicants, 'is_numeric')) || is_numeric($applicants)){
            $valid = $this->activity->check_if_valid_applicants($id, $applicants, FALSE);
            if(!$valid){
                $this->output->set_output(json_encode([
                    'result' => FALSE,
                    'message' => "Only students of the activity can be removed."
                ]));
                return;
            }
            $this->activity->remove_students($id, $applicants);
            $this->output->set_output(json_encode([
                'result' => TRUE
            ]));
        }
    }

    public function ajax_mark_students_cleared($id = FALSE)
    {
        $this->output->set_content_type('json');
        if(!$id || !$this->activity->is_approved($id)){
            $this->output->set_output(json_encode([
                'result' => FALSE,
                'message' => 'Please provide an approved activity id!'
            ]));
            return;
        }
        $applicants = $this->input->post('applicants');
        if((is_array($applicants) && $applicants === array_filter($applicants, 'is_numeric')) || is_numeric($applicants)){
            $this->load->model('User_model', 'user');
            $valid = $this->activity->check_if_valid_applicants($id, $applicants, FALSE);
            if(!$valid){
                $this->output->set_output(json_encode([
                    'result' => FALSE,
                    'message' => 'Only students of the activity can be marked as cleared.'
                ]));
                return;
            }
            $this->user->mark_cleared($applicants);
            $this->output->set_output(json_encode([
                'result' => TRUE
            ]));
        }
    }

    public function _perform_validation($mode = 'c')
    {
        $this->form_validation->set_rules('name', 'Name', 'required');
        $this->form_validation->set_rules('description', 'Description', 'required');
        $this->form_validation->set_rules('location', 'Location', 'required');
        $this->form_validation->set_rules('datetime', 'Date and time', 'callback__validate_datetime');
        $this->form_validation->set_rules('population', 'Population', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('nature_id', 'Nature of the activity', 'callback__validate_nature_id');
        $this->form_validation->set_rules('area_id', 'Activity area', 'callback__validate_area_id');
        $this->form_validation->set_rules('facilitator', 'Facilitator', 'required');
        $this->form_validation->set_rules('status', 'Status', 'callback__validate_status');
        
    }

    public function _format_input($mode = 'c')
    {
        $activity = elements(['name', 'description', 'location', 'population', 'nature_id', 'area_id', 'facilitator', 'status'], $this->input->post());
        // set formatted date for timestamp data type
        $activity['datetime'] = date('Y-m-d H:i:s', strtotime($this->input->post('datetime')));
        if($mode === 'c'){
            $activity['type'] = 'a';
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

    public function _validate_status($status)
    {
        $this->form_validation->set_message('_validate_status', 'Please select a valid status');
        return in_array($status, ['a', 'p', 'd']);
    }

}
