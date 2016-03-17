<?php

class Activities extends CES_Controller
{

    protected $tab_title = 'Activities';
    protected $active_nav = NAV_ADM_ACTIVITIES;

    protected $id;

    public function __construct()
    {
        parent::__construct();
        if(!in_array($this->session->userdata('type'),  ['su', 'f'])){
            redirect('dashboard');
        }
        $this->load->model('Activity_model', 'activity');
    }

    public function _search_params()
    {
        $search = [];
        $wildcards = [];
        $category = '';
        $params = elements(['name', 'start_date', 'end_date', 'category'], $this->input->get(), NULL);
        if($params['name']){
            $wildcards['a.name'] = $params['name'];
        }
        if($params['start_date'] && is_valid_date($params['start_date'], 'm/d/Y')){
            $search['DATE(a.datetime) >='] = date('Y-m-d', strtotime($params['start_date']));
        }
        if($params['end_date'] && is_valid_date($params['end_date'], 'm/d/Y')){
            $search['DATE(a.datetime) <='] = date('Y-m-d', strtotime($params['end_date']));
        }
        if($params['end_date']){
            $wildcards['name'] = $params['name'];
        }
        if($params['category'] && in_array($params['category'], [APPROVED_ACTIVITIES, PAST_ACTIVITIES, DECLINED_ACTVITIES, PROPOSED_ACTIVITIES])){
            $category = $params['category'];
        }
        return compact(['search', 'wildcards', 'category']);
    }

    public function index()
    {
        $this->import_plugin_script('bootstrap-datepicker/js/bootstrap-datepicker.min.js');
        $this->import_page_script('activity-listing.js');
        $params = $this->_search_params();
        $this->generate_page('activities/listing', [
            'items' => $this->activity->set_category($params['category'])->all($params['search'], $params['wildcards'])
        ]); 
    }

    public function view_participants($id = FALSE)
    {
        if(!$id || !$this->activity->is_approved($id)){
            show_404();
        }
        $this->import_page_script('manage-requests.js');
        $activity = $this->activity->get($id);
        $this->generate_page('activities/participants', [
            'form_title' => "View all participants for: {$activity['name']}",
            'participants' => $this->activity->get_participants($id),
            'activity_id' => $id
        ]);

    }

    public function view($id = FALSE)
    {
        if(!$id || !$activity = $this->activity->view_activity($id, FALSE)){
            show_404();
        }
        $this->import_page_script('faci-view-activity.js');
        $this->generate_page('activities/view', [
            'data' => $activity,
            'is_participant' => $this->activity->has_participant_with_id($id, pk()),
            'facilitators' => $this->activity->get_facilitators($id),
            'facilitators_count' => $this->activity->get_facilitators_count($id),
        ]);
    }

    public function create()
    {
        $this->import_plugin_script([
            'bootstrap-wysiwyg/bootstrap3-wysihtml5.all.min.js', 
            'moment.min.js', 
            'bootstrap-datetimepicker/bs-datetimepicker.min.js',
            'select2/select2.min.js'
        ]);
        $this->import_page_script('manage-activity.js');
        $this->generate_page('activities/manage', [
            'form_title' => 'Create new activity',
            'action' => ACTION_CREATE,
            'program_natures' => $this->activity->program_natures(),
            'program_areas' => $this->activity->program_areas(),
            'data' => [],
            'is_facilitator' => FALSE
        ]);
    }

    public function store()
    {
        $this->_perform_validation();
        if(!$this->form_validation->run()){
            $this->json_response(['result' => FALSE, 'messages' => array_values($this->form_validation->error_array())]);
            return;
        }
        $input = $this->_format_data(ACTION_CREATE);
        $created = $this->activity->create($input['data'], $input['facilitator']);
        if($created){
            $this->json_response(['result' => TRUE]);
            return;
        }
        $this->json_response(['result' => FALSE, 'messages' => ['Cannot perform action due to an unknown. Please try again later.']]);
    }

    public function edit($id = FALSE)
    {
        if(!$id || !$activity = $this->activity->get($id)){
            show_404();
        }
        $this->import_plugin_script([
            'bootstrap-wysiwyg/bootstrap3-wysihtml5.all.min.js', 
            'moment.min.js', 
            'bootstrap-datetimepicker/bs-datetimepicker.min.js',
            'select2/select2.min.js'
        ]);
        $this->import_page_script('manage-activity.js');
        $this->generate_page('activities/manage', [
            'form_title' => "Update activity: {$activity['name']}",
            'action' => ACTION_UPDATE,
            'program_natures' => $this->activity->program_natures(),
            'program_areas' => $this->activity->program_areas(),
            'data' => $activity,
            'is_facilitator' => $this->activity->has_participant_with_id($id, $this->session->userdata('id'))
        ]);

    }

