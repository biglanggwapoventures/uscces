<?php

class Facilitators extends CES_Controller
{

    protected $tab_title = 'Facilitators';
    protected $active_nav = NAV_ADM_FACI;

    public function __construct()
    {
        parent::__construct();
        $this->load->model('Facilitator_model', 'faci');
    }

    public function index()
    {
        $this->import_page_script('faci-listing.js');
        $this->generate_page('facilitators/listing', [
            'items' => $this->faci->all()
        ]);
    }

    public function create()
    {
        $this->import_page_script('manage-faci.js');
        $this->generate_page('facilitators/manage', [
            'form_title' => 'Create new facilitator',
            'action' => ACTION_CREATE,
            'data' => []
        ]);
    }

    public function edit($id = FALSE)
    {
        if(!$id || !$faci = $this->faci->get($id)){
            show_404();
        }
        $this->import_page_script('manage-faci.js');
        $this->generate_page('facilitators/manage', [
            'form_title' => "Update facilitator: {$faci['username']}",
            'action' => ACTION_UPDATE,
            'data' => $faci
        ]);

    }

    public function store()
    {
        $this->_perform_validation(ACTION_CREATE);
        if(!$this->form_validation->run()){
            $this->json_response(['result' => FALSE, 'messages' => array_values($this->form_validation->error_array())]);
            return;
        }
        $input = $this->_format_data();
        $created = $this->faci->create($input);
        if($created){
            $this->json_response(['result' => TRUE]);
            return;
        }
        $this->json_response(['result' => FALSE, 'messages' => ['Cannot perform action due to an unknown. Please try again later.']]);
    }

    public function reset_password()
    {
        $id = $this->input->post('id');
        if(!$id || !$this->faci->exists($id)){
            $this->json_response(['result' => FALSE, 'messages' => ['Facilitator not found!']]);
            return;
        }
        if($this->faci->reset_password($id)){
            $this->json_response(['result' => TRUE]);
            return;
        }
        $this->json_response(['result' => FALSE, 'messages' => ['Cannot perform action due to an unknown error. Please try again later.']]);
    }


    public function update($id = FALSE)
    {
        if(!$id || !$faci = $this->faci->get($id)){
            $this->json_response(['result' => FALSE, 'messages' => ['Please choose a facilitator to update.']]);
            return;
        }
        $this->id = $id;
        $this->_perform_validation(ACTION_UPDATE);
        if(!$this->form_validation->run()){
            $this->json_response(['result' => FALSE, 'messages' => array_values($this->form_validation->error_array())]);
            return;
        }
        $input = $this->_format_data(ACTION_UPDATE);
        $updated = $this->faci->update($id, $input);
        if($updated){
            $this->json_response(['result' => TRUE]);
            return;
        }
        $this->json_response(['result' => FALSE, 'messages' => ['Cannot perform action due to an unknown. Please try again later.']]);
    }

    public function _perform_validation($action = ACTION_CREATE)
    {
        if($action === ACTION_CREATE){
            $this->form_validation->set_rules('username', 'id number', 'required|is_unique[users.username]');
        }else{
            $this->form_validation->set_rules('username', 'id number', 'required|callback__validate_username');
        }
        $this->form_validation->set_rules('firstname', 'first name', 'required');
        $this->form_validation->set_rules('middlename', 'middle name', 'required');
        $this->form_validation->set_rules('lastname', 'last name', 'required');
        $this->form_validation->set_rules('email', 'email', 'required|valid_email');
        $this->form_validation->set_rules('mobile', 'mobile', 'required|numeric|exact_length[11]');
    }

    public function _format_data($action = ACTION_CREATE)
    {
        $data = elements(['username', 'firstname', 'middlename', 'lastname', 'email', 'mobile'], $this->input->post());
        if($action === ACTION_UPDATE){
            $data['is_locked'] = $this->input->post('is_locked') ? 1 : 0;
        }
        return $data;
    }

    public function _validate_username($username)
    {
        $this->form_validation->set_message('_validate_username', 'The %s is already been taken.');
        return $this->faci->has_unique_username($username, $this->id);
    }

}