    public function update($id = FALSE)
    {
        if(!$id || !$activity = $this->activity->exists($id)){
            $this->json_response(['result' => FALSE, 'messages' => ['Please choose an activity to update.']]);
            return;
        }
        $this->id = $id;
        $this->_perform_validation(ACTION_UPDATE);
        if(!$this->form_validation->run()){
            $this->json_response(['result' => FALSE, 'messages' => array_values($this->form_validation->error_array())]);
            return;
        }
        $input = $this->_format_data(ACTION_UPDATE);
        $updated = $this->activity->update($id, $input['data'], $input['facilitator']);
        if($updated){
            $this->json_response(['result' => TRUE]);
            return;
        }
        $this->json_response(['result' => FALSE, 'messages' => ['Cannot perform action due to an unknown. Please try again later.']]);
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


    public function join_activity()
    {
        $this->form_validation->set_rules('id', '', 'required|callback__validate_activity');
        if(!$this->form_validation->run()){
            $this->json_response(['result' => FALSE, 'messages' => array_values($this->form_validation->error_array())]);
            return;
        }
        if($this->activity->join($this->input->post('id'), pk())){
            $this->json_response(['result' => TRUE]);
            return;
        }
        $this->json_response(['result' => FALSE, 'messages' => ['Cannot perform action due to an unknown error. Please try again later.']]);
    }

    public function leave_activity()
    {
        $id = $this->input->post('id');
        if(!$this->activity->has_participant_with_id($id, pk())){
            $this->json_response(['result' => FALSE, 'messages' => ['You are not in the activity.']]);
            return;
        }
        if($this->activity->leave($id, pk())){
            $this->json_response(['result' => TRUE]);
            return;
        }
        $this->json_response(['result' => FALSE, 'messages' => ['Cannot perform action due to an unknown error. Please try again later.']]);
    }

    public function _perform_validation($action = ACTION_CREATE)
    {
        $this->form_validation->set_rules('name', 'name', 'required');
        $this->form_validation->set_rules('description', 'description', 'required');
        $this->form_validation->set_rules('location', 'location', 'required');
        $this->form_validation->set_rules('start_datetime', 'start date and time', 'callback__validate_datetime');
        $this->form_validation->set_rules('datetime', 'end date and time', 'callback__validate_datetime');
        $this->form_validation->set_rules('population', 'population', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('nature_id', 'nature of the activity', 'callback__validate_nature_id');
        $this->form_validation->set_rules('area_id', 'area of the activity', 'callback__validate_area_id');
        $this->form_validation->set_rules('status', 'status', 'callback__validate_status');
        $this->form_validation->set_rules('decline_reason', 'decline reason', 'callback__validate_decline_reason');
        $this->form_validation->set_rules('facilitate', '', "callback__validate_facilitate[{$action}]");
    }

    public function _format_data($action = ACTION_CREATE)
    {
        $data = elements(['name', 'description', 'location', 'population', 'nature_id', 'area_id'], $this->input->post());
        // set formatted date for timestamp data type
        $data['datetime'] = date_create($this->input->post('datetime'))->format('Y-m-d H:i:s');
        $data['start_datetime'] = date_create($this->input->post('start_datetime'))->format('Y-m-d H:i:s');
        if(user_type(USER_TYPE_SUPERUSER)){
            $data['status'] = $this->input->post('status');
            if($data['status'] === 'd'){
                $data['decline_reason'] = $this->input->post('decline_reason');
            }
        }
        if(user_type(USER_TYPE_SUPERUSER)){
            $data['facilitator_limit'] = $this->input->post('facilitator_limit');
        }
        if($action === ACTION_CREATE){
            $data['type'] = 'a';
            $data['created_by'] = $this->session->userdata('id');
        }
        return [
            'data' => $data,
            'facilitator' => [
                'id' => $this->session->userdata('id'),
                'action' => $this->input->post('facilitate') ? 'join' : 'leave'
            ]
        ];
    }

    public function _validate_status($status)
    {   
        if($status){
            $this->form_validation->set_message('_validate_status', 'You are not allowed to set activity status.');
            return user_type(USER_TYPE_SUPERUSER) && in_array($status, ['d', 'a', 'p']);
        }
        return TRUE;
    }


    public function _validate_datetime($datetime)
    {
        $this->load->helper('cesdate');
        $this->form_validation->set_message('_validate_datetime', 'Please input a valid %s with format: m/d/Y hh:mm AM/PM');
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

    public function _validate_decline_reason($reason)
    {
        $this->form_validation->set_message('_validate_decline_reason', 'Please provide a valid %s');
        $status = $this->input->post('status');
        if($status && $status === 'd'){
            return (bool)trim($reason);
        } 
        return TRUE;
    }


    public function _validate_facilitate($facilitate, $action)
    {
        if(!$facilitate || $action === ACTION_CREATE || user_type(USER_TYPE_SUPERUSER)){
            return TRUE;
        }
        $this->form_validation->set_message('_validate_facilitate', 'Unable to %s this activity. Facilitators has reached max limit.');
        $faci_count = $this->activity->get_facilitators_count($this->id, pk());
        return $faci_count < MAX_FACI_LIMIT_PER_ACTIVITY;
    }

    public function _validate_activity($id)
    {
        $this->form_validation->set_message('_validate_activity', 'Activity does not exist.');
        if($this->activity->exists($id)){
            $this->form_validation->set_message('_validate_activity', 'Unable to facilitate this activity. Facilitators has reached max limit.');
            return $this->activity->get_facilitators_count($id) < MAX_FACI_LIMIT_PER_ACTIVITY;
        }
        return FALSE;
    }

}